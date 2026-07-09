import io
from flask import Flask, request, jsonify
from minio import Minio
import mysql.connector
from PIL import Image

app = Flask(__name__)

MINIO_CONFIG = {
    "endpoint": "10.55.100.198:9000",
    "access_key": "minioadmin",
    "secret_key": "admin@123",
    "secure": False
}

MINIO_CLIENT = Minio(
    MINIO_CONFIG["endpoint"],
    access_key=MINIO_CONFIG["access_key"],
    secret_key=MINIO_CONFIG["secret_key"],
    secure=MINIO_CONFIG["secure"]
)

DB_CONFIG = {
    "host": "10.55.100.198",
    "user": "root",
    "password": "uas@cc123",
    "database": "cc_db",
    "port": 3306
}

BUCKET_NAME = "images"

@app.route('/webhook-minio', methods=['POST'])
def on_upload_trigger():
    event_data = request.get_json()
    if not event_data or 'Records' not in event_data:
        return jsonify({"status": "ignored"}), 200

    record = event_data['Records'][0]
    object_name = record['s3']['object']['key']

    if object_name.startswith("thumb_"):
        return jsonify({"status": "ignored"}), 200

    try:
        response = MINIO_CLIENT.get_object(BUCKET_NAME, object_name)
        image_bytes = response.read()

        img = Image.open(io.BytesIO(image_bytes))
        img = img.resize((200, 200))

        thumb_buffer = io.BytesIO()
        img.save(thumb_buffer, format='JPEG')
        thumb_len = thumb_buffer.tell()
        thumb_buffer.seek(0)

        thumb_name = f"thumb_{object_name}"
        MINIO_CLIENT.put_object(BUCKET_NAME, thumb_name, thumb_buffer, thumb_len)

        public_minio_url = f"http://10.55.100.198:9000/{BUCKET_NAME}/{thumb_name}"

        db_conn = mysql.connector.connect(**DB_CONFIG)
        cursor = db_conn.cursor()
        sql = "INSERT INTO images (filename, minio_url) VALUES (%s, %s)"
        cursor.execute(sql, (thumb_name, public_minio_url))
        db_conn.commit()

        cursor.close()
        db_conn.close()
        print(f"Sukses resize & catat database untuk: {thumb_name}")

    except Exception as e:
        print(f"Error ketika memproses file {object_name}: {str(e)}")
        return jsonify({"status": "error", "message": str(e)}), 500

    return jsonify({"status": "success"}), 200

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001)
    

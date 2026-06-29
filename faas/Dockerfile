FROM python:3.9-slim
WORKDIR /app
RUN pip install flask minio mysql-connector-python pillow
COPY app.py .
EXPOSE 5001
CMD ["python", "app.py"]

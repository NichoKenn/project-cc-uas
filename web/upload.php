<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;

if ($_FILES['photo']['name']) {
    if (!$_FILES['photo']['error']) {

        $file_info = getimagesize($_FILES['photo']['tmp_name']);
        if (empty($file_info)) {
            $message = "File bukan gambar.";
        } elseif ($_FILES['photo']['size'] > 10485760) {
            $message = "File terlalu besar!";
        } else {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;

            $s3 = new S3Client([
                'version'  => 'latest',
                'region'   => 'us-east-1',
                'endpoint' => 'http://10.55.100.198:9000',
                'use_path_style_endpoint' => true,
                'credentials' => ['key' => 'minioadmin', 'secret' => 'admin@123'],
            ]);
            $s3->putObject([
                'Bucket' => 'images',
                'Key'    => $filename,
                'Body'   => fopen($_FILES['photo']['tmp_name'], 'rb'),
                'ACL'    => 'public-read',
            ]);

            
            // $url = 'http://10.55.100.198:9000/images/' . $filename;

            // $conn = mysqli_connect("10.55.100.198", "root", "uas@cc123", "cc_db", 3306);

            // $filename_escaped = mysqli_escape_string($conn, $filename);
            // $url_escaped = mysqli_escape_string($conn, $url);
            // mysqli_query($conn, "INSERT INTO images (filename, minio_url) VALUES ('$filename_escaped', '$url_escaped')");

            $message = "Upload berhasil!";
        }
    } else {
        $message = "Error upload: " . $_FILES['photo']['error'];
    }
} else {
    $message = "Tidak ada file dipilih!";
}
echo $message . ' <a href="index.php">Kembali</a>';
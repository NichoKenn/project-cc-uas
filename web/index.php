<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gallery</title>
</head>
<body>
    <h2>Upload Gambar</h2>
    <form action="upload.php" method="post" enctype="multipart/form-data">
        <label>Pilih gambar :</label><br>
        <input type="file" name="photo">
        <input type="submit" value="Upload" name="btnsubmit">
    </form>
    <h2>Galeri CC</h2>
    <?php
        $conn = mysqli_connect("10.55.100.198", "root", "uas@cc123", "cc_db", 3306);
        $images = mysqli_query($conn, "SELECT * FROM images ORDER BY id DESC");
        while ($row = mysqli_fetch_assoc($images)):
    ?>
        <img src=" <?= $row['minio_url'] ?>" style="object-fit:cover; margin: 5px;">
    <?php endwhile; ?>
</body>
</html>
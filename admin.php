<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "image_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Upload Image
    if (isset($_FILES['image'])) {
        $imagePath = 'uploads/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $sql = "INSERT INTO images (image_path) VALUES ('$imagePath')";
            $conn->query($sql);
        }
    }
x
    // Delete Image
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];
        $sql = "SELECT image_path FROM images WHERE id=$id";
        $result = $conn->query($sql);
        $image = $result->fetch_assoc();
        
        if ($image && file_exists($image['image_path'])) {
            unlink($image['image_path']); // Delete file from folder
        }
        
        $conn->query("DELETE FROM images WHERE id=$id"); // Delete record from database
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
    <style>
    body {
        font-family: 'Montserrat', sans-serif;
        background-color: #ffe6f0; /* Soft pink background */
        margin: 0;
        padding: 0;
        color: #4a4a4a;
    }
    .header {
        background-color: #ff4d94; /* Vibrant pink header */
        color: #ffffff;
        padding: 15px 20px;
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
        border-bottom: 3px solid #ff1a66;
    }
    .container {
        max-width: 750px;
        margin: 30px auto;
        background: #fff0f6; /* Light blush container background */
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 8px 15px rgba(255, 105, 180, 0.3); /* Pink glow shadow */
        border: 2px solid #ffc2d1; /* Soft pink border */
    }
    .upload-form, .image-display {
        margin: 25px 0;
    }
    .upload-form input[type="file"] {
        margin-right: 12px;
        border: 1px solid #ffb3c6;
        border-radius: 5px;
        padding: 6px;
    }
    .upload-form button {
        background-color: #ff6699; /* Medium pink button */
        color: #ffffff;
        border: none;
        padding: 10px 18px;
        font-size: 16px;
        font-weight: bold;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        box-shadow: 0 4px 8px rgba(255, 102, 153, 0.3);
    }
    .upload-form button:hover {
        background-color: #ff3366; /* Deeper pink on hover */
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(255, 51, 102, 0.4);
    }
    .image-display {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: center;
    }
    .image-display img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        border: 3px solid #ffb3c6; /* Light pink border */
        box-shadow: 0 4px 10px rgba(255, 153, 204, 0.3);
        transition: transform 0.3s;
    }
    .image-display img:hover {
        transform: scale(1.05);
        box-shadow: 0 6px 12px rgba(255, 102, 153, 0.4);
    }
    .delete-button {
        background-color: #ff1744; /* Bold pink delete button */
        color: white;
        border: none;
        padding: 6px 12px;
        font-size: 14px;
        font-weight: bold;
        border-radius: 5px;
        cursor: pointer;
        margin-top: 5px;
        transition: background-color 0.3s, transform 0.2s;
    }
    .delete-button:hover {
        background-color: #d50000; /* Darker red on hover */
        transform: translateY(-1px);
    }
</style>


    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this image?");
        }
    </script>
</head>
<body>
    <div class="header">
        <h1>Admin Page</h1>
    </div>

    <div class="container">
        <div class="upload-form">
            <form action="admin.php" method="post" enctype="multipart/form-data">
                <input type="file" name="image" required>
                <button type="submit"><i class="fas fa-upload"></i> Upload Image</button>
            </form>
        </div>

        <h2>Uploaded Images</h2>
        <div class="image-display">
            <?php
            $result = $conn->query("SELECT * FROM images");
            while ($row = $result->fetch_assoc()) {
                echo '<div style="display:inline-block; margin: 10px; text-align: center;">';
                echo '<img src="' . $row['image_path'] . '" alt="Uploaded Image">';
                echo '<form action="admin.php" method="post" style="display:inline;" onsubmit="return confirmDelete();">
                        <button type="submit" name="delete" value="' . $row['id'] . '" class="delete-button">Delete</button>
                      </form>';
                echo '</div>';
            }
            $conn->close();
            ?>
        </div>
    </div>
</body>
</html>

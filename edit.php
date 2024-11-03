<?php
if (isset($_POST['update'])) {
    // Ambil data dari form
    $id = $_POST['id'];
    $kecamatan = $_POST['kecamatan'];
    $longitude = $_POST['longitude'];
    $latitude = $_POST['latitude'];
    $luas = $_POST['luas'];
    $jumlah_penduduk = $_POST['jumlah_penduduk'];

    // Pengaturan koneksi database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pbwebAc8";

    // Buat koneksi
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Cek koneksi
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk mengupdate data
    $sql = "UPDATE 7b SET 
                kecamatan = '$kecamatan', 
                longitude = $longitude, 
                latitude = $latitude, 
                luas = $luas, 
                jumlah_penduduk = $jumlah_penduduk 
            WHERE id = $id";

    // Eksekusi query update
    if ($conn->query($sql) === TRUE) {
        echo "Data berhasil diperbarui";
        header("Location: index.php"); // Arahkan ke halaman index.php setelah update
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Tutup koneksi
    $conn->close();
}
?>


<!DOCTYPE html>
<html>
<head>
    <title>Edit Data</title>

    <style>
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<?php
// Ambil ID dari URL
$id = $_GET['id'];

// Pengaturan koneksi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pbwebAc8";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ambil data berdasarkan ID
$sql = "SELECT * FROM 7b WHERE id = $id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Tampilkan data dalam form
    $row = $result->fetch_assoc();
    ?>
    <form action="edit.php" method="post">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <label>Kecamatan:</label>
        <input type="text" name="kecamatan" value="<?php echo $row['kecamatan']; ?>"><br>
        <label>Longitude:</label>
        <input type="text" name="longitude" value="<?php echo $row['longitude']; ?>"><br>
        <label>Latitude:</label>
        <input type="text" name="latitude" value="<?php echo $row['latitude']; ?>"><br>
        <label>Luas:</label>
        <input type="text" name="luas" value="<?php echo $row['luas']; ?>"><br>
        <label>Jumlah Penduduk:</label>
        <input type="text" name="jumlah_penduduk" value="<?php echo $row['jumlah_penduduk']; ?>"><br>

        <button type="submit" name="update">Update</button>
    </form>
    <?php
} else {
    echo "Data tidak ditemukan";
}
$conn->close();
?>

</body>
</html>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>PETA INTERAKTIF-sheryn</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        #map {
            width: 100%;
            height: 400px;
            margin: 0 auto;
            border: 1px solid black;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            margin-top: 20px;
            margin-bottom: 20px;
        }

        header {
            background-color: #9759a2;
            color: white;
            padding: 10px;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin-top: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
        }

        table th {
            background-color: #998cf2;
            color: black;
            padding: 10px;
            font-family: 'Times New Roman', Times, serif;
        }

        table td {
            background-color: #e9baf8;
            color: black;
            padding: 8px;
            border: 1px solid black;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
        }

        table tr:hover td {
            background-color: #ffffff;
            color: black;
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
</head>

<body>

    <header>
        <h1>Peta Sebaran Penduduk dan Data Tabel Kecamatan</h1>
    </header>

    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Inisialisasi peta
        var map = L.map("map").setView([-7.774835, 110.374301], 10);

        // Tile Layer Base Map
        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });

        // Menambahkan basemap ke dalam peta
        osm.addTo(map);
    </script>

    <script>
        <?php
        // Koneksi ke database MySQL
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "pbwebAc8";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Proses hapus data
        if (isset($_GET['hapus'])) {
            $id_hapus = $_GET['hapus'];
            $sql_hapus = "DELETE FROM 7b WHERE id = $id_hapus";
            if ($conn->query($sql_hapus) === TRUE) {
                echo "alert('Data dengan ID $id_hapus berhasil dihapus.');";
            } else {
                echo "alert('Error: " . $conn->error . "');";
            }
        }

        // Query data
        $sql = "SELECT * FROM 7b";
        $result = $conn->query($sql);

        // Tambahkan marker pada peta berdasarkan data dari database
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $long = $row["longitude"];
                $lat = $row["latitude"];
                $kec = $row["kecamatan"];

                echo "L.marker([$lat, $long]).addTo(map).bindPopup('$kec');\n";
            }
        } else {
            echo "console.log('0 results');";
        }
        ?>
    </script>

    <?php
    // Tampilkan tabel data dari database
    if ($result->num_rows > 0) {
        echo "<table><tr>
                <th>Id</th>
                <th>Kecamatan</th>
                <th>Longitude</th>
                <th>Latitude</th>
                <th>Luas</th>
                <th>Jumlah Penduduk</th>
                <th>Hapus</th>
                <th>Edit</th>
                </tr>";

        // Output setiap baris data
        $result->data_seek(0); 
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row["id"] . "</td>
                    <td>" . $row["kecamatan"] . "</td>
                    <td>" . $row["longitude"] . "</td>
                    <td>" . $row["latitude"] . "</td>
                    <td>" . $row["luas"] . "</td>
                    <td>" . $row["jumlah_penduduk"] . "</td>
                    <td><a href='?hapus=" . $row["id"] . "' onclick='return confirm(\"Serius mau hapus data ini?\")'>Hapus</a></td>
                    <td><a href='edit.php?id=" . $row["id"] . "'>Edit</a></</td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "<p>pilih data dulu!</p>";
    }

    $conn->close();
    ?>
</body>

</html>

<?php
$servername = "localhost";
$username = "root";
$password = "gtgy1tm1";
$dbname = "2019_ekivalensi";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$nim = intval($_GET['nim']);

function getTPB($conn,$nim) {
    $data = [];

    $result = $conn->query("SELECT * FROM `nilai` WHERE SUBSTR(kode,3,1) = 1 AND nim = $nim");

    if ($result->num_rows > 0) {

        // output data of each row
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}

function getWajib($conn,$nim) {
    $data = [];

    $result = $conn->query("SELECT * FROM `wajib` LEFT JOIN (SELECT * FROM nilai WHERE nilai.nim = $nim AND nilai.status = 'X') as nilai on wajib.kode_lama = nilai.kode");

    if ($result->num_rows > 0) {

        // output data of each row
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}

function getPilihan($conn,$nim) {
    $data = [];

    $result = $conn->query("SELECT * FROM pilihan RIGHT JOIN (SELECT nilai.nim, nilai.nama, nilai.tahun, nilai.semester, nilai.semkur, nilai.urut, nilai.kode, nilai.matakuliah,nilai.sks, nilai.nilai, nilai.status FROM `wajib` RIGHT JOIN (SELECT * FROM nilai WHERE nilai.nim = $nim AND nilai.status = 'X') as nilai on wajib.kode_lama = nilai.kode WHERE kode_lama IS NULL AND SUBSTR(nilai.kode,3,1) <> 1) as n on pilihan.kode_lama = n.kode");

    if ($result->num_rows > 0) {

        // output data of each row
        while($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
    }
    
    return $data;
}


echo json_encode([  'tpb' => getTPB($conn,$nim), 'wajib' => getWajib($conn,$nim), 'pilihan' => getPilihan($conn,$nim) ]);

$conn->close();
?>

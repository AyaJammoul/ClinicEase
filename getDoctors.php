<?php
include("connect.php");

if (isset($_GET['speciality'])) {
    $speciality = $_GET['speciality'];

    $stmt = $conn->prepare("SELECT doctorid as id, doctorname as name FROM Doctor WHERE speciality = ?");
    $stmt->bind_param("s", $speciality);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = [];
    while ($row = $result->fetch_assoc()) {
        $doctors[] = $row;
    }

    echo json_encode($doctors);
    $stmt->close();
} else {
    echo json_encode([]);
}

$conn->close();
?>

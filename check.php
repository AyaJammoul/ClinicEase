<?php
include("connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = $_POST['fullname'];
    $patient_phone = $_POST['phone'];

    $sql = "SELECT patientname, mobilenumber FROM patientreg WHERE patientname = ? AND mobilenumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $patient_name, $patient_phone);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['loggedin'] = true;
        $_SESSION['patientname'] = $row['patientname'];
        $_SESSION['mobilenumber'] = $row['mobilenumber'];
        echo "found";
    } else {
        echo "not found";
    }
    $stmt->close();
}
$conn->close();
?>

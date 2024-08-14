<?php
session_start();
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_name = $_POST['fullname'];
    $patient_address = $_POST['address'];
    $patient_home_number = $_POST['homenumber'];
    $patient_mobile_number = $_POST['mobilenumber'];
    $patient_email = $_POST['email'];
    $patient_birthdate = $_POST['birthdate'];
    $patient_sex = $_POST['sex'];
    $patient_ssn = $_POST['socialsecurity'];
    $patient_marital_status = $_POST['maritalstatus'];
    $regdate = date("Y-m-d");
    
    $check_sql = "SELECT patientname, mobilenumber FROM Patientreg WHERE patientname = ? AND mobilenumber = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $patient_name, $patient_mobile_number);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "User already exists. Redirecting to check.";
    } else {
        $sql = "INSERT INTO Patientreg (patientname, address, homenumber, mobilenumber, email, birthdate, sex, socialsecurity, maritalstatus, regdate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $patient_name, $patient_address, $patient_home_number, $patient_mobile_number, $patient_email, $patient_birthdate, $patient_sex, $patient_ssn, $patient_marital_status, $regdate);
        
        if ($stmt->execute()) {
            $_SESSION['loggedin'] = true;
            $_SESSION['patientname'] = $patient_name;
            $_SESSION['mobilenumber'] = $patient_mobile_number;
            echo "registered";
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
    $check_stmt->close();
}
$conn->close();
?>

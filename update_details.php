<?php
session_start();
include("connect.php");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['patientname'];
        $address = $_POST['address'];
        $home = $_POST['homenumber'];
        $mobile = $_POST['mobilenumber'];
        $email = $_POST['email'];
        $birthday = $_POST['birthdate'];
        $social = $_POST['socialsecurity'];
        $gender = $_POST['sex'];
        $marital = $_POST['maritalstatus'];

        $original_name = $_SESSION['patientname'];
        $original_mobile = $_SESSION['mobilenumber'];

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT patientid FROM Patientreg WHERE patientname = ? AND mobilenumber = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }
        $stmt->bind_param("ss", $original_name, $original_mobile);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
        $stmt->bind_result($patient_id);
        $stmt->fetch();
        $stmt->close();

        if (!$patient_id) {
            throw new Exception("Patient not found.");
        }

        $stmt = $conn->prepare("UPDATE Patientreg SET 
            patientname = ?, 
            address = ?, 
            homenumber = ?, 
            mobilenumber = ?, 
            email = ?, 
            birthdate = ?, 
            socialsecurity = ?, 
            sex = ?, 
            maritalstatus = ?
            WHERE patientid = ?");
        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        $stmt->bind_param("sssssssssi", $name, $address, $home, $mobile, $email, $birthday, $social, $gender, $marital, $patient_id);
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute statement: " . $stmt->error);
        }
        $stmt->close();

        $_SESSION['patientname'] = $name;
        $_SESSION['mobilenumber'] = $mobile;

        echo "success";
    } else {
        echo "Invalid request method";
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo "error: " . $e->getMessage();
} finally {
    $conn->close();
}
?>

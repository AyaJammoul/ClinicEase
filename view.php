<?php
include("connect.php");

$type = isset($_GET['type']) ? $_GET['type'] : '';

if ($type == 'clinics') {
    $sql_clinic = "SELECT clinicname FROM Clinic WHERE status = 'Active'";
    $result_clinic = $conn->query($sql_clinic);
    $clinics = array();
    if ($result_clinic->num_rows > 0) {
        while($row = $result_clinic->fetch_assoc()) {
            $clinics[] = $row['clinicname'];
        }
    }
    echo json_encode(['clinics' => $clinics]);

} elseif ($type == 'specializations') {
    $clinic = isset($_GET['clinic']) ? $_GET['clinic'] : '';
    $sql_specialization = "SELECT DISTINCT speciality FROM Availableprocedures WHERE clinicname = ?";
    $stmt = $conn->prepare($sql_specialization);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $clinic);
    $stmt->execute();
    $result = $stmt->get_result();

    $specializations = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $specializations[] = $row['speciality'];
        }
    }
    echo json_encode(['specializations' => $specializations]);

} elseif ($type == 'doctors') {
    $speciality = isset($_GET['speciality']) ? $_GET['speciality'] : '';
    $sql_doctors = "SELECT d.doctorname AS name, d.speciality AS specialization, s.clinicname FROM Doctor d JOIN Staff s ON d.staffid = s.staffid WHERE s.status = 'active' AND d.speciality = ?";
    $stmt = $conn->prepare($sql_doctors);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $speciality);
    $stmt->execute();
    $result = $stmt->get_result();

    $doctors = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $doctors[] = $row['name'];
        }
    }
    echo json_encode(['doctors' => $doctors]);

} elseif ($type == 'procedures') {
    $speciality = isset($_GET['speciality']) ? $_GET['speciality'] : '';
    $sql_procedures = "SELECT procedurename, duration FROM Availableprocedures WHERE speciality = ?";
    $stmt = $conn->prepare($sql_procedures);
    if ($stmt === false) {
        die('Prepare failed: ' . htmlspecialchars($conn->error));
    }
    $stmt->bind_param("s", $speciality);
    $stmt->execute();
    $result = $stmt->get_result();

    $procedures = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $procedures[] = array("name" => $row['procedurename'], "duration" => $row['duration']);
        }
    }
    echo json_encode(['procedures' => $procedures]);
}

$conn->close();
?>

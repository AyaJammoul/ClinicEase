<?php
include("connect.php");

if (isset($_GET['speciality'])) {
    $speciality = $_GET['speciality'];

    $stmt = $conn->prepare("SELECT procedureid, procedurename, duration FROM Availableprocedures WHERE speciality = ?");
    $stmt->bind_param("s", $speciality);
    $stmt->execute();
    $result = $stmt->get_result();

    $procedures = array();
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $procedures[] = array("id" => $row['procedureid'], "name" => $row['procedurename'], "duration" => $row['duration']);
        }
    }
    echo json_encode($procedures);
    $stmt->close();
} else {
    echo 'No speciality specified';
}

$conn->close();
?>

<?php
include("connect.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['patientid']) && !empty($_POST['patientid'])) {
        $patient_id = trim($_POST['patientid']);

        // Prepare the SQL statement
        $sql = "SELECT patientid, patientname, mobilenumber FROM Patientreg WHERE patientid = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind the parameter and execute the statement
            $stmt->bind_param("s", $patient_id);
            $stmt->execute();

            // Get the result
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Fetch the associated row
                $row = $result->fetch_assoc();
                $_SESSION['loggedin'] = true;
                $_SESSION['patientid'] = $row['patientid'];
                $_SESSION['patientname'] = $row['patientname'];
                $_SESSION['mobilenumber'] = $row['mobilenumber'];
                echo "found";
            } else {
                echo "not found";
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Database error: Unable to prepare statement.";
        }
    } else {
        echo "Patient ID is required.";
    }
} else {
    echo "Invalid request method.";
}

// Close the connection
$conn->close();
?>

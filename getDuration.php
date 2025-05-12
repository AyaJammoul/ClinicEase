<?php
include("connect.php");

if (isset($_GET['procedure'])) {
    $procedureId = intval($_GET['procedure']); // Ensure procedureId is an integer

    // Debugging: Log the procedure ID being queried
    error_log("Procedure ID: " . $procedureId);

    // Prepare and execute the SQL statement
    if ($stmt = $conn->prepare("SELECT duration FROM Availableprocedures WHERE procedureid = ?")) {
        $stmt->bind_param("i", $procedureId);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if a row was returned
        if ($row = $result->fetch_assoc()) {
            // Debugging: Log the fetched duration
            error_log("Fetched Duration: " . $row['duration']);
            echo htmlspecialchars($row['duration']) . ' minutes';
        } else {
            // Debugging: Log the case where no row was found
            error_log("No duration found for procedure ID: " . $procedureId);
            echo 'Duration not found';
        }
        $stmt->close();
    } else {
        // Log and display an error message if the statement could not be prepared
        error_log("Failed to prepare statement: " . $conn->error);
        echo 'Error fetching duration';
    }
} else {
    echo 'No procedure specified';
}

$conn->close();
?>

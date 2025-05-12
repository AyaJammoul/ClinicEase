<?php
include("connect.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $doctorName = $_POST['doctorname']; 
    $appointmentDate = $_POST['appointmentdate'];

    function getDoctorId($conn, $doctorName) {
        $stmt = $conn->prepare("SELECT doctorid FROM Doctor WHERE doctorname = ?");
        $stmt->bind_param("s", $doctorName);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->num_rows > 0 ? $result->fetch_assoc()['doctorid'] : null;
    }

    $doctorId = getDoctorId($conn, $doctorName);
    if ($doctorId === null) {
        echo "Invalid doctor name.";
        exit();
    }
    $dayOfWeek = date('l', strtotime($appointmentDate));
    $stmt = $conn->prepare("SELECT openingtime, closingtime FROM Schedule WHERE days = ?");
    $stmt->bind_param("s", $dayOfWeek);
    $stmt->execute();
    $result = $stmt->get_result();
    $schedule = $result->fetch_assoc();

    if (!$schedule) {
        echo "No schedule found for $dayOfWeek.";
        exit();
    }

    $openingTime = $schedule['openingtime'];
    $closingTime = $schedule['closingtime'];
    $sql = "SELECT apptime, duration FROM Appointment WHERE doctorid = ? AND appdate = ? AND status='S'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $doctorId, $appointmentDate);
    $stmt->execute();
    $result = $stmt->get_result();

    $existingAppointments = [];
    while ($row = $result->fetch_assoc()) {
        $existingAppointments[] = [
            'time' => $row['apptime'],
            'duration' => $row['duration']
        ];
    }

    $stmt->close();
    $breakStartTime = strtotime("1:00 PM");
    $breakEndTime = strtotime("2:00 PM");
    $availableTimes = [];
    $currentTime = strtotime($openingTime);
    $endTime = strtotime($closingTime);

    while ($currentTime < $endTime) {
        $isAvailable = true;
        $slotEndTime = strtotime("+30 minutes", $currentTime);
        if ($currentTime >= $breakStartTime && $slotEndTime <= $breakEndTime) {
            $isAvailable = false;
        }

        foreach ($existingAppointments as $appointment) {
            $appointmentStartTime = strtotime($appointment['time']);
            $appointmentEndTime = strtotime("+" . $appointment['duration'] . " minutes", $appointmentStartTime);
            if (
                ($currentTime < $appointmentEndTime && $slotEndTime > $appointmentStartTime)
            ) {
                $isAvailable = false;
                break;
            }
        }

        if ($isAvailable && $slotEndTime <= $endTime) {
            $availableTimes[] = date("g:i A", $currentTime);
        }

        $currentTime = strtotime("+30 minutes", $currentTime);
    }

    if (empty($availableTimes)) {
        echo "No available times.";
    } else {
        foreach ($availableTimes as $time) {
            echo "<p>Available Time: $time</p>";
        }
    }

    $conn->close();
}
?>

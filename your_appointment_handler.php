<?php
include("connect.php");

function getDoctorId($conn, $doctorName) {
    $stmt = $conn->prepare("SELECT doctorid FROM Doctor WHERE doctorname = ?");
    $stmt->bind_param("s", $doctorName);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc()['doctorid'] : null;
}

function isHoliday($conn, $date) {
    $stmt = $conn->prepare("SELECT * FROM Holidays WHERE holidaydate = ?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0;
}

function isClinicOpen($conn, $date) {
    $dayOfWeek = date('l', strtotime($date));
    $stmt = $conn->prepare("SELECT status, openingtime, closingtime FROM Schedule WHERE days = ?");
    $stmt->bind_param("s", $dayOfWeek);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['status'] === 'Open' ? $row : null;
    } else {
        return null;
    }
}

function isTimeSlotAvailable($conn, $doctorId, $date, $startTime, $duration) {
    $endTime = date('H:i:s', strtotime($startTime) + ($duration * 60));
    
    $breakStartTime = "13:00:00";
    $breakEndTime = "13:59:00";
    if (($startTime >= $breakStartTime && $startTime < $breakEndTime) ||
        ($endTime > $breakStartTime && $endTime <= $breakEndTime) ||
        ($startTime < $breakStartTime && $endTime > $breakEndTime)) {
        return false;
    }

    $stmt = $conn->prepare("SELECT * FROM Appointment WHERE doctorid = ? AND appdate = ? AND ((apptime <= ? AND DATE_ADD(apptime, INTERVAL duration MINUTE) > ?) OR (apptime < ? AND DATE_ADD(apptime, INTERVAL duration MINUTE) >= ?))");
    $stmt->bind_param("isssss", $doctorId, $date, $startTime, $startTime, $endTime, $endTime);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows == 0;
}

function findNextAvailableSlot($conn, $doctorId, $date, $duration, $openingTime, $closingTime) {
    $time = strtotime($openingTime);
    $endTime = strtotime($closingTime);
    $slotDuration = $duration * 60;

    while ($time + $slotDuration <= $endTime) {
        $nextStartTime = date('H:i:s', $time);
        if (isTimeSlotAvailable($conn, $doctorId, $date, $nextStartTime, $duration)) {
            return $nextStartTime;
        }
        $time += 300;  
    }
    return null;
}

function calculateEndTime($startTime, $duration) {
    return date('H:i:s', strtotime($startTime) + ($duration * 60));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patientid = $_POST['patientid'];
    $clinicName = $_POST['clinicname'];
    $doctorName = $_POST['doctorname'];
    $procedureName = $_POST['procedurename'];
    $appointmentDate = $_POST['appointmentdate'];

    $doctorId = getDoctorId($conn, $doctorName);
    if ($doctorId === null) {
        echo "Invalid doctor name.";
        exit();
    }

    if (isHoliday($conn, $appointmentDate)) {
        echo "The selected date is a holiday. No appointments can be made on this day.";
        exit();
    }

    $clinicSchedule = isClinicOpen($conn, $appointmentDate);
    if (!$clinicSchedule) {
        echo "The clinic is closed on the selected date.";
        exit();
    }

    $procedureStmt = $conn->prepare("SELECT duration FROM Availableprocedures WHERE procedurename = ?");
    $procedureStmt->bind_param("s", $procedureName);
    $procedureStmt->execute();
    $procedureResult = $procedureStmt->get_result();
    if ($procedureResult->num_rows > 0) {
        $procedureDuration = $procedureResult->fetch_assoc()['duration'];
    } else {
        echo "Invalid procedure selected.";
        exit();
    }

    if (isset($_POST['appointmenttime'])) {
        $appointmentTime = $_POST['appointmenttime'];
        $appointmentEndTime = calculateEndTime($appointmentTime, $procedureDuration);

        if (strtotime($appointmentTime) < strtotime($clinicSchedule['openingtime']) || strtotime($appointmentEndTime) > strtotime($clinicSchedule['closingtime'])) {
            echo "The selected time is outside the clinic's working hours.";
            exit();
        }

        if (!isTimeSlotAvailable($conn, $doctorId, $appointmentDate, $appointmentTime, $procedureDuration)) {
            $nextAvailableSlot = findNextAvailableSlot($conn, $doctorId, $appointmentDate, $procedureDuration, $clinicSchedule['openingtime'], $clinicSchedule['closingtime']);
            if ($nextAvailableSlot) {
                echo "The selected time slot is not available. The next available slot is at $nextAvailableSlot.";
            } else {
                echo "The selected time slot is not available and no other slots are available today.";
            }
            exit();
        }

        $getdate = date("Y-m-d");
        $status = 'S';
        $vitalFill = 'No';
        $stmt = $conn->prepare("INSERT INTO Appointment (patientid, doctorid, clinicname, appdate, procedurename, duration, apptime, getdate, status, vitalfill) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sisssissss", $patientid, $doctorId, $clinicName, $appointmentDate, $procedureName, $procedureDuration, $appointmentTime, $getdate, $status, $vitalFill);

        if ($stmt->execute()) {
            echo "Successful Appointment.";
        } else {
            echo "Failed to create appointment. Please try again.";
        }
    }
}

$conn->close();
?>
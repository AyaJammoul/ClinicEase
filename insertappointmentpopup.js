document.getElementById('addAppointmentBtn').addEventListener('click', function() {
    document.getElementById('appointmentModal').style.display = 'block';
});

document.getElementById('appointmentclose').addEventListener('click', function() {
    document.getElementById('appointmentModal').style.display = 'none';
});

window.onclick = function(event) {
    if (event.target == document.getElementById('appointmentModal')) {
        document.getElementById('appointmentModal').style.display = 'none';
    }
}

function updateDoctors() {
    var speciality = document.getElementById('speciality').value;
    if (speciality) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'getDoctors.php?speciality=' + encodeURIComponent(speciality), true);
        xhr.onload = function() {
            if (this.status == 200) {
                console.log("Response from getDoctors.php: ", this.responseText);
                try {
                    var doctors = JSON.parse(this.responseText);
                    var doctorSelect = document.getElementById('doctor-name');
                    doctorSelect.innerHTML = '<option value="">Select Doctor</option>';
                    doctors.forEach(function(doctor) {
                        var option = document.createElement('option');
                        option.value = doctor.name;
                        option.text = doctor.name;
                        doctorSelect.appendChild(option);
                    });
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                }
            } else {
                console.error('Error fetching doctors:', this.status, this.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    } else {
        document.getElementById('doctor-name').innerHTML = '<option value="">Select Doctor</option>';
    }
}

function updateProcedures() {
    var speciality = document.getElementById('speciality').value;
    if (speciality) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'getProcedures.php?speciality=' + encodeURIComponent(speciality), true);
        xhr.onload = function() {
            if (this.status == 200) {
                try {
                    var procedures = JSON.parse(this.responseText);
                    var procedureSelect = document.getElementById('procedure');
                    procedureSelect.innerHTML = '<option value="">Select Procedure</option>';
                    procedures.forEach(function(procedure) {
                        var option = document.createElement('option');
                        option.value = procedure.id;
                        option.text = procedure.name;
                        option.value = procedure.name;
                        option.dataset.duration = procedure.duration; 
                        procedureSelect.appendChild(option);
                    });
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                }
            } else {
                console.error('Error fetching procedures:', this.status, this.statusText);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send();
    } else {
        document.getElementById('procedure').innerHTML = '<option value="">Select Procedure</option>';
    }
}

function updateDuration() {
    var procedureSelect = document.getElementById('procedure');
    var selectedOption = procedureSelect.options[procedureSelect.selectedIndex];
    var duration = selectedOption.dataset.duration; 
    if (duration) {
        document.getElementById('duration').textContent = duration + ' minutes';
    } else {
        document.getElementById('duration').textContent = 'Select a procedure...';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('procedure').addEventListener('change', updateDuration);
});



document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('appointmentForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(form);
        for (const [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }

        fetch('insertAppointment.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data);
            if (data.includes("successfully")) {
                form.reset();
                document.getElementById('appointmentModal').style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error submitting the form. Please try again.');
        });
    });
});
        var checkTimeModal = document.getElementById("checkTimeModal");
        var checkTimeBtn = document.getElementById("checktime");
        var checkTimeClose = document.getElementById("checkTimeClose");
        checkTimeBtn.onclick = function() {
            checkTimeModal.style.display = "block";
        }
        checkTimeClose.onclick = function() {
            checkTimeModal.style.display = "none";
        }
        window.onclick = function(event) {
            if (event.target == checkTimeModal) {
                checkTimeModal.style.display = "none";
            }
        }

        function updateDoctor() {
            var speciality = document.getElementById("checkSpeciality").value;

            if (speciality) {
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "getDoctors.php?speciality=" + encodeURIComponent(speciality), true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var doctors = JSON.parse(xhr.responseText);
                        var doctorSelect = document.getElementById("checkDoctorName");
                        doctorSelect.innerHTML = "<option value=''>Select Doctor</option>";
                        doctors.forEach(function(doctor) {
                            var option = document.createElement("option");
                            option.value = doctor.name;
                            option.textContent = doctor.name;
                            doctorSelect.appendChild(option);
                        });
                    }
                };
                xhr.send();
            } else {
                document.getElementById("checkDoctorName").innerHTML = "<option value=''>Select Doctor</option>";
            }
        }
        document.getElementById("getAvailableTimesBtn").onclick = function() {
            var doctorName = document.getElementById("checkDoctorName").value;
            var appointmentDate = document.getElementById("checkAppointmentDate").value;

            if (doctorName && appointmentDate) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "getAvailableTimes.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.getElementById("availableTimes").innerHTML = xhr.responseText;
                    }
                };
                xhr.send("doctorname=" + encodeURIComponent(doctorName) + "&appointmentdate=" + encodeURIComponent(appointmentDate));
            } else {
                alert("Please select a doctor and date.");
            }
        };
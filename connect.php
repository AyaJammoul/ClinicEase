<?php

$HostName = "localhost";
$HostUser = "id22166182_admin";
$HostPass = "Clinicease@2024";
$DatabaseName = "id22166182_clinicease";
$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

if(!empty($conn))
    {
       echo( "");
    }
    else
    {
        echo( "Connection Error ");
    }
?>
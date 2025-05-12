<?php

$HostName = "localhost";
$HostUser = "root";
$HostPass = "";
$DatabaseName = "clinicease";

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
<?php
    $host="localhost";
    $dbname="intranet";
    $dbuser="root";
    $dbpass="";

    $conn = new PDO('mysql:host='.$host.';dbname='.$dbname, $dbuser, $dbpass);
?>
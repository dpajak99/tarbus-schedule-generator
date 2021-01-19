<?php
function OpenDbConnection()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "root";
    $db = "tarbus3";
    $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

    return $conn;
}

function OpenDbConnection2()
{
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "root";
    $db = "tarbus2";
    $conn = new mysqli($dbhost, $dbuser, $dbpass,$db) or die("Connect failed: %s\n". $conn -> error);

    return $conn;
}

function CloseDbConnection($conn)
{
    $conn -> close();
}


<?php

require 'Connection.php';

$inData = getRequestInfo();

$secrets = readSecrets();
$conn    = new mysqli($secrets['host'], $secrets['username'], $secrets['passwd'], $secrets['dbname']);

deleteContact($conn, $inData);

$conn->close();

function deleteContact($conn, $inData)
{
    $contactID = $inData["id"];

    $userID = $inData["userID"];

    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        $result = $conn->query("DELETE FROM CONTACTS WHERE id='$contactID'");

        if (!$result) {
            returnWithError($conn->error);
        }
    }

    returnWithError("");
}

function getRequestInfo()
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendResultInfoAsJson($obj)
{
    header('Content-type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: Content-Type, origin');
    echo $obj;
}

function returnWithError($err)
{
    $retValue = '{"error":"' . $err . '"}';
    sendResultInfoAsJson($retValue);
}

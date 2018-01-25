<?php

$inData = getRequestInfo();

$firstName    = $inData["firstName"];
$lasttName    = $inData["lastName"];
$phoneNumber  = $inData["phoneNumber"];
$emailAddress = $inData["emailAddress"];
$userID       = $inData["userID"];

$secrets = readSecrets();
$conn    = new mysqli($secrets['host'], $secrets['username'], $secrets['passwd'], $secrets['dbname']);

insertContact($conn, $inData);

$conn->close();

function insertContact($conn, $inData)
{
    if ($conn->connect_error) {
        returnWithError($conn->connect_error);
    } else {
        $result = $conn->query("INSERT INTO Contacts (firstName, lastName, phoneNumber, emailAddress, userID) VALUES ('$firstName', '$lastName', '$phoneNumber', '$emailAddress', '$userID')");

        if (!$result) {
            returnWithError($conn->error);
        } else {
            returnWithError("");
        }
    }
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

/**
 * Reads MySQL database login information through a 'secrets' file
 *
 *  @return array (array containing database login information)
 */
function readSecrets()
{
    $secretsFile = fopen("../secrets", "r");

    while (!feof($secretsFile)) {
        $secretsString = fgets($secretsFile);
    }

    fclose($secretsFile);

    $secretsArray = explode(",", $secretsString);

    $secrets['host']     = $secretsArray[0];
    $secrets['username'] = $secretsArray[1];
    $secrets['passwd']   = $secretsArray[2];
    $secrets['dbname']   = $secretsArray[3];

    return $secrets;
}

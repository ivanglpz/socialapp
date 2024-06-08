<?php
require_once 'controllers/users.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $users = new Users();
    $jsondata = $users->getUsers();
    header('Content-Type: application/json');
    echo json_encode($jsondata);
}
?>

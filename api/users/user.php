<?php
require_once '../../controllers/users.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = $_GET['user_id'];
    $users = new Users();
    $result = $users->getUserById($user_id);
     header('Content-Type: application/json');
    echo json_encode($result);
}

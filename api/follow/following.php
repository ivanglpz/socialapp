<?php
require_once '../../controllers/followers.php';
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $user_id = $_GET['user_id'];
    $users = new Followers();
    $result = $users->GetUserFollowing($user_id);
     header('Content-Type: application/json');
    echo json_encode($result);
}
?>
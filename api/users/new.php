<?php
require_once '../../controllers/users.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents("php://input"), true);
    $name = $data['name'];
    $username = $data['username'];
    $email = $data['email'];
    $image_profile = $data["image_profile"];
    $image_banner = $data["image_banner"];
    $password = $data["password"];
    $users = new Users();
    $result = $users->createNewUser($name,$username,$email,$image_profile,$image_banner,$password);
    echo $result;
}
?>
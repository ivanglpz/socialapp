<?php
require_once '../../controllers/followers.php';
if ($_SERVER["REQUEST_METHOD"] == "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);
    $followed_user_id = $data["followed_user_id"];
    $following_user_id = $data["following_user_id"];

    $follow = new Followers();

    $result = $follow->unFollow($followed_user_id, $following_user_id);

    echo $result;
}

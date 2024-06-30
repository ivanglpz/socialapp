<?php
require_once '../../db/db.php';
require __DIR__ . '/../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

class Followers
{
    private $database;
    private $db;
    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
    }
    public function newFollow($followed_user_id, $following_user_id)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO follows (id, followed_user_id, following_user_id,created_at) VALUES (:id, :followed_user_id, :following_user_id, :created_at)");
            $created_at = date('Y-m-d H:i:s');
            $uuid = Uuid::uuid4()->toString();

            $stmt->bindParam(':id', $uuid);
            $stmt->bindParam(':followed_user_id', $followed_user_id);
            $stmt->bindParam(':following_user_id', $following_user_id);
            $stmt->bindParam(':created_at', $created_at);
            if ($stmt->execute()) {
                return "Usuario seguido exitosamente.";
            } else {
                return "Error al seguir al usuario.";
            }
        } catch (Exception $e) {
            throw new Exception('Caught exception: ' .  $e->getMessage());
        }
    }
    public function unFollow($followed_user_id, $following_user_id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM follows WHERE followed_user_id = :followed_user_id AND following_user_id = :following_user_id");
            $stmt->bindParam(':followed_user_id', $followed_user_id);
            $stmt->bindParam(':following_user_id', $following_user_id);
            if ($stmt->execute()) {
                return "Usuario dejado de seguir exitosamente.";
            } else {
                return "Error al dejar de seguir al usuario.";
            }
        } catch (Exception $e) {
            throw new Exception('Caught exception: ' .  $e->getMessage());
        }
    }
    public function GetUserFollowers($following_user_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT users.name, users.image_profile, users.username, users.user_id FROM `follows` LEFT JOIN users ON users.user_id = followed_user_id WHERE following_user_id = :following_user_id");
            $stmt->bindParam(':following_user_id', $following_user_id);
            $stmt->execute();

            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        } catch (Exception $e) {
            throw new Exception('Caught exception: ' .  $e->getMessage());
        }
    }
    public function GetUserFollowing($followed_user_id)
    {
        try {
            $stmt = $this->db->prepare("SELECT users.name, users.image_profile, users.username, users.user_id FROM `follows` LEFT JOIN users ON users.user_id = following_user_id WHERE followed_user_id = :followed_user_id");
            $stmt->bindParam(':followed_user_id', $followed_user_id);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        } catch (Exception $e) {
            throw new Exception('Caught exception: ' .  $e->getMessage());
        }
    }
}

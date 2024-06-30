<?php
require_once '../../db/db.php';
require __DIR__ . '/../vendor/autoload.php';

use Ramsey\Uuid\Uuid;

class Users
{
    private $database;
    private $db;
    private $sql_user = "SELECT users.user_id, users.name, users.username, users.email, users.image_profile, users.image_banner, users.created_at, users.is_verified FROM users WHERE user_id = :user_id";
    private $sql_create_user = "INSERT INTO users (user_id, name, username, email, image_profile, image_banner, created_at, updated_at, is_verified, password) VALUES (:user_id, :name, :username, :email, :image_profile, :image_banner, :created_at, :updated_at, :is_verified, :password)";
    private $sql_update_user = "UPDATE users SET name = :name WHERE id = :id";
    public function __construct()
    {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
    }
    public function getUserById($user_id)
    {
        try {
            $stmt = $this->db->prepare($this->sql_user);
            $stmt->bindParam(":user_id", $user_id);
            $stmt->execute();

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                $user['is_verified'] = (bool)$user['is_verified'];
                return $user;
            } else {
                return null;
            }
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            return null;
        }
    }
    public function createNewUser($name, $username, $email, $image_profile, $image_banner, $password)
    {
        try {

            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            if ($name == "" || $name == null || empty(trim($name))) {
                throw new Exception("Name is a require field.");
            }
            $created_at = date('Y-m-d H:i:s');
            $uuid = Uuid::uuid4()->toString();
            $is_verified = false;
            $stmt = $this->db->prepare($this->sql_create_user);
            $stmt->bindParam(":user_id", $uuid);
            $stmt->bindParam(":name", $name);
            $stmt->bindParam(":username", $username);
            $stmt->bindParam(":email", $email);
            $stmt->bindParam(":image_profile", $image_profile);
            $stmt->bindParam(":image_banner", $image_banner);
            $stmt->bindParam(":created_at", $created_at);
            $stmt->bindParam(":updated_at", $created_at);
            $stmt->bindParam(":is_verified", $is_verified);
            $stmt->bindParam(":password", $hashed_password);
            if ($stmt->execute()) {
                return "Registro guardado exitosamente.";
            } else {
                return "Error al guardar el registro.";
            }
        } catch (Exception $e) {
            throw new Exception('Caught exception: ' .  $e->getMessage());
        }
    }
    public function updateUserById($id, $name)
    {
        $stmt = $this->db->prepare($this->sql_update_user);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        if ($stmt->execute()) {
            return "Registro actualizado exitosamente.";
        } else {
            throw new Exception("Error al actualizar el registro.");
        }
    }
}

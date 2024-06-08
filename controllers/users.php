<?php
require_once 'db/db.php';
require __DIR__ . '/../vendor/autoload.php'; // Ruta correcta para cargar autoload.php

use Ramsey\Uuid\Uuid;

class Users  {
    private $database;
    private $db;
    private $sql = "SELECT users.user_id, users.name, users.username, users.email, users.image_profile, users.image_banner, users.created_at, users.is_verified FROM users ORDER BY users.created_at DESC;";
    private $sql_create_user = "INSERT INTO users (user_id, name, username, email, image_profile, image_banner, created_at, updated_at, is_verified, password) VALUES (:user_id, :name, :username, :email, :image_profile, :image_banner, :created_at, :updated_at, :is_verified, :password)";
    private $sql_delete_user = "DELETE FROM users WHERE user_id = :user_id";
    private $sql_update_user = "UPDATE users SET name = :name WHERE id = :id";
    public function __construct() {
        $this->database = new Database();
        $this->db = $this->database->getConnection();
    }

    public function getCountUsers() {
        $stmt = $this->db->prepare($this->sql);

        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return count($users);
    }

    public function getUsers () {
        $stmt = $this->db->prepare($this->sql);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $users;
    }
    public function createNewUser($name, $username,$email, $image_profile ,$image_banner,$password){
        $created_at=date('Y-m-d H:i:s');
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
        $stmt->bindParam(":password", $password);
         if ($stmt->execute()) {
            return "Registro guardado exitosamente.";
        } else {
            return "Error al guardar el registro.";
        }
    }
    public function deleteUserById($id){
        $stmt = $this->db->prepare($this->sql_delete_user);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            return "Registro eliminado exitosamente.";
        } else {
            return "Error al eliminar el registro.";
        }
    }
    public function updateUserById($id, $name) {
        $stmt = $this->db->prepare($this->sql_update_user);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":name", $name);
        if ($stmt->execute()) {
            return "Registro actualizado exitosamente.";
        } else {
            return "Error al actualizar el registro.";
        }
    }
}
?>

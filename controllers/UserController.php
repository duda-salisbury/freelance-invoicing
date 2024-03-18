<?php

require_once '../models/User.php';

class UserController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    // Create a new user
    public function registerUser($email, $password) {
        // Perform input validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format";
        }

        // Hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Call the createUser method from the User model to insert the user into the database
        $userId = $this->userModel->createUser($email, $passwordHash);

        if ($userId) {
            return "User registered successfully";
        } else {
            return "Failed to register user";
        }
    }

    // Update a user's password
    public function updatePassword($userId, $newPassword) {
        // Hash the new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Call the updateUserPassword method from the User model to update the user's password in the database
        $success = $this->userModel->updateUserPassword($userId, $newPasswordHash);

        if ($success) {
            return "Password updated successfully";
        } else {
            return "Failed to update password";
        }
    }

    // Delete a user
    public function deleteUser($userId) {
        // Call the deleteUser method from the User model to delete the user from the database
        $success = $this->userModel->deleteUser($userId);

        if ($success) {
            return "User deleted successfully";
        } else {
            return "Failed to delete user";
        }
    }
}

?>

<?php

require_once '../app/models/User.php';

class AuthController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $userModel = new User($this->db);

            $user = $userModel->login($username);

            /*
            |--------------------------------------------------------------------------
            | SIMPLE PASSWORD CHECK
            |--------------------------------------------------------------------------
            */
            if ($user && $password == $user['password']) {

                $_SESSION['user'] = $user['username'];

                header("Location: index.php?action=index");

                exit;

            } else {

                $error = "Invalid Username or Password";

                require '../app/views/auth/login.php';
            }

        } else {

            require '../app/views/auth/login.php';
        }
    }

    public function logout()
    {
        session_destroy();

        header("Location: index.php?action=login");

        exit;
    }
}
?>

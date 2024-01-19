<?php

namespace App\Controllers;

use Framework\Database;
use Framework\Validation;
use Framework\Session;

class UserController
{
    protected $db;

    public function __construct()
    {
        $config = require basePath('config/db.php');

        $this->db = new Database($config);
    }

    public function login()
    {
        loadView('users/loginView');
    }

    public function register()
    {
        loadView('users/registerView');
    }

    public function store()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password_confirmation'];

        $errors = [];

        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if (!Validation::string($name, 2, 50)) {
            $errors['name'] = 'Name must be between 2 and 255 characters';
        }

        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be atleast 6 characters';
        }

        if (!Validation::match($password, $passwordConfirm)) {
            $errors['password_confirmation'] = 'Password do not match';
        }

        if (!empty($errors)) {
            loadView('users/registerView', [
                'errors' => $errors,
                'user' => [
                    'name' => $name,
                    'email' => $email,
                    'city' => $city,
                    'state' => $state,
                ]
            ]);
            exit;
        }

        $params = [
            'email' => $email,
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if ($user) {
            $errors['email'] = 'That email address is already exists';
            loadView('users/registerView', ['errors' => $errors]);
            exit;
        }

        $params = [
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
            'password' => password_hash($password, PASSWORD_DEFAULT),
        ];

        $this->db->query('INSERT INTO users (name, email, city, state, password) 
        VALUES (:name, :email, :city, :state, :password)', $params);

        // Get new user id
        $userId = $this->db->connection->lastInsertId();

        Session::set('user', [
            'id' => $userId,
            'name' => $name,
            'email' => $email,
            'city' => $city,
            'state' => $state,
        ]);

        redirect('/');
    }

    public function authenticate()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $errors = [];

        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email';
        }

        if (!Validation::string($password, 6, 50)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!empty($errors)) {
            loadView('users/loginView', [
                'errors' => $errors,
            ]);
            exit;
        }

        $params = [
            'email' => $email
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();

        if (!$user) {
            $errors['email'] = 'Incorrect Credentials';
            loadView('users/loginView', [
                'errors' => $errors
            ]);
            exit;
        }

        if (!password_verify($password, $user->password)) {
            $errors['email'] = 'Incorrect Credentials';
            loadView('users/loginView', [
                'errors' => $errors
            ]);
            exit;
        }

        Session::set('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'city' => $user->city,
            'state' => $user->state,
        ]);

        redirect('/');
    }

    public function logout()
    {
        Session::clearAll('user');

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }
}

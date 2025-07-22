<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['token_emp']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function login($user_id, $token_emp, $username, $name) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['token_emp'] = $token_emp;
    $_SESSION['username'] = $username;
    $_SESSION['name'] = $name;
    $_SESSION['login_time'] = time();
}

function logout() {
    session_destroy();
    header('Location: login.php');
    exit;
}

function getCurrentUser() {
    if (!isLoggedIn()) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'token_emp' => $_SESSION['token_emp'],
        'username' => $_SESSION['username'],
        'name' => $_SESSION['name']
    ];
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}
?>


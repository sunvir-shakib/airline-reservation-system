<?php
session_start();
include 'admin/db_connect.php';

if (($_GET['do'] ?? '') === 'login') {
    $email = $_POST['email'] ?? '';
    $pass  = $_POST['password'] ?? '';

    $q = $conn->query("SELECT * FROM customer_users WHERE email='$email' LIMIT 1");
    if ($q && $q->num_rows) {
        $u = $q->fetch_assoc();
        if (password_verify($pass, $u['password'])) {
            $_SESSION['customer_id'] = $u['id'];
            $_SESSION['customer_name'] = $u['name'];
            header('Location: index.php?page=my_bookings');
            exit;
        }
    }
    header('Location: index.php?page=login&err=1');
    exit;
}

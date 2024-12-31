<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new BazaKP();
    $baza->sendFriendRequest($_SESSION['id'], $_GET['id_to']);
    echo json_encode([]);
?>
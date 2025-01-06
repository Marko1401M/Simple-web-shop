<?php

    session_start();
    if(!isset($_SESSION["id"])){
        session_destroy();
        header('login.php');
        exit();
    }

    require_once 'db/baza.php';
    $baza = new Baza();

    $users = $baza->findUserByUsername($_GET['name']);

    echo json_encode($users);
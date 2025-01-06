<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new Baza();
    $baza->deleteOglas($_GET['id']);
    echo json_encode($baza->getOglasiById($_SESSION['id']));
?>
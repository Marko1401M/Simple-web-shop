<?php 
    session_start();
    if(!isset($_SESSION["id"])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'baza.php';
    $baza = new BazaKP();
    $oglasi = $baza->pretrazi($_GET['naslov']);
    echo json_encode($oglasi);  
?>
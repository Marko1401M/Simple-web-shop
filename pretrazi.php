<?php 
    session_start();
    if(!isset($_SESSION["id"])){
        session_destroy();
        header(header: 'Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new Baza();
    $oglasi = $baza->pretrazi($_GET['naslov']);
    echo json_encode($oglasi);  
?>
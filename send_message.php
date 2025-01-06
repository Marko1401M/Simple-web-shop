<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new Baza();

    $baza->sendMessage($_GET['chat_id'], $_SESSION['id'],$_GET['reciever_id'], $_GET['text']);
    $poruke = $baza->getMessages($_GET['chat_id']);
    echo json_encode($poruke);
?>
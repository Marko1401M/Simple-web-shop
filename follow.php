<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'baza.php';    
    $baza = new BazaKP();
    if(!isset($_GET['unfollow'])){
        $baza->followOglas($_GET['id_oglasa'],$_SESSION['id']);
        echo json_encode([1]);
    }
    else{
        $baza->unfollowOglas($_GET['id_oglasa'],$_SESSION['id']);
        echo json_encode([-1]);
    }

?>
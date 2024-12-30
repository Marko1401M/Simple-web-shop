<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';  
    $baza = new BazaKP();
    if(!isset($_GET['unfollow'])){
        $baza->followOglas($_GET['id_oglasa'],$_SESSION['id']);
    }
    else{
        $baza->unfollowOglas($_GET['id_oglasa'],$_SESSION['id']);
    }
    $oglasi = $baza->getPraceniOglasi($_SESSION['id']);
    $ret = [];
    $i = 0;
    foreach($oglasi as $oglas){
        $ret[$i] = $baza->getOglas($oglas['id_oglasa']);
        $i += 1;  
    }
    echo json_encode($ret);
?>
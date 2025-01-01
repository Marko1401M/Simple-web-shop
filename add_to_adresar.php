<?php 

    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new BazaKP();
    if(!isset($_GET['izbaci'])){ 
        $baza->dodajUAdresar($_SESSION['id'], $_GET['id']);
        echo json_encode([]);
    }
    else{ 
        $baza->izbaciIzAdresara($_SESSION['id'], $_GET['id']);
        $adresar = $baza->getAdresar($_SESSION['id']);
        for($i = 0; $i < count($adresar); $i++){
            $adresar[$i]['username'] = $baza->getUserById($adresar[$i]['id_seller'])['username'];
        }
        echo json_encode($adresar);
    }

    
?>
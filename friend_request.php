<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new Baza();
    if(isset($_GET['prihvati'])){
        $baza->acceptFriendRequest($_GET['id_from'], $_SESSION['id']);
        $req = $baza->getFriendRequests(id: $_SESSION['id']);
        for($i = 0; $i < count($req); $i++){
            $req[$i]['username'] = ($baza->getUserById($req[$i]['id_from']))['username'];
        }
        echo json_encode($req);
    }
    else if(isset($_GET['odbij'])){
        $req = $baza->getFriendRequests($_SESSION['id']);
        for($i = 0; $i < count($req); $i++){
            $req[$i]['username'] = ($baza->getUserById($req[$i]['id_from']))['username'];
        }
        echo json_encode($req);
    }
    else {
        $baza->sendFriendRequest($_SESSION['id'], id_to: $_GET['id_to']);
        echo json_encode([]);
    }
?>
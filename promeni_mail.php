<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new Baza();
    $kategorije = $baza->getKategorije();
    if(isset($_POST['mail'])){
        $baza->updateMail($_SESSION['id'], $_POST['mail']);
        header('Location: moj_profil.php');
        exit();
    }
?>
<link rel="stylesheet" href="assets/style.css">

<div id="dodajOglas">
    <form id="forma" method="POST">
        Unesite mail:<br>
        <input name="mail" type="mail"><br><br>
        <input type="button" onclick="promeniMail()" value="Promeni">
    </form>
    
</div>

<script>
    function promeniMail(){
        document.getElementById('forma').submit();
    }
</script>
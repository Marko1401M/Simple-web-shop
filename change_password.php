<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new BazaKP();
    $kategorije = $baza->getKategorije();
    if(isset ($_POST['password'])){
        $password = sha1($_POST['password']);
        $user = $baza->getUserById($_SESSION['id']);
        $new = $baza->login($user['username'], $password);
        if($new){
            $baza->updatePassword($_SESSION['id'], sha1($_POST['password-new']));
            header('Location: login.php');
            exit();
        }
        else {
            echo '<p style="color:red;">Sifra ne odgovara vasem nalogu!;</p>';
        }
    }
?>
<link rel="stylesheet" href="style.css">

<div id="dodajOglas">
    <form id="forma" method="POST">
        Unesi trenutnu sifru:<br>
        <input id="password" name="password" type="password"><br>
        Unesi novu sifru:<br>
        <input id="password-new" name="password-new"  type="password"><br>
        Potvrdi novu sifru:<br>
        <input id="password-new-conf"  name="password-new-conf"  type="password"><br><br>
        <input onclick="promeniSifru()" type="button" value="Promeni sifru">
    </form>
</div>

<script>
    function promeniSifru(){
        if(document.getElementById('password-new').value != document.getElementById('password-new-conf').value){
            alert('Sifre se ne poklapaju');
            return;
        }
        document.getElementById('forma').submit();
    }
</script>
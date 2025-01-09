<?php
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once './db/baza.php';
    $baza = new Baza();
    $kategorije = $baza->getKategorije();
    $oglas = $baza->getOglas($_GET['id']);
    if(isset($_POST['izmeni'])){
        $target_file = "images/".basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$target_file);
        $baza->updateOglas($_POST['id'], $_POST['naslov'], $_POST['tekst'], $_POST['kategorija-sel'], $target_file);
        header('Location: moji_oglasi.php');
        exit();
    }
?>

<link rel="stylesheet" href="assets/style.css">
<div id="dodajOglas">
    <?php if(!isset($_POST['kat-sel'])){ ?>
        <form id="dodajOglasForma" method="POST" enctype="multipart/form-data">
            <select id="kat-sel" name="kategorija-sel">
                <option value="-1">Odaberi Kategoriju</option>
                <?php foreach($kategorije as $kategorija){ ?>
                    <option value="<?php echo $kategorija['id']; ?>"><?php echo $kategorija['naziv'] ?></option>
                <?php } ?>
            </select><br>
            Unesi naslov oglasa:<br> <input value="<?php echo $oglas['naslov'] ?>" id="naslov" name="naslov" type="text" placeholder="naslov"><br>
            Unesi path slike:<br><input type="file" name="fileToUpload" id="fileToUpload"><br>
            Unesite tekst oglasa:<br><textarea id="tekst" name="tekst" type="text"><?php echo $oglas['tekst'] ?></textarea><br>
            <input onclick='proveriOglas()' type="button" value="Izmeni Oglas">
            <input type="hidden" name="izmeni">
            <input type="hidden" name="id" value="<?php echo $oglas['id']; ?>">
        </form>
    <?php }else{ ?>
        <h1>Oglas je uspesno dodat!</h1>
        <a href="index.php">nazad</a>
    <?php } ?>
    
</div>

<div>
    
</div>

<script>
    function proveriOglas(){
        document.getElementById('dodajOglasForma').submit();
    }
</script>
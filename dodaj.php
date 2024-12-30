<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
    }
    require_once 'db/baza.php';
    $baza = new BazaKP();
    $kategorije = $baza->getKategorije();
    if(isset($_POST['naslov'])){
        $target_file = "images/".basename($_FILES["fileToUpload"]["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$target_file);
        $baza->dodajOglas($_SESSION['id'],$_POST['naslov'],$_POST['tekst'],$_POST['kategorija-sel'],$target_file);
        header('Location: index.php');
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
            Unesi naslov oglasa:<br> <input id="naslov" name="naslov" type="text" placeholder="naslov"><br>
            Unesi path slike:<br><input type="file" name="fileToUpload" id="fileToUpload"><br>
            Unesite tekst oglasa:<br><textarea id="tekst" name="tekst" type="text"></textarea><br>
            <input onclick='proveriOglas()' type="button" value="Dodaj Oglas">
            
        </form>
    <?php }else{ ?>
        <h1>Oglas je uspesno dodat!</h1>
        <a href="index.php">nazad</a>
    <?php } ?>
    
</div>

<script>
    function proveriOglas(){
        let naslov = document.getElementById('naslov').value;
        let tekst = document.getElementById('tekst').value;
        let kat = document.getElementById('kat-sel').value;
        if(parseInt(kat) == -1){
            alert('Nisi odabrao kategoriju!!!');
            return;
        }
        if(tekst == ""){
            alert('Morate neki tekst uneti!');
            return;
        }
        if(naslov == ""){
            alert("Morate neki naslov uneti!");
            return;
        }
        let forma = document.getElementById('dodajOglasForma');
        forma.submit();
        
    }
</script>
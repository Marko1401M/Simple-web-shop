<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
    }
    require_once 'baza.php';
    $baza = new BazaKP();
    $kategorije = $baza->getKategorije();
    if(isset($_POST['naslov'])){
        $baza->dodajOglas($_SESSION['id'],$_POST['naslov'],$_POST['tekst'],$_POST['kategorija-sel'],$_POST['path']);
        header('Location: index.php');
        exit();
    }
?>
<link rel="stylesheet" href="style.css">
<div id="dodajOglas">
    <?php if(!isset($_POST['kat-sel'])){ ?>
        <form id="dodajOglasForma" method="POST">
            <select id="kat-sel" name="kategorija-sel">
                <option value="-1">Odaberi Kategoriju</option>
                <?php foreach($kategorije as $kategorija){ ?>
                    <option value="<?php echo $kategorija['id']; ?>"><?php echo $kategorija['naziv'] ?></option>
                <?php } ?>
            </select><br>
            Unesi naslov oglasa:<br> <input id="naslov" name="naslov" type="text" placeholder="naslov"><br>
            Unesi path slike:<br><input type="text" name="path" id="path"><br>
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
        let path = document.getElementById('path').value;
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
        if(path == ""){
            alert("morate uneti neki path do slike!");
            return;
        }
        let forma = document.getElementById('dodajOglasForma');
        forma.submit();
        
    }
</script>
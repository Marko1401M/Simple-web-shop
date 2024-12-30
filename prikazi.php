<link rel="stylesheet" href="assets/style.css">

<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new BazaKP();
    $oglas = $baza->getOglas($_GET['id']);
    $kategorije = $baza->getKategorije();
    $user = $baza->getUserById($_SESSION['id']);
    $autor = $baza->getUserById($oglas['id_korisnika']);
?>


<div id="cnt">
<div id="leviMeni">
    <a id="logOut" href="index.php?Logout">LogOut</a>

    <h3>Dobrodosao <span style="color:blue;"><?php echo $user['username']; ?></span>!</h3>
    <div id="profil">
        <ul>  
            <li><a href="moj_profil.php">Moj profil</a></li>
            <li><a href="moji_oglasi.php">Moji oglasi</a></li>
            <li><a href="praceni_oglasi.php">Oglasi koje pratim</a></li>
            <li><a>Prijatelji</a></li>
            <li><a>Poruke</a></li>
            <li><a>Adresar</a></li>
        </ul>
    </div>
    <div id="kategorije">
        <?php foreach($kategorije as $kategorija){ ?>
            <a><?php echo $kategorija['naziv']; ?></a>
        <?php } ?>
    </div>
</div>

<div id ='sredina'>
    <div id="prikaz-oglasa">
        <img id="slika-oglasa" src="<?php echo $oglas['path_slike'] ?>">
        <h2><?php echo $oglas['naslov']; ?></h2>
        <h3>Oglas postavio: <?php echo $autor['username']; ?></h3>
        <?php if($autor['id'] != $_SESSION['id']){ ?><a onclick="posaljiPoruku()" id="snd-msg">Posalji poruku</a><?php } ?>
        <div id="flw-div">
        <?php if($baza->proveriPracenje($oglas['id'], $_SESSION['id'])){ ?>
            <a onclick="otpratiOglas(<?php echo $oglas['id'] ?>, <?php echo $_SESSION['id']; ?>)" id="flwd-oglas">Oglas je pracen</a><br>
        <?php }else{ ?>
        <a onclick="zapratiOglas(<?php echo $oglas['id'] ?>,<?php echo $_SESSION['id']; ?>)" id="flw-oglas">Zaprati oglas</a>   
        <?php } ?> 
        </div>
        <p><?php echo $oglas['tekst'] ?></p>
    </div>
</div>

<div id="desno">    
    <a href="dodaj.php">Dodaj Oglas</a><br><br><br>
    <a href="index.php">Pocetna</a>
</div>

</div>

<script>
    function zapratiOglas(id_oglasa, id_korisnika){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                console.log(this.responseText);
                let res = JSON.parse(this.responseText);
                if(res[0] == '1'){
                    document.getElementById('flw-div').innerHTML = '<a onclick= "otpratiOglas(<?php echo $oglas['id']; ?>, <?php echo $_SESSION['id']; ?>)" id="flwd-oglas">Oglas je pracen</a>';
                }
            }
        }
        xhttp.open('GET','follow.php?id_oglasa='+id_oglasa+'&id_korisnika=' + id_korisnika,true);
        xhttp.send();
    }
    function otpratiOglas(id_oglasa, id_korisnika){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                console.log(this.responseText);
                let res = JSON.parse(this.responseText);
                if(res[0] == '-1'){
                    document.getElementById('flw-div').innerHTML = '<a onclick= "zapratiOglas(<?php echo $oglas['id']; ?>, <?php echo $_SESSION['id']; ?>)" id="flw-oglas">Zaprati oglas</a>';
                }
            }
        }
        xhttp.open('GET','follow.php?unfollow=1&id_oglasa='+id_oglasa+'&id_korisnika=' + id_korisnika,true);
        xhttp.send();
    }
</script>
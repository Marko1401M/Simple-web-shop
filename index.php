
<head>
    <link rel="stylesheet" href="assets/style.css">
</head>
<?php 
    require_once 'db/baza.php';
    $baza = new Baza();
    session_start();
    if(!isset($_SESSION['id'])){
        header('Location: login.php');
        exit();
    }
    if(isset($_GET['Logout'])){
        setcookie('password',"",time() - 1);
        session_destroy();
        session_unset();
        header(header: 'Location: login.php');
        exit();
    }

    $user = $baza->getUserById($_SESSION['id']);
    $kategorije = $baza->getKategorije();
    $userInfo = $baza->getUserInfo($_SESSION['id']);
    $oglasi = $baza->getOglasi();//Naravno ovo nije dobra ideja ako ima preveliki broj oglasa, ali obzirom da je ovo sam primer onda neka ga
?>
<div id="cnt">

<div id="leviMeni">
    <a id="logOut" href="index.php?Logout">LogOut</a>
    <h3>Dobrodosao <span style="color:blue;"><?php echo $user['username']; ?></span>!</h3>
    <div id="profil">
        <ul>  
            <li><a href="moj_profil.php">Moj profil</a></li>
            <li><a href="moji_oglasi.php" >Moji oglasi</a></li>
            <li><a href="praceni_oglasi.php">Oglasi koje pratim</a></li>
            <li><a href="prijatelji.php">Prijatelji</a></li>
            <li><a href="poruke.php">Poruke</a></li>
            <li><a href="adresar.php">Adresar</a></li>
        </ul>
    </div>
    <div id="kategorije">
        <?php foreach($kategorije as $kategorija){ ?>
            <a><?php echo $kategorija['naziv']; ?></a>
        <?php } ?>
    </div>
    <div style="border-top:1px solid blue;margin-top:5px;margin-bottom:5px;padding:5px;" id="korisnici">
        <h4 style="margin-bottom:0">Pretrazi korisnike: <input id="src-btn-usr" onclick="pretraziKorisnike()" type="button" value="Pretrazi">    </h4>
        <input id='search-korisnika' type="text">
        <div id="prikaz-korisnika">

        </div>
        
    </div>
</div>

<div id ='sredina'>
<input style="border:1px solid blue;width:250px;height:40px;font-size:35px;margin-bottom:10px" type="text" id="search" onkeyup="search()"><br>
    <div id="oglasi">
        <?php foreach($oglasi as $oglas){ ?>
            <div onclick="prikaziOglas(<?php echo $oglas['id']; ?>)" id="oglas">
                <img src="<?php echo $oglas['path_slike']; ?>">
                <h3><?php echo $oglas['naslov']; ?></h3>
                <p><?php echo $oglas['tekst']; ?></p>
            </div>
        <?php } ?>
    </div>
</div>

<div id="desno">
    <a href="dodaj.php">Dodaj Oglas</a>
</div>

</div>


<script src="assets/script.js">
    
</script>
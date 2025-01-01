<head>
<link rel="stylesheet" href="assets/style.css">
</head>
<?php 
    require_once 'db/baza.php';
    $baza = new BazaKP();
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
    $oglasi = $baza->getOglasiById($_SESSION['id']);
?>
<div id="cnt">
<div id="leviMeni">
    <a id="logOut" href="index.php?Logout">LogOut</a>

    <h3>Dobrodosao <span style="color:blue;"><?php echo $user['username']; ?></span>!</h3>
    <div id="profil">
        <ul>  
            <li><a style="color:darkblue;">Moj profil</a></li>
            <li><a href="moji_oglasi.php" >Moji oglasi</a></li>
            <li><a href="praceni_oglasi.php">Oglasi koje pratim</a></li>
            <li><a href="prijatelji.php">Prijatelji</a></li>
            <li><a>Poruke</a></li>
            <li><a>Adresar</a></li>
        </ul>
    </div>
    <div id="kategorije">
        <?php foreach($kategorije as $kategorija){ ?>
            <a onclick="prikaziKategoriju(<?php echo $kategorija['id']; ?>)"><?php echo $kategorija['naziv']; ?></a>
        <?php } ?>
    </div>
</div>

<div id ='sredina'>
    <div id="moj-profil">
        <div id="info-profil">
            <h1>Moj profil</h1>
            <h4>Username:<span> <?php echo $user['username']; ?></span></h4>
            <h4>Ime:<span><?php if($userInfo) echo $userInfo['ime']; else { ?>  Pera  <?php } ?></span></h4>
            <h4>Prezime:<span><?php if($userInfo) echo $userInfo['prezime']; else { ?> Peric <?php } ?></span></h4>
            <h4>Email: <span><?php echo $baza->getMail($_SESSION['id']) ?></span></h4>
            <h4>Broj oglasa: <span><?php echo count($oglasi) ?></span></h4>
            <h4 id="cng-pw" onclick="promeniSifru(<?php echo $_SESSION['id']; ?>)">Promeni sifru</h4>
            <h4 id="cng-mail" onclick="promeniMail(<?php echo $_SESSION['id']; ?>)">Promeni mail</h4>
        </div>
        
    </div>
    
</div>

<div id="desno">
    <a href="dodaj.php">Dodaj Oglas</a><br><br><br>
    <a href="index.php">Pocetna</a>
</div>

</div>

<script>
    function prikaziOglas(id){
        window.location="prikazi.php?id="+id;
    }
    function deleteOglas(id){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                let oglasi = JSON.parse(this.responseText);
                prikaziOglase(oglasi);
            }
        }
        xhttp.open('GET','obrisi_oglas.php?id=' + id, true);
        xhttp.send();
    }
    function promeniSifru(){
        window.location="change_password.php";
    }
    function promeniMail(){
        window.location = "promeni_mail.php";
    }
</script>
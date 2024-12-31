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

    $user = $baza->getUserById($_GET['id']);
    $kategorije = $baza->getKategorije();
    $userInfo = $baza->getUserInfo($_GET['id']);
    $oglasi = $baza->getOglasiById($_GET['id']);
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
            <li><a>Prijatelji</a></li>
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
            <h1>Prikaz korisnika</h1>
            <h4>Username:<span> <?php echo $user['username']; ?></span></h4>
            <h4>Ime:<span><?php if($userInfo) echo $userInfo['ime']; else { ?>  Pera  <?php } ?></span></h4>
            <h4>Prezime:<span><?php if($userInfo) echo $userInfo['prezime']; else { ?> Peric <?php } ?></span></h4>
            <h4>Email: <span><?php echo $baza->getMail($_SESSION['id']) ?></span></h4>
            <h4>Broj oglasa: <span><?php echo count($oglasi) ?></span></h4>
            <div id="prikaz-btns">
            <?php if($baza->checkFriend($_SESSION['id'], $_GET['id'])){ ?>
                <h4 id="snd-frnd-req" onclick="removeFriend(<?php echo $_SESSION['id'] ?>, <?php echo $_GET['id'] ?>)">Ukloni prijatelja</h4>
                <h4>Posalji Poruku</h4>
            <?php } else{ ?>
                <?php if($baza->checkFriendRequest($_SESSION['id'], $_GET['id'])){ ?>
                    <h4 id="snd-frnd-req" >Zahtev je poslat!</h4>
                <?php } else{ ?>
                    <h4 id="snd-frnd-req" onclick="friendRequest(<?php echo $_GET['id'] ?>)">Dodaj prijatelja</h4>
                <?php } ?>
                
            <?php } ?>
            </div>
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
    function friendRequest(id_to){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                console.log(this.response);
            }
        }
        console.log(id_to);
        xhttp.open('GET','friend_request.php?id_to='+id_to, true );
        xhttp.send();
    }
    function checkButton(val){
        if(val == 1){
            document.getElementById('prikaz-btns');
        }
    }
</script>
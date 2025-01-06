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

    $user = $baza->getUserById($_GET['id']);
    if($_SESSION['id'] == $user['id']){
        header('Location: moj_profil.php');
        exit();
    }
    $kategorije = $baza->getKategorije();
    $userInfo = $baza->getUserInfo($_GET['id']);
    $oglasi = $baza->getOglasiById($_GET['id']);
?>
<div id="cnt">
<div id="leviMeni">
    <a id="logOut" href="index.php?Logout">LogOut</a>

    <h3>Dobrodosao <span style="color:blue;"><?php echo $baza->getUserById($_SESSION['id'])['username']; ?></span>!</h3>
    <div id="profil">
        <ul>  
            <li><a style="color:darkblue;">Moj profil</a></li>
            <li><a href="moji_oglasi.php" >Moji oglasi</a></li>
            <li><a href="praceni_oglasi.php">Oglasi koje pratim</a></li>
            <li><a href="prijatelji.php">Prijatelji</a></li>
            <li><a href="poruke.php">Poruke</a></li>
            <li><a href="adresar.php">Adresar</a></li>
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
            <h4>Email: <span><?php echo $baza->getMail($user['id']) ?></span></h4>
            <h4>Broj oglasa: <span><?php echo count($oglasi) ?></span></h4>
            <h4 onclick="posaljiPoruku(<?php echo $_GET['id']; ?>)">Posalji Poruku</h4>
            <div id="prikaz-btns">
            <?php if($_SESSION['id'] != $user['id']){ ?>
            <?php if($baza->checkFriend($_SESSION['id'], $_GET['id'])){ ?>
                <h4 style="margin-left:10px;" id="snd-frnd-req" onclick="removeFriend(<?php echo $_SESSION['id'] ?>, <?php echo $_GET['id'] ?>)">Ukloni prijatelja</h4>

            <?php } else{ ?>
                <?php if($baza->checkFriendRequest($_SESSION['id'], $_GET['id'])){ ?>
                    <h4 style="margin-left:10px;" id="snd-frnd-req" >Zahtev je poslat!</h4>
                <?php } else{ ?>
                    <h4 style="margin-left:10px;"  id="snd-frnd-req" onclick="friendRequest(<?php echo $_GET['id'] ?>)">Dodaj prijatelja</h4>
                <?php } ?>
                
            <?php } ?>
            <?php } ?>
            </div>
            <?php if(!$baza->checkAdresar($_SESSION['id'], $_GET['id'])){ ?>
                <button class="adr" id="add-to-adr" onclick="dodajUAdresar(<?php echo $_GET['id']; ?>)">
                Dodaj u Adresar
                </button>
            <?php } ?>
            <h4 onclick="prikaziOglase(<?php echo $_GET['id']; ?>)">Prikazi oglase</h4>
        </div>
        
    </div>
    
</div>

<div id="desno">
    <a href="dodaj.php">Dodaj Oglas</a><br><br><br>
    <a href="index.php">Pocetna</a>
</div>

</div>

<script>
    function prikaziOglase(id){
        window.location = "prikazi_oglase_korisnika.php?id=" + id;
    }
    function posaljiPoruku(id){
        window.location = "chat.php?id=" + id;
    }
    function friendRequest(id_to){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                console.log(this.response);
                checkButton(1);
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
    function dodajUAdresar(id){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                document.getElementById('add-to-adr').style.display = 'none';
            }
        }
        xhttp.open('GET','add_to_adresar.php?id=' + id, true);
        xhttp.send();
    }
</script>
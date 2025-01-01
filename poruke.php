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
    $kategorije = $baza->getKategorije();
    $user = $baza->getUserById($_SESSION['id']);
    $chats = $baza->getChats($_SESSION['id']);
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
            <li><a href="prijatelji.php">Prijatelji</a></li>
            <li><a style="color:darkblue">Poruke</a></li>
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
    <div id="prikaz-chatova">
        <input id='search-korisnika' type="text">
        <button onclick="pretraziKorisnike()">Pretrazi</button>
        <div id="prikaz-korisnika">

        </div>
        <ul>
            <?php foreach($chats as $chat){ ?>
            <li onclick="otvoriChat(<?php if($chat['sender_id'] != $_SESSION['id']) echo $chat['sender_id']; else echo $chat['reciever_id']; ?>)">
                <?php if($chat['sender_id'] != $_SESSION['id']) echo $baza->getUserById($chat['sender_id'])['username']; else echo $baza->getUserById($chat['reciever_id'])['username']; ?>
            </li>
            <?php } ?>
        </ul>
       
    </div>
</div>

<div id="desno">    
    <a href="dodaj.php">Dodaj Oglas</a><br><br><br>
    <a href="index.php">Pocetna</a>
</div>

</div>

<script>
    function prikaziKorisnika(id){
        window.location = "prikaz_korisnika.php?id=" + id;
    }
    function pretraziKorisnike(){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                let users = JSON.parse(this.responseText);
                console.log(users);
                prikaziKorisnike(users);
            }
        }
        xhttp.open('GET','pretrazi_korisnike.php?name='+ document.getElementById('search-korisnika').value,true);
        xhttp.send();
    }
    function prikaziKorisnike(users){
        let pom = '<ul id ="lista-korisnika" >';
        users.forEach(element => {
            pom += '<li><a onclick="otvoriChat('+element.id+')">';
            pom += element.username;
            pom += '</a></li>';
        });
        pom += '</ul>';
        document.getElementById('prikaz-korisnika').innerHTML = pom;
    }
    function otvoriChat(id){
        window.location = "chat.php?id=" + id;
    }
</script>
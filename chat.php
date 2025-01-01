<?php 
    session_start();
    if(!isset($_SESSION['id'])){
        session_destroy();
        header('Location: login.php');
        exit();
    }
    require_once 'db/baza.php';
    $baza = new BazaKP();
    $chat = $baza->getChat($_SESSION['id'], $_GET['id']);
    $kategorije = $baza->getKategorije();
    if(!$chat){
        $baza->createChat($_SESSION['id'], $_GET['id']);
        $chat = $baza->getChat($_SESSION['id'], $_GET['id']);
    }
    $other = $baza->getUserInfo($_GET['id']);
    $poruke = $baza->getMessages($chat['id']);
?>

<link rel="stylesheet" href="assets/style.css">

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
    <div id="user-display">
        <h3 style="border-bottom:1px solid blue"><span><?php if($other) echo $other['ime']." ".$other['prezime']; ?></span></h3>
        <div id="poruke" style="height:500px">
            <?php foreach($poruke as $poruka){ ?>
                <div class="<?php if($poruka['sender_id'] == $_SESSION['id']) echo 'sender-msg'; else echo 'reciever-msg' ?>"><?php echo $poruka['text'] ?></div>
            <?php } ?>
        </div>
        <input style="width:1000px;border:1px solid blue;margin:10px;height:50px;" id="text-poruke" type="text" style="width:500px";><input type="button" value="Send" onclick="sendMessage(<?php echo $_GET['id'];?>, <?php echo $chat['id']; ?>)">
    </div>
</div>

<div id="desno">
    <a href="dodaj.php">Dodaj Oglas</a><br><br><br>
    <a href="index.php">Pocetna</a>
</div>

</div>

<script>
    const poruke = document.getElementById('poruke');
    poruke.scrollTop = poruke.scrollHeight;
    function sendMessage(reciever_id, chat_id){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                let poruke = JSON.parse(this.responseText);
                prikaziPoruke(poruke);
            }
        }
        xhttp.open('GET','send_message.php?reciever_id=' + reciever_id + '&chat_id=' + chat_id +'&text=' + document.getElementById('text-poruke').value, true);
        xhttp.send();
    }
    function prikaziPoruke(poruke){
        let pom = '';
        poruke.forEach(element => {
            if(element.sender_id == <?php echo $_SESSION['id'] ?>) pom += '<div class="sender-msg">' + element.text + '</div>'
            else pom += '<div class="reciever-msg">' + element.text + '</div>'
        });
        document.getElementById('poruke').innerHTML = pom;
        scrollToBottom();
    }
    function scrollToBottom(){
        poruke.scrollTop = poruke.scrollHeight;
    }
</script>
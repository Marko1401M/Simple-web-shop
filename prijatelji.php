
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
    $oglasi = $baza->getOglasi();//Naravno ovo nije dobra ideja ako ima preveliki broj oglasa, ali obzirom da je ovo sam primer onda neka ga
    $requests = $baza->getFriendRequests($_SESSION['id']);
    $friends = $baza->getFriends($_SESSION['id']);
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
            <li><a style="color:darkblue;">Prijatelji</a></li>
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
    <div id="oglasi">
        <ul id = 'lista-zahteva'>
        <?php foreach($requests as $req){ $fr = $baza->getUserById($req['id_from']); if($req['status'] == 'waiting'){ ?>
            <li>
                <a onclick="prikaziKorisnika(<?php echo $req['id_from']; ?>)"><?php echo $fr['username']; ?></a>
                <button onclick="accept(<?php echo $req['id_from']?>, <?php echo $_SESSION['id']; ?>)">Prihvati</button>
                <button onclick="decline(<?php echo $req['id_from']?>, <?php echo $_SESSION['id']; ?>)">Odbij</button>
            </li>
        <?php }} ?>
        </ul>
        <h4 style="margin-left:10px;">Friend list:</h4>
        <ul id="prikaz-prijatelja">
            <?php foreach($friends as $friend){ ?>
                <li>
                    <a class="frnd" onclick="prikaziKorisnika(<?php if($friend['id_user1'] != $_SESSION['id']) echo $friend['id_user1']; else echo $friend['id_user2'] ?>)">
                        <?php if($friend['id_user1'] != $_SESSION['id']) echo $baza->getUserById($friend['id_user1'])['username']; else echo $baza->getUserById($friend['id_user2'])['username']; ?>
                    </a>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>

<div id="desno">
    <a href="dodaj.php">Dodaj Oglas</a>
</div>

</div>

<script>
    function decline(id_from, id_to){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                let reqs = JSON.parse(this.responseText);
                prikaziZahteve(reqs);
            }
        }
        xhttp.open('GET','friend_request.php?obij=1&id_from=' + id_from + '&id_to=' + id_to, true);
        xhttp.send();
    }
    function accept(id_from, id_to){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                console.log(this.responseText);
                let reqs = JSON.parse(this.responseText);
                console.log(reqs);
                prikaziZahteve(reqs);
            }
        }
        xhttp.open('GET','friend_request.php?prihvati=1&id_from=' + id_from + '&id_to=' + id_to, true);
        xhttp.send();
    }
    function prikaziZahteve(reqs){
        let pom = '';
        reqs.forEach(element => {
            pom += '<li><a>'
            pom +=  element.username;
            pom += '</a>'
            pom += '<button onclick="accept('+ element.id_from +','+ element.id_to +')">Prihvati </button>'
            pom += '<button onclick="decline('+ element.id_from +','+ element.id_to +')">Odbij </button>'
            pom += '</li>'
        });
        document.getElementById('lista-zahteva').innerHTML = pom;
    }
    function prikaziOglas(id){
        window.location="prikazi.php?id="+id;
    }
    function prikaziOglase(oglasi){
        let pom = '';
        oglasi.forEach(element => {
            pom += '<div id="oglas">';
            pom += '<img src="' + element.path_slike + '">';
            pom += '<h3>' + element.naslov + '</h3>';
            pom += '<p>' + element.tekst + '</p>';
            pom += '</div>';
        });
        document.getElementById('oglasi').innerHTML = pom;
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
            pom += '<li><a onclick="prikaziKorisnika('+element.id+')">'
            pom += element.username;
            pom += '</a></li>';
        });
        pom += '</ul>';
        document.getElementById('prikaz-korisnika').innerHTML = pom;
    }
    function prikaziKorisnika(id){
        window.location = "prikaz_korisnika.php?id=" + id;
    }
    function prikaziKategoriju(id){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                let oglasi = JSON.parse(this.responseText);
                console.log(oglasi);
                prikaziOglase(oglasi);
            }
        }
        xhttp.open('GET','prikazi_kategoriju.php?id=' + id,true);
        xhttp.send();
    }

</script>
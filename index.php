
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
?>
<div id="cnt">
<div id="leviMeni">
    <a id="logOut" href="index.php?Logout">LogOut</a>

    <h3>Dobrodosao <?php if($userInfo) echo $userInfo['ime']." ".$userInfo['prezime']. " "; ?> <span style="color:blue;"><?php echo $user['username']; ?></span>!</h3>
    <div id="profil">
        <ul>  
            <li><a href="moj_profil.php">Moj profil</a></li>
            <li><a href="moji_oglasi.php" >Moji oglasi</a></li>
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

<script>
    function prikaziOglas(id){
        window.location="prikazi.php?id="+id;
    }
    function prikaziOglase(oglasi){
        let pom = '';
        oglasi.forEach(element => {
            pom += '<div id="oglas">';
            pom += '<img src="' + element.path_slike + '">';
            pom += '<h3>' + element.naslov + '</h3>';
            pom += '<a onclick = "deleteOglas(' + element.id + ')">Obrisi oglas</a>'
            pom += '<a onclick = "prikaziOglas(' + element.id + ')">Prikazi oglas</a>'
            pom += '<a onclick = "izmeniOglas(' + element.id + ')">Izmeni oglas</a>'
            pom += '</div>';
        });
        document.getElementById('oglasi').innerHTML = pom;
        document.getElementById
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
    function search(){
        let text = document.getElementById('search').value;
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                let oglasi = JSON.parse(this.responseText);
                prikaziOglase(oglasi);
            }
        }
        xhttp.open('GET','pretrazi.php?naslov=' + text, true);
        xhttp.send();
    }
</script>
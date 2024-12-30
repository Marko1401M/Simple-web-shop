
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
    $oglasi = $baza->getPraceniOglasi($_SESSION['id']);
?>
<div id="cnt">
<div id="leviMeni">
    <a id="logOut" href="index.php?Logout">LogOut</a>

    <h3>Dobrodosao <span style="color:blue;"><?php echo $user['username']; ?></span>!</h3>
    <div id="profil">
        <ul>  
            <li><a href="moj_profil.php">Moj profil</a></li>
            <li><a href="moji_oglasi.php" >Moji oglasi</a></li>
            <li><a style="color:darkblue;" >Oglasi koje pratim</a></li>
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
    <div id="oglasi">
        <?php foreach($oglasi as $o){ $oglas = $baza->getOglas($o['id_oglasa']) ?>
            <div style="cursor:default" id="oglas">
                <img src="<?php echo $oglas['path_slike']; ?>">
                <h3><?php echo $oglas['naslov']; ?></h3>
                <a onclick="prikaziOglas(<?php echo $oglas['id'] ?>)" class="btn">Prikazi Oglas</a><br>
                <div style="height:10px"></div>
                <a onclick="izbaciOglas(<?php echo $oglas['id'] ?>)" style="width:150px;" class="btn">Izbaci iz pracenja</a>
            </div>
        <?php } ?>
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
    function izbaciOglas(id){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                let oglasi = JSON.parse(this.responseText);
                prikaziOglase(oglasi);
            }
        }
        xhttp.open('GET','follow2.php?unfollow=1&id_oglasa=' + id, true);
        xhttp.send();
    }
    function prikaziOglase(oglasi){
        if(oglasi == null) return;
        console.log(oglasi);
        let pom = '';
        oglasi.forEach(element => {
            pom += '<div id="oglas">';
            pom += '<img src="' + element.path_slike + '">';
            pom += '<h3>' + element.naslov + '</h3>';
            pom += '<a class="btn" onclick = "prikaziOglas(' + element.id + ')">Prikazi oglas</a><br>'
            pom += '<div style="height:10px;"></div>'
            pom += '<a class="btn" onclick = "izbaciOglas(' + element.id + ')">Izbaci oglas</a>'
            pom += '</div>';
        });
        document.getElementById('oglasi').innerHTML = pom;
    }
</script>
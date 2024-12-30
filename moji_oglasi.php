
<head>
    <link rel="stylesheet" href="style.css">
</head>
<?php 
    require_once 'baza.php';
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
    $oglasi = $baza->getOglasiById($_SESSION['id']);//Naravno ovo nije dobra ideja ako ima preveliki broj oglasa, ali obzirom da je ovo sam primer onda neka ga
?>
<div id="cnt">
<div id="leviMeni">
    <a id="logOut" href="index.php?Logout">LogOut</a>

    <h3>Dobrodosao <span style="color:blue;"><?php echo $user['username']; ?></span>!</h3>
    <div id="profil">
        <ul>  
            <li><a href="moj_profil.php">Moj profil</a></li>
            <li><a style="color:darkblue;" href="moji_oglasi.php" >Moji oglasi</a></li>
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
    <div id="oglasi">
    
        <?php foreach($oglasi as $oglas){ ?>
            <div style="cursor:default" id="oglas">
                <img src="<?php echo $oglas['path_slike']; ?>">
                <h3><?php echo $oglas['naslov']; ?></h3>
                <a id="delOglasbtn" style="cursor:pointer" class="btn" onclick="deleteOglas(<?php echo $oglas['id']?>)">Obrisi oglas</a><br>
                <div style="height:5px;"></div>
                <a style="cursor:pointer" class="btn" onclick="prikaziOglas(<?php echo $oglas['id']; ?>)" >Prikazi oglas</a><br>
                <div style="height:5px;"></div>
                <a style="cursor:pointer" class="btn" onclick="izmeniOglas(<?php echo $oglas['id']; ?>)" >Izmeni oglas</a>
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
    function prikaziOglase(oglasi){
        let pom = '';
        oglasi.forEach(element => {
            pom += '<div style="cursor:default" id="oglas">';
            pom += '<img src="'+element.path_slike+'">';
            pom += '<h3>' + element.naslov + '</h3>';
            pom += '<a id="delOglasbtn" style="cursor:pointer" class="btn" onclick="deleteOglas('+ element.id +')">Obrisi oglas</a>';
            pom += '<div style="height:5px;"></div>'
            pom += '<a style="cursor:pointer" class="btn" onclick="prikaziOglas('+ element.id +')">PrikaziOglas</a>';
            pom += '<div style="height:5px;"></div>';
            pom += '<a style="cursor:pointer" class="btn" onclick="izmeniOglas('+element.id+')">Izmeni oglas</a>';
            pom += '</div>'
        });
        document.getElementById('oglasi').innerHTML = pom;
    }
</script>
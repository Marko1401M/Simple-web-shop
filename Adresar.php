
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
    $adresar = $baza->getAdresar($_SESSION['id']);
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
            <li><a>Adresar</a></li>
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
        
        
    </div>
</div>

<div id ='sredina'>
<div style="border-top:1px solid blue;margin-top:5px;margin-bottom:5px;padding:5px;" id="korisnici">

        <div id="prikaz-korisnika">
            <h5 style="margin-bottom:5px;border-bottom:1px solid blue;overflow:hidden;width:390px">Lista osoba koje se nalaze u Vasem adresaru:</h5>
        <ul id="lista-adresar">
                <?php foreach($adresar as $usr){ ?>
                    <li>
                        <a  class="adr-pr" onclick="prikaziKorisnika(<?php echo $usr['id_seller']; ?>)"><?php echo $baza->getUserById($usr['id_seller'])['username']; ?></a>
                        <button class="adr" id="rem-fr-adr" onclick="izbaciIzAdresara(<?php echo $usr['id_seller'] ?>)">
                            Izbaci iz adresara
                        </button>
                    </li>
                <?php } ?>
            </ul>
        </div>
        
    </div>
</div>

<div id="desno">
    <a href="dodaj.php">Dodaj Oglas</a>
    <br><br><br>
    <a href="index.php">Pocetna</a>
</div>

</div>

<script>
    function prikaziOglas(id){
        window.location="prikazi.php?id="+id;
    }
    function prikaziKorisnika(id){
        window.location = "prikaz_korisnika.php?id=" + id;
    }
    function izbaciIzAdresara(id){
        let xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function(){
            if(this.status == 200 && this.readyState == 4){
                prikaziAdresar(JSON.parse(this.responseText));
            }
        }
        xhttp.open('GET',"add_to_adresar.php?izbaci=1&id=" + id, true);
        xhttp.send();
    }
    function prikaziAdresar(adresar){
        let pom = '';
        adresar.forEach(element => {
            pom += '<li>'
            pom += '<a>' + element.username + '</a>'
            pom += '<button onclick="izbaciIzAdresara('+ element.id_seller +')">' + 'Izbaci iz adresara' + '</button>';
            pom += '</li>'
        });
        document.getElementById('lista-adresar').innerHTML = pom;
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
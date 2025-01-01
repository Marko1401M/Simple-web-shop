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
    document.getElementById
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

function validateRegister(){
    if(document.getElementById('username').value == ""){
        alert('Niste uneli username');
        return;
    }
    if(document.getElementById('ime').value  == ""){
        alert('Niste uneli ime');
        return;
    }
    if(document.getElementById('prezime').value  == ""){
        alert('Niste uneli prezime');
        return;
    }
    if(document.getElementById('broj-telefona').value  == ""){
        alert('Niste uneli broj telefona');
        return;
    }
    if(document.getElementById('mail').value  == ""){
        alert('Niste uneli mail');
        return;
    }
    if(document.getElementById('password').value  == ""){
        alert('Niste uneli sifru');
        return;
    }
    if(document.getElementById('password2').value  == ""){
        alert('Niste potvrdili sifru');
        return;
    }
    document.getElementById('register-forma').submit();
}

function register(){
    document.getElementById('login').style.display = 'none';
    document.getElementById('register').style.display = '';
}
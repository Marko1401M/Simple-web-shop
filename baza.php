<?php 

class BazaKP{
    const ime_hosta = 'localhost';
    const ime_baze = 'KP';
    const ime_korisnika = 'root';
    const sifra_korisnika = '';
    private $dbh;
    public function __construct(){
        $konekcioni_string = 'mysql:host='.self::ime_hosta.';dbname='.self::ime_baze;
        $this->dbh = new PDO($konekcioni_string, self::ime_korisnika, self::sifra_korisnika);
    }
    public function checkUsername($username){
        $sql = "SELECT * from user where username = '$username'";
        $stmt = $this->dbh->query($sql);
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($user) > 0) return false;
        return true;
    }
    public function register($username, $password){
        $sql = "INSERT INTO user(username, password) VALUES('$username','$password')";
        $this->dbh->exec($sql);
    }
    public function login($username, $password){
        $sql = "SELECT * from user where username = '$username' and password = '$password'";
        $stmt = $this->dbh->query($sql);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    public function getUserById($id){
        $sql = "SELECT * from user where id = '$id'";
        $stmt = $this->dbh->query($sql);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
    public function getKategorije(){
        $sql = "SELECT * from kategorija";
        $stmt = $this->dbh->query($sql);
        $kategorije = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $kategorije;
    }
    public function getOglasi(){
        $sql = "SELECT * from oglas";
        $stmt = $this->dbh->query($sql);
        $oglasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $oglasi;
    }
    public function dodajOglas($id_user,$naslov,$tekst,$kategorija,$path){
        $sql = "INSERT INTO oglas(id_korisnika,naslov,id_kategorije,path_slike,tekst) ";
        $sql .= "VALUES('$id_user','$naslov','$kategorija','$path','$tekst')";
        $this->dbh->exec($sql);
    }
    public function getOglas($id){
        $sql = "SELECT * from oglas where id='$id'";
        $stmt = $this->dbh->query($sql);
        $oglas = $stmt->fetch(PDO::FETCH_ASSOC);
        return $oglas;
    }
    public function proveriPracenje($id_oglasa, $id_user){
        $sql = "SELECT * from follow where id_oglasa = '$id_oglasa' and id_korisnika = '$id_user'";
        $stmt = $this->dbh->query( $sql );
        if($stmt->rowCount() > 0) return true;
        return false;
    }
    public function followOglas($id_oglasa, $id_user){
        $sql = "INSERT INTO follow(id_oglasa, id_korisnika) VALUES('$id_oglasa','$id_user')";
        $this->dbh->exec($sql);
    }
    public function unfollowOglas($id_oglasa, $id_user){
        $sql = "DELETE from follow where id_oglasa = '$id_oglasa' and id_korisnika ='$id_user'";
        $this->dbh->exec($sql);
    }
    public function getOglasiById($id_user){
        $sql = "SELECT * from oglas where id_korisnika = '$id_user'";
        $stmt = $this->dbh->query($sql);
        $oglasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $oglasi;
    }
    public function deleteOglas($id){
        try{
            $sql = "DELETE from oglas where id = '$id'";
            $this->dbh->query($sql);
        }
        catch(PDOException $e) {
            header('Location: index.php');
        }
    }
    public function getPraceniOglasi($id){
        try{
            $sql = "SELECT * from follow where id_korisnika = '$id'";
            $stmt = $this->dbh->query($sql);
            $oglasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $oglasi;
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
    }
    public function updatePassword($id, $password){
        try{
            $sql = "UPDATE user SET password = '$password' where id ='$id'";
            $this->dbh->exec($sql);
        }
        catch(PDOException $e){

        }
    }
    public function updateMail($id, $mail){
        $check = false;
        $sql = "SELECT * from mails where user_id = '$id'";
        $stmt = $this->dbh->query($sql);
        if($stmt->rowCount() > 0){
            $sql = "UPDATE mails set mail='$mail' where id = '$id'";
        }
        else{
            $sql = "INSERT INTO mails(user_id, mail) VALUES('$id','$mail')";
        }
        $this->dbh->exec($sql);
    }
    public function getMail($id){
        try{
            $sql = "SELECT * from mails where user_id = '$id'";
            $stmt = $this->dbh->query($sql);
            $mail = $stmt->fetch(PDO::FETCH_ASSOC);
            return $mail['mail'];
        }
        catch(PDOException $e){}
    }
}
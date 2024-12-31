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
            $this->dbh->exec($sql);
            $sql = "DELETE from follow where id_oglasa = '$id'";
            $this->dbh->exec($sql);
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
            $sql = "UPDATE mails set mail='$mail' where user_id = '$id'";
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
    public function addUserInfo($id, $ime, $prezime, $broj_telefona){
        try{
            $sql = "INSERT into user_info(user_id, ime, prezime, broj_telefona) VALUES('$id','$ime','$prezime','$broj_telefona')";
            $this->dbh->exec($sql);
        }
        catch(PDOException $e){

        }
    }
    public function getUserInfo($id){
        try{
            $sql = "SELECT * from user_info where user_id = '$id'";
            $stmt = $this->dbh->query($sql);
            $info = $stmt->fetch(PDO::FETCH_ASSOC);
            return $info;
        }
        catch(PDOException $e){

        }
    }
    public function pretrazi($naslov){
        try{
            $sql = "SELECT * FROM oglas WHERE naslov LIKE :naslov";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':naslov', $naslov . '%', PDO::PARAM_STR);
            $stmt->execute();
            $oglasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $oglasi;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function findUserByUsername($username){
        try{
            $sql = "SELECT * FROM user where username LIKE :username";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindValue(':username',$username . '%', PDO::PARAM_STR);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $users;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function sendFriendRequest($id_from, $id_to){
        try{
            if($this->checkFriendRequest($id_from, $id_to)) return;
            $sql = "INSERT into friend_request(id_from, id_to, status) VALUES('$id_from','$id_to','waiting')";
            $this->dbh->exec($sql);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function checkFriend($id1, $id2){
        try{
            $sql = "SELECT * from friend where (id_user1 = '$id1' and id_user2 = '$id2') or (id_user1 = '$id2' and id_user2 = '$id1')";
            $stmt = $this->dbh->query($sql);
            if($stmt->rowCount() > 0) return true;
            return false;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function checkFriendRequest($id1, $id2){
        try{
            $sql = "SELECT * from friend_request where id_from = '$id1' and id_to = '$id2' and status = 'waiting'";
            $stmt = $this->dbh->query($sql);
            if($stmt->rowCount() > 0) return true;
            return false;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function getFriendRequests($id){
        try{
            $sql = "SELECT * from friend_request where id_to = '$id'";
            $stmt = $this->dbh->query($sql);
            $reqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $reqs;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function acceptFriendRequest($id1, $id2){
        try{
            $sql = "INSERT INTO friend(id_user1, id_user2) VALUES('$id1','$id2')";
            $this->dbh->exec($sql);
            $sql = "UPDATE friend_request SET status = 'accepted' where id_from = '$id1' and id_to ='$id2' and status='waiting'";
            $this->dbh->exec($sql);
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function getFriends($id){
        try{
            $sql = "SELECT * from friend where id_user1 = '$id' or id_user2='$id'";
            $stmt = $this->dbh->query($sql);
            $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $friends;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
}
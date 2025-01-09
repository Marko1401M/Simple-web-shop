<?php 

class Baza{
    const ime_hosta = 'localhost';
    const ime_baze = 'db_shop';
    const ime_korisnika = 'root';
    const sifra_korisnika = '';
    private $dbh;
    public function __construct(){
        $konekcioni_string = 'mysql:host='.self::ime_hosta.';dbname='.self::ime_baze;
        $this->dbh = new PDO($konekcioni_string, self::ime_korisnika, self::sifra_korisnika);
    }
    public function checkUsername($username){
        $sql = "SELECT * from user where username = :username";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':username',$username);
        $stmt->execute();
        $user = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if(count($user) > 0) return false;
        return true;
    }
    public function register($username, $password){
        $sql = "INSERT INTO user(username, password) VALUES(:username, :password)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
    }
    public function login($username, $password) {
        $sql = "SELECT * FROM user WHERE username = :username AND password = :password";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserById($id) {
        $sql = "SELECT * FROM user WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getKategorije() {
        $sql = "SELECT * FROM kategorija";
        $stmt = $this->dbh->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getOglasi() {
        $sql = "SELECT * FROM oglas";
        $stmt = $this->dbh->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function dodajOglas($id_user, $naslov, $tekst, $kategorija, $path) {
        $sql = "INSERT INTO oglas(id_korisnika, naslov, id_kategorije, path_slike, tekst) VALUES(:id_user, :naslov, :kategorija, :path, :tekst)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->bindParam(':naslov', $naslov, PDO::PARAM_STR);
        $stmt->bindParam(':kategorija', $kategorija, PDO::PARAM_INT);
        $stmt->bindParam(':path', $path, PDO::PARAM_STR);
        $stmt->bindParam(':tekst', $tekst, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function getOglas($id) {
        $sql = "SELECT * FROM oglas WHERE id = :id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function proveriPracenje($id_oglasa, $id_user) {
        $sql = "SELECT * FROM follow WHERE id_oglasa = :id_oglasa AND id_korisnika = :id_user";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id_oglasa', $id_oglasa, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->rowCount() > 0;
    }

    public function followOglas($id_oglasa, $id_user) {
        $sql = "INSERT INTO follow(id_oglasa, id_korisnika) VALUES(:id_oglasa, :id_user)";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id_oglasa', $id_oglasa, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function unfollowOglas($id_oglasa, $id_user) {
        $sql = "DELETE FROM follow WHERE id_oglasa = :id_oglasa AND id_korisnika = :id_user";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id_oglasa', $id_oglasa, PDO::PARAM_INT);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getOglasiById($id_user) {
        $sql = "SELECT * FROM oglas WHERE id_korisnika = :id_user";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteOglas($id) {
        $sql1 = "DELETE FROM oglas WHERE id = :id";
        $sql2 = "DELETE FROM follow WHERE id_oglasa = :id";
        $stmt1 = $this->dbh->prepare($sql1);
        $stmt2 = $this->dbh->prepare($sql2);
        $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt2->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();
        $stmt2->execute();
    }

    public function getPraceniOglasi($id){
        try{
            $sql = "SELECT * from follow where id_korisnika = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $oglasi = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $oglasi;
        }
        catch(PDOException $e) {
            $e->getMessage();
        }
    }
    public function updatePassword($id, $password){
        try{
            $sql = "UPDATE user SET password = :password where id = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':password', $password);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
        }
        catch(PDOException $e){

        }
    }
    public function updateMail($id, $mail){
        $check = false;
        $sql = "SELECT * from mails where user_id = :id";
        $stmt = $this->dbh->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        if($stmt->rowCount() > 0){
            $sql = "UPDATE mails set mail=:mail where user_id = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam('id',$id);
            $stmt->bindParam('mail',$mail);
        }
        else{
            $sql = "INSERT INTO mails(user_id, mail) VALUES(:id,:mail)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam('id',$id);
            $stmt->bindParam('mail',$mail);
        }
        $stmt->execute();
    }
    public function getMail($id){
        try{
            $sql = "SELECT * from mails where user_id = ':id'";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $mail = $stmt->fetch(PDO::FETCH_ASSOC);
            return $mail['mail'];
        }
        catch(PDOException $e){}
    }
    public function addUserInfo($id, $ime, $prezime, $broj_telefona){
        try{
            $sql = "INSERT into user_info(user_id, ime, prezime, broj_telefona) VALUES(:id,:ime,:prezime,:broj_telefona)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam('id',$id);
            $stmt->bindParam('ime',$ime);
            $stmt->bindParam('prezime',$prezime);
            $stmt->bindParam('broj_telefona',$broj_telefona);
            $stmt->execute();
        }
        catch(PDOException $e){

        }
    }
    public function getUserInfo($id){
        try{
            $sql = "SELECT * from user_info where user_id = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam('id',$id);
            $stmt->execute();
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
            $sql = "INSERT into friend_request(id_from, id_to, status) VALUES(:id_from,:id_to,:statu)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id_from',$id_from);
            $stmt->bindParam(':id_to',$id_to);
            $st = 'waiting';
            $stmt->bindParam(':statu',$st);
            $stmt->execute();
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function checkFriend($id1, $id2){
        try{
            $sql = "SELECT * from friend where (id_user1 = :id1 and id_user2 = :id2) or (id_user1 = :id2 and id_user2 = :id1)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id1',$id1);
            $stmt->bindParam(':id2',$id2);
            $stmt->execute();
            if($stmt->rowCount() > 0) return true;
            return false;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function checkFriendRequest($id1, $id2){
        try{
            $sql = "SELECT * from friend_request where id_from = :id1 and id_to = :id2 and status = :stat";
            $stmt = $this->dbh->prepare($sql);
            $st = 'waiting';
            $stmt->bindParam(':id1',$id1);
            $stmt->bindParam(':id2',$id2);
            $stmt->bindParam(':stat',$st);
            $stmt->execute();
            if($stmt->rowCount() > 0) return true;
            return false;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function getFriendRequests($id){
        try{
            $sql = "SELECT * from friend_request where id_to = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $reqs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $reqs;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function acceptFriendRequest($id1, $id2){
        try{
            $sql = "INSERT INTO friend(id_user1, id_user2) VALUES(:id1,:id2)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id1',$id1);
            $stmt->bindParam(':id2',$id2);
            $stmt->execute();
            $st1 = 'accepted';
            $st2 = 'waiting';

            $sql = "UPDATE friend_request SET status = :st1 where id_from = :id1 and id_to =:id2 and status= :st2";
            $stmt->bindParam(':id1',$id1);
            $stmt->bindParam(':id2',$id2);
            $stmt->bindParam(':st1',$st1);
            $stmt->bindParam(':st2',$st2);
            $stmt->execute();
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function getFriends($id){
        try{
            $sql = "SELECT * from friend where id_user1 = :id or id_user2= :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
            $friends = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $friends;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function getChats($id1){
        try{
            $sql = "SELECT * from chat where sender_id = :id1 or reciever_id = :id1";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id1',$id1);
            $stmt->execute();
            $chats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $chats;
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }
    public function getChat($id1, $id2){
        try{
            $sql = "SELECT * from chat where (sender_id = :id1 and reciever_id = :id2) or (sender_id = :id2 and reciever_id =:id1)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id1',$id1);
            $stmt->bindParam(':id2',$id2);
            $stmt->execute();
            $chat = $stmt->fetch(PDO::FETCH_ASSOC);
            return $chat;
        }
        catch(PDOException $e){

        }
    }
    public function createChat($id1, $id2){
        try{
            $sql = "INSERT into chat(sender_id, reciever_id) VALUES(:id1,:id2)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':id1',$id1);
            $stmt->bindParam(':id2',$id2);
            $stmt->execute();
        }
        catch(PDOException $e){

        }
    }
    public function getMessages($chat_id){
        try{
            $sql = "SELECT * from poruka where chat_id = :chat_id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':chat_id',$chat_id);
            $stmt->execute();
            $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $messages;
        }
        catch(PDOException $e){

        }
    }
    public function sendMessage($chat_id, $from, $to, $text){
        try{
            $sql = "INSERT into poruka(chat_id, sender_id, reciever_id, text) VALUES(:chat_id,:from,:to,:text)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':chat_id',$chat_id);
            $stmt->bindParam(':from',$from);
            $stmt->bindParam(':to',$to);
            $stmt->bindParam(':text',$text);
            $stmt->execute();
        }
        catch(PDOException $e){

        }
    }
    public function dodajUAdresar($user_id, $seller_id){
        try{
            $sql = "INSERT INTO Adresar(id_user, id_seller) VALUES(:user_id,:seller_id)";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':user_id',$user_id);
            $stmt->bindParam(':seller_id',$seller_id);
            $stmt->execute();
        }
        catch(PDOException $e){

        }
    }
    public function getAdresar($user_id){
        try{
            $sql = "SELECT * from adresar where id_user = :user_id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':user_id',$user_id);
            $stmt->execute();
            $adresar = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $adresar;
        }
        catch(PDOException $e){

        }
    }
    public function checkAdresar($user_id, $seller_id){
        try{
            $sql = "SELECT * from adresar where id_user = :user_id and id_seller = :seller_id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':user_id',$user_id);
            $stmt->bindParam(':seller_id',$seller_id);
            $stmt->execute();
            if($stmt->rowCount() > 0) return true;
            return false;
        }
        catch(PDOException $e){

        }
    }
    public function izbaciIzAdresara($user_id, $seller_id){
        try{
            $sql = "DELETE FROM adresar where id_user = :user_id and id_seller = :seller_id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':user_id',$user_id);
            $stmt->bindParam(':seller_id',$seller_id);
            $stmt->execute();
        }
        catch(PDOException $e){

        }
    }

    public function updateOglas($id, $naslov, $tekst, $kat, $path_slike){
        try{
            $sql = "UPDATE oglas set naslov = :naslov, id_kategorije = :id_kat, tekst=:tekst, path_slike = :path_slike where id = :id";
            $stmt = $this->dbh->prepare($sql);
            $stmt->bindParam(':naslov',$naslov);
            $stmt->bindParam(':tekst',$tekst);
            $stmt->bindParam(':id_kat',$kat);
            $stmt->bindParam(':path_slike',$path_slike);
            $stmt->bindParam(':id',$id);
            $stmt->execute();
        }
        catch(PDOException $e){
            
        }
    }
}

<?php 
    require_once 'db/baza.php';
    $baza = new BazaKP();
    $checkPassMatch = true;
    $checkUserExists = true;
    $checkValidCred = true;
    if(isset($_POST['password2'])){
        $password = sha1($_POST['password']);
        if($password == sha1($_POST['password2'])){
            if($baza->checkUsername($_POST['username'])){
                $baza->register($_POST['username'], $password);
                $user = $baza->login($_POST['username'], $password);
                $baza->addUserInfo($user['id'],$_POST['ime'],$_POST['prezime'],$_POST['broj-telefona']);
                $baza->updateMail($user['id'], $_POST['mail']);
                session_start();
                $_SESSION['id'] = $user['id'];
                setcookie('username',$_POST['username'],time() + 80000*2);
                if(isset($_POST['remember'])){
                    setcookie('password',$password,time() + 80000 * 2);
                }
                header('Location: index.php');
                exit();
            }
            else $checkUserExists = false;
        }
        else $checkPassMatch = false;
    }
    else if(isset($_POST['username'])){
        $password = sha1($_POST['password']);
        $user = $baza->login($_POST['username'], $password);
        if($user){
            session_start();
            $_SESSION['id'] = $user['id'];
            setcookie('username',$user['id'],time() + 80000 * 2);
            if(isset($_POST['remember'])){
                setcookie('password',$password,time() + 80000*2);
            }
            header('Location: index.php');
            exit();
        }
        else $checkValidCred = false;
    }

?>

<link rel="stylesheet" href="assets/style.css">

<div id="page">
    <div id="login">
        <form method="POST">
            <input name="username" type="text" placeholder="korisnicko ime"><br>
            <input name="password" type="password" placeholder ="lozinka"><br>
            <input name="remember" type="checkbox">Zapamti me<br>
            <input id="log-btn" type="submit" value="Login">
        </form>
        <?php if(!$checkValidCred){ ?>
            <h4 style="color:red;">Username/sifra se ne poklapaju!</h4>
        <?php } ?>
        <button id="new-btn" onclick="register()">Novi nalog!</button>

    </div>
    <div id="register" style="display:none;">
        <form id="register-forma" method="POST">
            <input id="username" name="username" type="text" placeholder="korisnicko ime" required><br>
            <input id="ime" name="ime" type="text" placeholder="ime" required><br>
            <input id="prezime" name="prezime" type="text" placeholder="prezime" required><br>
            <input id="broj-telefona" name="broj-telefona" type="text" placeholder="broj telefona" required><br>
            <input id="mail" name="mail" type="email" placeholder="email" required><br>
            <input id="password" name="password" type="password" placeholder ="lozinka" required><br>
            <input id="password2" name="password2" type="password" placeholder ="potvrdi lozinku" required><br>
            <input name="remember" type="checkbox" required>Zapamti me<br>
            <input id="reg-btn" type="button" onclick="validateRegister()" value="Register">
        </form>
        <?php 
            if(!$checkUserExists){
        ?>
            <h4 style="color:red;">Username vec postoji!</h4>
        <?php } ?>
    </div>
</div>

<script src="assets/script.js">
    
</script>



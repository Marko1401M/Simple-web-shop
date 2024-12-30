
<?php 
    require_once 'baza.php';
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

<link rel="stylesheet" href="style.css">

<div id="page">
    <div id="login">
        <form method="POST">
            <input name="username" type="text" placeholder="korisnicko ime"><br>
            <input name="password" type="password" placeholder ="lozinka"><br>
            <input name="remember" type="checkbox">Zapamti me<br>
            <input type="submit" value="Login">
        </form>
        <?php if(!$checkValidCred){ ?>
            <h4 style="color:red;">Username/sifra se ne poklapaju!</h4>
        <?php } ?>
        <button onclick="register()">Novi nalog!</button>

    </div>
    <div id="register" style="display:none;">
        <form method="POST">
            <input name="username" type="text" placeholder="korisnicko ime"><br>
            <input name="password" type="password" placeholder ="lozinka"><br>
            <input name="password2" type="password" placeholder ="potvrdi lozinku"><br>
            <input name="remember" type="checkbox">Zapamti me<br>
            <input type="submit" value="Register">
        </form>
        <button onclick="register()">Novi nalog!</button>
        <?php 
            if(!$checkUserExists){
        ?>
            <h4 style="color:red;">Username vec postoji!</h4>
        <?php } ?>
    </div>
</div>

<script>
    function register(){
        document.getElementById('login').style.display = 'none';
        document.getElementById('register').style.display = '';
    }
</script>



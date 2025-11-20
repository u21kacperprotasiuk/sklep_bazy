<?php
session_start();
$pageTitle = 'Login/Signup';
if (isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}
include 'init.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        $user = $_POST['username'];
        $pass = $_POST['password'];
        $stmt = $con->prepare('SELECT `id`, `login`, `haslo` FROM `uzytkownicy` WHERE `login` = ? LIMIT 1');
        $stmt->execute(array($user));
        $get = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($get) {
            $stored = $get['haslo'];
            if ($stored === $pass || $stored === sha1($pass)) {
                $_SESSION['user'] = $get['login'];
                $_SESSION['uid']  = $get['id'];
                header('Location: index.php');
                exit();
            } else {
                $formErrors[] = 'Invalid username or password';
            }
        } else {
            $formErrors[] = 'Invalid username or password';
        }
    } else {
        $formErrors = array();
        $username  = $_POST['username'];
        $password  = $_POST['password'];
        $password2 = $_POST['password2'];
        $email     = $_POST['email'];
        if (isset($username)) {
            $filteredUser = htmlspecialchars($username);
            if (strlen($filteredUser) < 4) {
                $formErrors[] = 'Username must be more than 3 characters';
            }
        }
        if (isset($password) && isset($password2)) {
            if (empty($password)) {
                $formErrors[] = 'Password can\'t be empty';
            }
            if ($password !== $password2) {
                $formErrors[] = 'Passwords don\'t match each other';
            }
        }
        if (isset($email)) {
            $filteredEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
            if (filter_var($filteredEmail, FILTER_VALIDATE_EMAIL) != true) {
                $formErrors[] = 'This email is not valid';
            }
        }
        if (empty($formErrors)) {
            $stmt = $con->prepare('SELECT COUNT(*) AS cnt FROM `uzytkownicy` WHERE `login` = ?');
            $stmt->execute(array($username));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row && $row['cnt'] > 0) {
                $formErrors[] = 'This user already exists';
            } else {
                $stmt = $con->prepare('INSERT INTO `uzytkownicy` (`login`, `haslo`, `email`, `pelna_nazwa`, `data_rejestracji`) VALUES (:zuser, :zpass, :zmail, :zname, NOW())');
                $stmt->execute(array(
                    'zuser' => $username,
                    'zpass' => $password,
                    'zmail' => $email,
                    'zname' => ''
                ));
                $succesMsg = 'Congratz, You Are Now A Registered User';
            }
        }
    }
}
?>
    <div class="container login-page">
        <h1 class="text-center"><span class="selected" data-class="login">Login</span> | <span data-class="signup">SignUp</span></h1>
        <form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container"><input class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required></div>
            <div class="input-container"><input class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required></div>
            <input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
        </form>
        <form class="signup" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
            <div class="input-container"><input pattern=".{4,}" title="Username must be more than 3 characters" class="form-control" type="text" name="username" autocomplete="off" placeholder="Type Your Username" required></div>
            <div class="input-container"><input minlength="6" class="form-control" type="password" name="password" autocomplete="new-password" placeholder="Type Your Password" required></div>
            <div class="input-container"><input minlength="6" class="form-control" type="password" name="password2" autocomplete="new-password" placeholder="Type Your Password Again" required></div>
            <div class="input-container"><input class="form-control" type="email" name="email" placeholder="Type A Valid Email" required></div>
            <input class="btn btn-success btn-block" type="submit" name="signup" value="Signup">
        </form>
        <div class="the-errors text-center">
<?php
            if (!empty($formErrors)) {
                foreach ($formErrors as $error) {
                    echo '<div class="msg error">' . $error . '</div>';
                }
            }
            if (isset($succesMsg)) {
                echo '<div class="msg success">' . $succesMsg . '</div>';
            }
?>
        </div>
    </div>
<?php
include $tpl . 'footer.php';
?>

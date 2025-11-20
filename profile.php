<?php
session_start();
$pageTitle = 'Profile';
include 'init.php';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
    $getUser = $con->prepare('SELECT * FROM `uzytkownicy` WHERE `login` = ? LIMIT 1');
    $getUser->execute(array($sessionUser));
    $info = $getUser->fetch(PDO::FETCH_ASSOC);
    $userid = $info['id'];
?>
        <h1 class="text-center">My Profile</h1>
        <div class="information block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading">My Information</div>
                    <div class="panel-body">
                        <ul class="list-unstyled">
                            <li><i class="fa fa-unlock-alt fa-fw" aria-hidden="true"></i> <span>Login Name</span>: <?php echo htmlspecialchars($info['login']) ?></li>
                            <li><i class="fa fa-envelope-o fa-fw" aria-hidden="true"></i> <span>Email</span>: <?php echo htmlspecialchars($info['email']) ?></li>
                            <li><i class="fa fa-user fa-fw" aria-hidden="true"></i> <span>Full Name</span>: <?php echo htmlspecialchars($info['pelna_nazwa']) ?></li>
                            <li><i class="fa fa-calendar fa-fw" aria-hidden="true"></i> <span>Registration Date</span>: <?php echo $info['data_rejestracji'] ?></li>
                        </ul>
                        <a href="#" class="btn btn-default">Edit Information</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="my-ads block" id="my-ads">
                <div class="container">
                    <div class="panel panel-primary">
                        <div class="panel-heading">My Comments</div>
                        <div class="panel-body">
<?php
                            $stmt = $con->prepare('SELECT * FROM `komentarze` WHERE `uzytkownik_id` = ? ORDER BY `data_zmiany` DESC');
                            $stmt->execute(array($userid));
                            $myComments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                            if (!empty($myComments)) {
                                foreach ($myComments as $comment) {
                                    echo '<div class="well">' . nl2br(htmlspecialchars($comment['komentarz'])) . '<br><small>' . $comment['data_zmiany'] . '</small></div>';
                                }
                            } else {
                                echo 'There are no comments to show';
                            }
?>      
                        </div>
                    </div>
                </div>
            </div>
<?php
} else {
    header('Location: login.php');
    exit();
}
include $tpl . 'footer.php';
?>

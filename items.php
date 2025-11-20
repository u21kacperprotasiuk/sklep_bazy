<?php
session_start();
$pageTitle = 'Show Item';
include 'init.php';
$itemid = isset($_GET['itemid']) && is_numeric($_GET['itemid']) ? intval($_GET['itemid']) : 0;
$stmt = $con->prepare('SELECT p.*, k.nazwa AS category_name FROM `produkty` p LEFT JOIN `kategorie` k ON k.id = p.kategoria_id WHERE p.id = ?');
$stmt->execute(array($itemid));
$count = $stmt->rowCount();
if ($count > 0) {
    $item = $stmt->fetch(PDO::FETCH_ASSOC);
?>
    <h1 class="text-center"><?php echo htmlspecialchars($item['nazwa']) ?></h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3"><img class="img-responsive img-thumbnail center-block" src="<?php echo !empty($item['zdjecie']) ? htmlspecialchars($item['zdjecie']) : 'img.jpg' ?>" alt="image"></div>
            <div class="col-md-9 item-info">
                <h2><?php echo htmlspecialchars($item['nazwa']) ?></h2>
                <p><?php echo nl2br(htmlspecialchars($item['opis'])) ?></p>
                <ul class="list-unstyled">
                    <li><i class="fa fa-calendar fa-fw" aria-hidden="true"></i> <span>Adding Date</span>: <?php echo $item['data_dodania'] ?></li>
                    <li><i class="fa fa-money fa-fw" aria-hidden="true"></i> <span>Price</span>: <?php echo number_format($item['cena'], 2) ?></li>
                    <li><i class="fa fa-building fa-fw" aria-hidden="true"></i> <span>Platform</span>: <?php echo htmlspecialchars($item['platforma']) ?></li>
                    <li><i class="fa fa-tags fa-fw" aria-hidden="true"></i> <span>Category</span>: <a href="categories.php?pageid=<?php echo $item['kategoria_id'] ?>"><?php echo htmlspecialchars($item['category_name']) ?></a></li>
                    <li><i class="fa fa-user fa-fw" aria-hidden="true"></i> <span>Stock</span>: <?php echo intval($item['ilosc_stan']) ?></li>
                </ul>
            </div>
        </div>
        <hr class="custom-hr">
<?php
        if (isset($_SESSION['user'])) {
?>
            <div class="row">
                <div class="col-md-offset-3">
                    <div class="add-comment">
                        <h3>Add Your Comment</h3>
                        <form action="<?php echo $_SERVER['PHP_SELF'] . '?itemid=' . $item['id'] ?>" method="POST">
                            <textarea name="comment" required></textarea>
                            <input class="btn btn-primary" type="submit" value="Add Comment">
                        </form>
<?php
                        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                            $comment = trim($_POST['comment']);
                            $itemid = $item['id'];
                            $userid = $_SESSION['uid'];
                            if (!empty($comment)) {
                                $stmt = $con->prepare('INSERT INTO `komentarze` (`komentarz`, `produkt_id`, `uzytkownik_id`, `data_komentarza`) VALUES (:zcomment, :zitemid, :zuserid, NOW())');
                                $stmt->execute(array(
                                    'zcomment' => $comment,
                                    'zitemid'  => $itemid,
                                    'zuserid'  => $userid
                                ));
                                if ($stmt) {
                                    echo '<br><div class= "alert alert-success">Comment Added!</div>';
                                }
                            }
                        }
?>
                    </div>
                </div>
            </div>
<?php
        } else {
            echo '<a href="login.php">Login</a> or <a href="login.php">Register</a> to add comment';
        }
?>
        <hr class="custom-hr">
<?php
        $stmt = $con->prepare('SELECT k.*, u.login AS username FROM `komentarze` k INNER JOIN `uzytkownicy` u ON u.id = k.uzytkownik_id WHERE k.produkt_id = ? ORDER BY k.data_zmiany DESC');
        $stmt->execute(array($item['id']));
        $comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($comments as $comment) {
?>
                <div class="comment-box">
                    <div class="row">
                        <div class="col-sm-2 text-center">
                            <img class="img-responsive img-thumbnail img-circle center-block" src="img.jpg" alt="random image">
                            <?php echo htmlspecialchars($comment['username']) ?>
                        </div>
                        <div class="col-sm-10">
                            <p class="lead"><?php echo nl2br(htmlspecialchars($comment['komentarz'])) ?></p>
                        </div>
                    </div>
                </div>
                <hr class="custom-hr">
<?php
        }
?>
    </div>
<?php
} else {
    echo '<div class="alert alert-danger">There\'s no such product ID or product is missing</div>';
}
include $tpl . 'footer.php';
?>

<?php
session_start();
include 'init.php';
?>
    <div class="container">
        <div class="row">
<?php
            if (isset($_GET['name'])) {
                $tag = trim($_GET['name']);
                echo '<h1 class="text-center">' . htmlspecialchars($tag) . '</h1>';
                $stmt = $con->prepare('SELECT * FROM `produkty` WHERE `opis` LIKE ? ORDER BY `data_dodania` DESC');
                $like = "%$tag%";
                $stmt->execute(array($like));
                $tagItems = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tagItems as $item) {
                    echo '<div class="col-sm-6 col-md-3">';
                        echo '<div class="thumbnail item-box">';
                            echo '<span class="price-tag">' . number_format($item['cena'], 2) . ' zł</span>';
                            $img = !empty($item['zdjecie']) ? $item['zdjecie'] : 'img.jpg';
                            echo '<img class="img-responsive" src="' . htmlspecialchars($img) . '" alt="random image">';
                            echo '<div class="caption">';
                                echo '<h3><a href="items.php?itemid=' . $item['id'] . '">' . htmlspecialchars($item['nazwa']) . '</a></h3>';
                                echo '<p>' . htmlspecialchars(mb_strimwidth($item['opis'], 0, 100, '...')) . '</p>';
                                echo '<div class="date">' . $item['data_dodania'] . '</div>';
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                }
            } else {
                echo 'You must specify Tag name';
            }
?>      
        </div>
    </div>
<?php
    include $tpl . 'footer.php';
?>

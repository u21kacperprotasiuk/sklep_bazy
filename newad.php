<?php
session_start();
$pageTitle = 'Create New Product';
include 'init.php';
if (isset($_SESSION['user'])) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $formErrors = array();
            $nazwa = trim($_POST['name']);
            $opis = trim($_POST['description']);
            $cena = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $platforma = trim($_POST['platform']);
            $wydawca = trim($_POST['publisher']);
            $wersja = trim($_POST['version']);
            $ilosc = filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);
            $kategoria = filter_var($_POST['category'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($nazwa) < 4) {
                $formErrors[] = 'Product name must be at least 4 characters';
            }
            if (strlen($opis) < 10) {
                $formErrors[] = 'Product description must be at least 10 characters';
            }
            if ($cena === '' || !is_numeric($cena)) {
                $formErrors[] = 'Product price must be valid';
            }
            if (empty($kategoria) || $kategoria == 0) {
                $formErrors[] = 'Product category must be selected';
            }
            if (empty($formErrors)) {
                $stmt = $con->prepare('INSERT INTO `produkty` (`nazwa`, `opis`, `cena`, `data_dodania`, `pegi`, `platforma`, `wydawca`, `wersja`, `zdjecie`, `ilosc_stan`, `kategoria_id`) VALUES (:znazwa, :zopis, :zcena, NOW(), :zpegi, :zplatforma, :zwydawca, :zwersja, :zzdjecie, :zilosc, :zkat)');
                $stmt->execute(array(
                    'znazwa' => $nazwa,
                    'zopis' => $opis,
                    'zcena' => $cena,
                    'zpegi' => isset($_POST['pegi']) ? intval($_POST['pegi']) : 0,
                    'zplatforma' => $platforma,
                    'zwydawca' => $wydawca,
                    'zwersja' => $wersja,
                    'zzdjecie' => '',
                    'zilosc' => $ilosc ? intval($ilosc) : 0,
                    'zkat' => $kategoria
                ));
                if ($stmt) {
                    $succesMsg = 'Product Added';
                }
            }
        }
    ?>
        <h1 class="text-center"><?php echo $pageTitle ?></h1>
        <div class="create-ad block">
            <div class="container">
                <div class="panel panel-primary">
                    <div class="panel-heading"><?php echo $pageTitle ?></div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Name:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input pattern=".{4,}" required class="form-control live" data-class=".live-title" type="text" name="name" placeholder="Name of the product">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Description:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <textarea pattern=".{10,}" required class="form-control live" data-class=".live-desc" name="description" placeholder="Description of the product"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Price:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control live" data-class=".live-price" type="text" name="price" required placeholder="Price of the product">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Platform:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control" type="text" name="platform" placeholder="Platform">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Publisher:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control" type="text" name="publisher" placeholder="Publisher">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Version:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control" type="text" name="version" placeholder="Version">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">PEGI:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control" type="number" name="pegi" min="0" max="100" placeholder="PEGI">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Stock:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <input class="form-control" type="number" name="stock" min="0" value="0" placeholder="Stock quantity">
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                        <label class="col-sm-3 control-label">Category:</label>
                                        <div class="col-sm-10 col-md-9">
                                            <select name="category" required>
                                                <option value="0">...</option>
<?php
                                            $cats = $con->query('SELECT * FROM `kategorie` ORDER BY `nazwa`')->fetchAll(PDO::FETCH_ASSOC);
                                            foreach ($cats as $cat) {
                                                echo '<option value="' . $cat['id'] . '">' . htmlspecialchars($cat['nazwa']) . '</option>';
                                            }
?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg">
                                            <div class="col-sm-offset-3 col-sm-9">
                                            <input class="btn btn-primary btn-sm" type="submit" value="Add Product">
                                            </div>
                                    </div>
                                    </form>
                            </div>
                            <div class="col-md-4">
                                <div class="thumbnail item-box live-preview">
                                    <span class="price-tag">
                                        <span class="live-price">0</span>
                                    </span>
                                    <img class="img-responsive" src="img.jpg" alt="random image">
                                    <div class="caption">
                                        <h3 class="live-title">Title</h3>
                                        <p class="live-desc">Description</p>
                                    </div>
                                </div>
                            </div>
                        </div>
<?php
                    if (!empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error . '</div>';
                        }
                    }
                    if (isset($succesMsg)) {
                        echo '<div class="alert alert-success">' . $succesMsg . '</div>';
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

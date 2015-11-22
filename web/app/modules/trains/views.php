<?php
session_start();
include('../../config/config.db.php');
if (isset($_SESSION['login_role']) && intval($_SESSION['login_role']) > 2) {
    $view = $_POST['view'];
    if (strpos($view,'detail') !== false) {
        $id = substr($view, 7);
        $view = 'detail';
    }
    ?>
    <?php switch ($view): ?><?php case 'list': ?>
        <section class="trains">
            <div class="container">
                <button class="btn-actions btn-add ajax-action" data-action="trains-add_form"> Přidat nový</button>
                <div class="search-row">
                    <input type="text" id="search" class="form-control" placeholder="Zadejte hledané číslo vlaku">
                </div>
                <div class="train-list">
                    <?php $query = "SELECT cislo_zkv, rada, datum_preznaceni, flag_eko, km_probeh_po, vmax, delka,img_url from vlak";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="train-list_item">
                            <div class="train-list_item_column">
                                <img src="upload_pic/<?php echo $row['img_url']; ?>" alt=""/>
                            </div><div class="train-list_item_column">
                                <div class="train-list_item_text">
                                    <strong>ID:</strong> <?php echo $row['cislo_zkv']; ?>
                                </div>
                                <div class="train-list_item_text">
                                    <strong>Řada:</strong> <?php echo $row['rada']; ?>
                                </div>
                                <div class="train-list_item_text">
                                    <strong>Datum:</strong> <?php echo $row['datum_preznaceni']; ?>
                                </div>
                            </div><div class="train-list_item_column">
                                <div class="train-list_item_text">
                                    <strong>Eko:</strong> <?php echo $row['flag_eko']; ?>
                                </div>
                                <div class="train-list_item_text">
                                    <strong>Maximální rychlost:</strong> <?php echo $row['vmax']; ?>
                                </div>
                                <div class="train-list_item_text">
                                    <strong>Délka přes nárazník:</strong> <?php echo $row['delka']; ?>
                                </div>
                            </div><div class="train-list_item_column ajax-action" data-action="trains-detail_<?php echo $row['cislo_zkv']; ?>">
                                <img src="icons/search.svg" alt="icon"/>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
        </section>
        <?php break;
        case 'add_form':
            $query = $con->query("SHOW COLUMNS from vlak");
            if ($query->num_rows > 0) {
                echo "<section class='trains'><div class='container'><div class='add_form'>";
                while ($row = $query->fetch_assoc()) {
                    $type = $row['Type'];
                    if ($row['Field'] == 'img_url') {
                        echo "<div class='add_form__row'><label for='$row[Field]'>$row[Field]</label><button id='upload_link' data-name='$row[Field]'>Choose File</button></div>";
                    } elseif ($type != 'date') {
                        $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                        $type = substr($type, 0, stripos($type, '('));
                        echo "<div class='add_form__row'><label for='$row[Field]'>$row[Field]</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                    } else {
                        echo "<div class='add_form__row'><label for='$row[Field]'>$row[Field]</label><input type='text' class='datepicker' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                    }
                }
                echo "<button data-action='trains-operation-insert' class='btn-basic btn-only-top center-block ajax-action'>Uložit Data</button>";
                echo "</div></div></section>";
            }
            break;
        case
            'detail':
                echo "<section class=\"trains\"><div class=\"container\">sracka</div></section>";
                break;
                ?>
    <?php endswitch ?>
<?php }
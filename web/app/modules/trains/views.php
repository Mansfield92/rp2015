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
                <button class="btn-actions btn-add ajax-action" data-action="trains-add_form"> Přidat lokomotivu</button>
                <div class="search-row">
                    <input type="text" id="search" class="form-control" placeholder="Zadejte hledané číslo vlaku">
                </div>
                <div class="train-list">
                    <?php $query = "SELECT cislo_zkv, rada, datum_preznaceni, flag_eko, km_probeh_po, vmax, delka,img_url from vlak";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="train-list_item" data-search="<?php echo $row['cislo_zkv']; ?>">
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
                echo "<section>";
                $query = $con->query("SELECT * from vlak WHERE cislo_zkv = '$id'");
                $fieldCount = ($query->field_count);
                $fieldCount = round(($fieldCount-2)/2);
//                $fieldCount = ($fieldCount-2)/2;
                $rows = $con->query("SHOW COLUMNS from vlak");
                if($query->num_rows > 0) {
                    $data = $query->fetch_assoc();
                    echo "<div class='container'><div class='train-header'>ID: $data[cislo_zkv]</div>";
                    echo "<img class='train-detail' alt='train' src='upload_pic/$data[img_url]'><button id='upload_link' data-name='img_url'>Choose File</button><div class='train-description'>";
                    $i = 0;
                    while ($row = $rows->fetch_assoc()) {
                        if ($row['Field'] != 'cislo_zkv' && $row['Field'] != 'img_url') {
//                        echo $i."==".$fieldCount;
                            if ($i++ == $fieldCount) {
                                echo "</div><div class='train-description train-description_right'>";
                            }
                            $key = $row['Field'];
                            if($row['Type'] == 'date'){
                                echo "<div class='train-description_item'><span class='train-label'>$key: </span><span data-name='$row[Field]' class='adminizer datepick'>$data[$key]</span></div>";
                            }else echo "<div class='train-description_item'><span class='train-label'>$key: </span><span data-name='$row[Field]' class='adminizer'>$data[$key]</span></div>";
                        }
                    }
                    echo "</div>";
                    echo '<button class="btn-actions ajax-action" data-action="trains-operation-update" data-id="cislo_zkv=\''.$data['cislo_zkv'].'\'" data-reload="detail_'.$data['cislo_zkv'].'">Uložit</button>';
                    echo '<button class="btn-actions ajax-action" data-action="trains-operation-delete" data-delete="cislo_zkv=\''.$data['cislo_zkv'].'\'" data-reload="list">Smazat</button>';
                    echo "<button class='btn-actions load-page' data-action='trains-list'>Zpět na seznam</button></div>";
                }else{
                    echo "<div class='container'>Záznam neexistuje</div>";
                }
                echo "</section>";
            break;
                ?>
    <?php endswitch ?>
<?php }
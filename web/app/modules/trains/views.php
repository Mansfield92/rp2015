<?php
session_start();
include('../../config/config.db.php');
include('mapping.php');
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
                    <input type="text"  class="form-control search-input" data-search="train-list" placeholder="Zadejte hledané číslo vlaku">
                </div>
                <div class="train-list">
                    <?php $query = "SELECT cislo_zkv, rada, datum_preznaceni, flag_eko, km_probeh_po,pocet_naprav, vmax, delka,img_url from vlak";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="train-list_item" data-search="<?php echo $row['cislo_zkv']; ?>">
                            <div class="train-list_item_column small-column">
                                <img src="upload_pic/<?php echo $row['img_url']; ?>" alt=""/>
                            </div><div class="train-list_item_column big-column">
                                <div class="train-list_item_text">
                                    <strong><?php echo $trainsMap['cislo_zkv'] ?>:</strong> <?php echo $row['cislo_zkv']; ?>
                                </div>
                                <div class="train-list_item_text">
                                    <strong><?php echo $trainsMap['rada'] ?>:</strong> <?php echo $row['rada']; ?>
                                </div>
                                <div class="train-list_item_text">
                                    <strong><?php echo $trainsMap['datum_preznaceni'] ?>:</strong> <?php echo $row['datum_preznaceni']; ?>
                                </div>
                            </div><div class="train-list_item_column big-column">
                                <div class="train-list_item_text">
                                    <strong><?php echo $trainsMap['pocet_naprav'] ?>:</strong> <?php echo $row['pocet_naprav']; ?>
                                </div>
                                <div class="train-list_item_text">
                                    <strong><?php echo $trainsMap['vmax'] ?>:</strong> <?php echo $row['vmax']; ?> km/h
                                </div>
                                <div class="train-list_item_text">
                                    <strong><?php echo $trainsMap['delka'] ?>:</strong> <?php echo $row['delka']; ?> m
                                </div>
                            </div><div class="train-list_item_column ajax-action small-column" data-action="trains-detail_<?php echo $row['cislo_zkv']; ?>">
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
                    $f = $row['Field'];
                    if ($row['Field'] == 'img_url') {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$trainsMap[$row['Field']]."</label><button id='upload_link' data-name='$row[Field]'>Choose File</button></div>";
                    } elseif($row['Field'] == 'depo'){
                        $depos = $con->query("SELECT id, nazev FROM depo");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$trainsMap[$row['Field']]."</label><select id='depo' name='depo'>";
                        while($r = $depos->fetch_assoc()){
                            echo "<option value='$r[id]'>$r[nazev]</option>";
                        }
                        echo "</select></div>";
                    }elseif ($f == 'UIC_OLD' || $f == 'm_stav' || $f == 'flag_eko' || $f == 'vkv' || $f == 'vz' || $f == 'brvaha_p' || $f == 'ele_ohrev') {}
                    elseif ($type != 'date') {
                        $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                        $type = substr($type, 0, stripos($type, '('));
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$trainsMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                    } else {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$trainsMap[$row['Field']]."</label><input type='text' class='datepicker' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
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
                $fieldCount = round(($fieldCount-9)/2);
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
                            if ($i == $fieldCount) {
                                echo "</div><div class='train-description train-description_right'>";
                            }
                            $key = $row['Field'];
                            if($row['Type'] == 'date'){
                                $i++;
                                echo "<div class='train-description_item'><span class='train-label'>$trainsMap[$key]</span><span data-name='$key' class='adminizer datepick'>$data[$key]</span></div>";
                            }elseif ($key == 'UIC_OLD' || $key == 'm_stav' || $key == 'flag_eko' || $key == 'vkv' || $key == 'vz' || $key == 'brvaha_p' || $key == 'ele_ohrev') {}
                            elseif($row['Field'] == 'depo'){
                                $i++;
                                $depos = $con->query("SELECT id, nazev FROM depo");
                                echo "<div class='train-description_item'><span class='train-label'>$trainsMap[$key]: </span><select class='hidden' id='depo' name='depo'>";
                                $val = $data[$key];
                                while($r = $depos->fetch_assoc()){
                                    if($r['id'] == $data[$key]){
                                        echo "<option value='$r[id]' selected>$r[nazev]</option>";
                                        $val = $r['nazev'];
                                    }else{
                                        echo "<option value='$r[id]'>$r[nazev]</option>";
                                    }
                                }
                                echo "</select><span data-name='$key' class='adminizer adminizer-hide'>$val</span></div>";
                            }else {
                                $i++;
                                echo "<div class='train-description_item'><span class='train-label'>$trainsMap[$key]: </span><span data-name='$key' class='adminizer'>$data[$key]</span></div>";
                            }
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
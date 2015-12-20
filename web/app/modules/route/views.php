<?php
session_start();
include('../../config/config.db.php');
include('mapping.php');
if (isset($_SESSION['login_role']) && intval($_SESSION['login_role']) >= 3) {
    $view = $_POST['view'];
    if (strpos($view,'detail') !== false) {
        $id = substr($view, 7);
        $view = 'detail';
    }
    ?>
    <?php switch ($view): ?><?php case 'list': ?>
        <section class="route">
            <div class="container">
                <button class="btn-actions btn-add ajax-action" data-action="route-add_form"> Přidat trasu</button><button
                    class="btn-actions btn-add ajax-action" data-action="station-add_form"> Přidat stanici</button>
                <div class="station-list">
                    <h2>Stanice</h2>
                    <?php
                    $query = "SELECT id,nazev, mesto, stat from stanice";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="station-list_item">
                            <div class="station-list_item_column">
                                <div class="station-list_item_text">
                                    <?php echo $row['nazev']; ?>
                                </div>
                            </div><div class="station-list_item_column">
                                <div class="station-list_item_text">
                                    <?php echo $row['mesto']; ?>
                                </div>
                            </div><div class="station-list_item_column">
                                <div class="station-list_item_text">
                                    <?php echo $row['stat']; ?>
                                </div>
                            </div><div class="station-list_item_column ajax-action" data-action="station-detail_<?php echo $row['id']; ?>">
                                <img src="icons/search.svg" alt="icon"/>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>

                <div class="route-list">
                    <h2>Trasy</h2>
                    <?php
                    $query = "SELECT id,nazev_trasy, delka, vyluka, disabled from trasa";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="route-list_item">
                            <div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['nazev_trasy']; ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['delka']; ?> km
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['vyluka']; ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['disabled']; ?>
                                </div>
                            </div><div class="route-list_item_column ajax-action" data-action="route-detail_<?php echo $row['id']; ?>">
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
            $query = $con->query("SHOW COLUMNS from depo");
            if ($query->num_rows > 0) {
                echo "<section class='trains'><div class='container'><div class='add_form'>";
                while ($row = $query->fetch_assoc()) {
                    $type = $row['Type'];
                    if ($row['Field'] == 'img_url') {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$depoMap[$row['Field']]."</label><button id='upload_link' data-name='$row[Field]'>Choose File</button></div>";
                    } elseif ($type != 'date') {
                        if($row['Field'] == 'id'){
                            $id = 'NULL';
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$depoMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='$id' disabled placeholder='$row[Type]' /></div>";
                        }else {
                            $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                            $type = substr($type, 0, stripos($type, '('));
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$depoMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                        }
                    } else {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$depoMap[$row['Field']]."</label><input type='text' class='datepicker' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                    }
                }
                echo "<button data-action='depo-operation-insert' class='btn-basic btn-only-top center-block ajax-action'>Uložit Data</button>";
                echo "</div></div></section>";
            }
            break;
        case
        'detail':
            echo "<section>";
            $query = $con->query("SELECT * from depo WHERE id = '$id'");
            $rows = $con->query("SHOW COLUMNS from depo");
            if($query->num_rows > 0) {
                $data = $query->fetch_assoc();
                echo "<div class='container'><div class='train-header'>$data[nazev]</div>";
                echo "<img class='train-detail' alt='train' src='upload_pic/$data[img_url]'><button id='upload_link' data-name='img_url'>Choose File</button><div class='train-description'>";

                $trains = $con->query("select count(*) from vlak where depo = $data[id]");
                $trains= $trains->fetch_array();
                $trains = $trains[0];
                $ppl = $con->query("select count(*) from zamestnanec where depo = $data[id]");
                $ppl = $ppl->fetch_array();
                $ppl = $ppl[0];

                $i = 0;
                while ($row = $rows->fetch_assoc()) {
                    if ($row['Field'] != 'img_url' && $row['Field'] != 'id' && $row['Field'] != 'kapacita') {
                        if($i++ == 4){
                            echo "<div class='train-description_item'><span class='train-label'>Počet zaměstnanců: </span><span>$ppl</span></div>";
                            echo "</div><div class='train-description train-description_right'>";
                        }
                        $key = $row['Field'];
                        echo "<div class='train-description_item'><span class='train-label'>$depoMap[$key]: </span><span data-name='$key' class='adminizer'>$data[$key]</span></div>";
                    }
                }
                echo "<div class='train-description_item'><span class='train-label'>Počet lokomotiv: </span><span>$trains</span></div>";
                echo "</div>";
                echo '<button class="btn-actions ajax-action" data-action="depo-operation-update" data-id="id=\''.$data['id'].'\'" data-reload="detail_'.$data['id'].'">Uložit</button>';
                echo '<button class="btn-actions ajax-action" data-action="depo-operation-delete" data-delete="id=\''.$data['id'].'\'" data-reload="list">Smazat</button>';
                echo "<button class='btn-actions load-page' data-action='depo-list'>Zpět na seznam</button></div>";
            }else{
                echo "<div class='container'>Záznam neexistuje</div>";
            }
            echo "</section>";
            break;
            ?>
        <?php endswitch ?>
<?php }
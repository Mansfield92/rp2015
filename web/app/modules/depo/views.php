<?php
session_start();
include('../../config/config.db.php');
include('mapping.php');
    $view = $_POST['view'];
    if (strpos($view,'detail') !== false) {
        $id = substr($view, 7);
        $view = 'detail';
    }
    ?>
    <?php switch ($view): ?><?php case 'list': ?>
        <section class="depos">
            <div class="container">
                <button class="btn-actions btn-add ajax-action" data-action="depo-add_form"> Přidat depo</button>
                <div class="search-row">
                    <input type="text" class="form-control search-input" data-search="depos-list" placeholder="Filtrace podle názvu nebo města">
                </div>
                <div class="depos-list">
                    <div class="depos-list-header">
                        <div class="depos-list-header-item">
                            Obrázek
                        </div><div class="depos-list-header-item">
                            Název depa
                        </div><div class="depos-list-header-item">
                            Město
                        </div><div class="depos-list-header-item">
                            Kapacita vozů
                        </div><div class="depos-list-header-item">
                            Detail
                        </div>
                    </div>
                    <?php
                    $query = "SELECT img_url,id, nazev, mesto, kapacita from depo";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="depos-list_item"  data-search="<?php echo $row['nazev'].$row['mesto']; ?>">
                            <div class="depos-list_item_column">
                                <img src="upload_pic/<?php echo $row['img_url']; ?>" alt=""/>
                            </div><div class="depos-list_item_column">
                                <div class="depos-list_item_text">
                                    <?php echo $row['nazev']; ?>
                                </div>
                            </div><div class="depos-list_item_column">
                                <div class="depos-list_item_text">
                                    <?php echo $row['mesto']; ?>
                                </div>
                            </div><div class="depos-list_item_column">
                                <div class="depos-list_item_text">
                                    <?php echo $row['kapacita']; ?>
                                </div>
                            </div><div class="depos-list_item_column ajax-action" data-action="depo-detail_<?php echo $row['id']; ?>">
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
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$depoMap[$row['Field']]."</label><button id='upload_link' data-name='$row[Field]'>Vybrat obrázek</button></div>";
                    } elseif ($type != 'date') {
                        if($row['Field'] == 'id' ){
//                            $id = 'NULL';
//                            echo "<div class='add_form__row'><label for='$row[Field]'>".$depoMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='$id' disabled placeholder='$row[Type]' /></div>";
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
                echo "<img class='train-detail' alt='train' src='upload_pic/$data[img_url]'><button id='upload_link' data-name='img_url'>Vybrat obrázek</button><div class='train-description'>";

                $trains = $con->query("select count(*) from vlak where depo = $data[id]");
                $trains= $trains->fetch_array();
                $trains = $trains[0];
                $ppl = $con->query("select count(*) from zamestnanec where depo = $data[id]");
                $ppl = $ppl->fetch_array();
                $ppl = $ppl[0];

                $i = 0;
                while ($row = $rows->fetch_assoc()) {
                    if ($row['Field'] != 'img_url' && $row['Field'] != 'id') {
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
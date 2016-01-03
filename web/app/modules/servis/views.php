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
        <section class="servis">
            <div class="container">
                <button class="btn-actions btn-add ajax-action" data-action="servis-add_form"> Zaznamenat kontrolu</button>
                <div class="search-row">
                    <input type="text" class="form-control search-input" data-search="route-list" placeholder="Filtrování technika a vlaku">
                </div>
                <div class="route-list">
                    <div class="route-list-header">
                        <div class="route-list-header-item percent4">
                            Technik
                        </div><div class="route-list-header-item percent4">
                            Číslo vlaku
                        </div><div class="route-list-header-item percent4">
                            Datum kontroly
                        </div><div class="route-list-header-item percent4">
                            Příští kontrola
                        </div><div class="route-list-header-item percent10">
                            Detail
                        </div>
                    </div>
                    <?php
                    $query = "SELECT jmeno, prijmeni ,kontrola.id, cislo_zkv, datum_expirace, kontrola_od from kontrola left join zamestnanec on kontrola.id_user = zamestnanec.id order by datum_expirace DESC";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="route-list_item"  data-search="<?php echo $row['cislo_zkv'].$row['jmeno'].' '.$row['prijmeni']; ?>"><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['jmeno'].' '.$row['prijmeni']; ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['cislo_zkv']; ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['kontrola_od']; ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['datum_expirace']; ?>
                                </div>
                            </div><div class="route-list_item_column ajax-action" data-action="servis-detail_<?php echo $row['id']; ?>">
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
            $query = $con->query("SHOW COLUMNS from kontrola");
            if ($query->num_rows > 0) {
                echo "<section class='trains'><div class='container'><div class='add_form'>";
                while ($row = $query->fetch_assoc()) {
                    $type = $row['Type'];
                    if ($type != 'date') {
                        if($row['Field'] == 'id' ){
//                            $id = 'NULL';
//                            echo "<div class='add_form__row'><label for='$row[Field]'>".$servisMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='$id' disabled placeholder='$row[Type]' /></div>";
                        }elseif ($row['Field'] == 'cislo_zkv') {
                            $depos = $con->query("SELECT cislo_zkv FROM vlak WHERE cislo_zkv not in (select cislo_zkv from kontrola where datum_expirace > CURDATE())");
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$servisMap[$row['Field']]."</label><select id='$row[Field]' name='$row[Field]'>";
                            if($depos->num_rows > 0) {
                                while ($r = $depos->fetch_row()) {
                                    echo "<option value='$r[0]'>$r[0]</option>";
                                }
                            }else{
                                echo "<option value='--'>----</option>";
                            }
                            echo "</select></div>";
                        }elseif ($row['Field'] == 'id_user') {
                            $tech = $con->query("SELECT id FROM zamestnanec WHERE login='$_SESSION[login_name]'");
                            $tech = $tech->fetch_row();
                            echo "<input type='hidden' name='$row[Field]' value='$tech[0]' />";
                        }elseif ($row['Field'] == 'vysledek') {
                            $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                            $type = substr($type, 0, stripos($type, '('));
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$servisMap[$row['Field']]."</label><textarea name='$row[Field]' placeholder='$row[Type]' /></textarea>";
                        }else {
                            $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                            $type = substr($type, 0, stripos($type, '('));
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$servisMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                        }
                    } else {
                        if($row['Field'] != 'kontrola_do') {
                            if($row['Field'] != 'kontrola_od'){
                                echo "<div class='add_form__row'><label for='$row[Field]'>" . $servisMap[$row['Field']] . "</label><input type='text' class='datepicker future' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                            }else echo "<div class='add_form__row'><label for='$row[Field]'>" . $servisMap[$row['Field']] . "</label><input type='text' class='datepicker nofuture' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";

                        }
                    }
                }
                echo "<button data-action='servis-operation-insert' class='btn-basic btn-only-top center-block ajax-action'>Uložit Data</button>";
                echo "</div></div></section>";
            }
            break;
        case
        'detail':
            echo "<section>";
            $q = "SELECT concat(jmeno, ' ', prijmeni) as id_user, cislo_zkv, datum_expirace, kontrola_do, kontrola_od, vysledek, kontrola.id as id  from kontrola left join zamestnanec on zamestnanec.id = kontrola.id_user WHERE kontrola.id = '$id'";
            $query = $con->query($q);
//            echo $q;
            $rows = $con->query("SHOW COLUMNS from kontrola");
            if($query->num_rows > 0) {
                $data = $query->fetch_assoc();
                echo "<div class='container'>";
                echo "<div class='train-description'>";
                $i = 0;
                while ($row = $rows->fetch_assoc()) {
                    if ($row['Field'] != 'id' && $row['Field'] != 'kontrola_do') {
                        if($i++ == 4){
                            echo "</div><div class='train-description train-description_right'>";
                        }
                        $key = $row['Field'];
                        if($row['Field'] == 'vysledek'){
                            echo "<div class='train-description_item'><span class='train-label ontop'>$servisMap[$key]: </span><span class='big-wide' data-name='$key' >$data[$key]</span></div>";
                        }else echo "<div class='train-description_item'><span class='train-label'>$servisMap[$key]: </span><span data-name='$key' >$data[$key]</span></div>";

                    }
                }
                echo "</div>";
                echo '<button class="btn-actions ajax-action" data-action="servis-operation-delete" data-delete="id=\''.$data['id'].'\'" data-reload="list">Smazat</button>';
                echo "<button class='btn-actions load-page' data-action='servis-list'>Zpět na seznam</button></div>";
            }else{
                echo "<div class='container'>Záznam neexistuje</div>";
            }
            echo "</section>";
            break;
            ?>
        <?php endswitch ?>
<?php }
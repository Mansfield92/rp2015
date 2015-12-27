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
                    while ($row = $query->fetch_assoc()) {
                        $disabled = intval($row['disabled']) == 1;
                        $vyluka = "SELECT nazev,dopad from vyluky where id = '$row[vyluka]'";
                        $vyluka = $con->query($vyluka);
                        $vyluka = $vyluka->fetch_row();
                        ?>
                        <div class="route-list_item <?php echo ($disabled || intval($vyluka[1]) == 100) ? 'disabled' : '' ?>">
                            <div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['nazev_trasy']; ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['delka']; ?> km
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text
                                <?php
                                ?>">
                                    <?php
                                        echo $vyluka[0];
                                    ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <a href="#" data-action="<?php echo $row['id']; ?>" class='route-switch'><?php echo $disabled ? "Povolit" : "Zakázat" ?></a>
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
            $query = $con->query("SHOW COLUMNS from trasa");
            if ($query->num_rows > 0) {
                echo "<section class='trains'><div class='container'><div class='add_form'>";
                while ($row = $query->fetch_assoc()) {
                    $type = $row['Type'];
                    if ($row['Field'] == 'stanice1') {
                        $depos = $con->query("SELECT id, nazev FROM stanice");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$row['Field']."</label><select id='stanice1' name='".$row['Field']."'>";
                        while($r = $depos->fetch_row()){
                            echo "<option value='$r[0]'>$r[1]</option>";
                        }
                        echo "</select></div>";
                    } elseif ($row['Field'] == 'stanice2') {
                        $depos = $con->query("SELECT id, nazev FROM stanice");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$row['Field']."</label><select id='stanice2' name='".$row['Field']."'>";
                        while($r = $depos->fetch_row()){
                            echo "<option value='$r[0]'>$r[1]</option>";
                        }
                        echo "</select></div>";
                    } elseif ($row['Field'] == 'vyluka') {
                        $depos = $con->query("SELECT id, nazev, dopad FROM vyluky order by dopad asc");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$row['Field']."</label><select id='vyluky' name='".$row['Field']."'>";
                        while($r = $depos->fetch_row()){
                            echo "<option value='$r[0]'>$r[1]</option>";
                        }
                        echo "</select></div>";
                    } elseif ($row['Field'] == 'disabled') {
                    }elseif ($type != 'date') {
                        if($row['Field'] == 'id'){
                            $id = 'NULL';
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$routeMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='$id' disabled placeholder='$row[Type]' /></div>";
                        }else {
                            $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                            $type = substr($type, 0, stripos($type, '('));
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$routeMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                        }
                    } else {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$routeMap[$row['Field']]."</label><input type='text' class='datepicker' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                    }
                }
                echo "<button data-action='route-operation-insert' data-validate='notSame,stanice1+stanice2,Stanice nesmi byt shodne.' class='btn-basic btn-only-top center-block ajax-action'>Uložit Data</button>";
                echo "</div></div></section>";
            }
            break;
        case
        'detail':
            echo "<section>";
            $query = $con->query("SELECT * from trasa WHERE id = '$id'");
            $rows = $con->query("SHOW COLUMNS from trasa");
            if($query->num_rows > 0) {
                $data = $query->fetch_assoc();
                echo "<div class='container'><div class='station-header'>$data[nazev_trasy]</div>";
                echo "<div class='train-description'>";

                $i = 0;
                while ($row = $rows->fetch_assoc()) {
                    if ($row['Field'] != 'img_url' && $row['Field'] != 'id' && $row['Field'] != 'kapacita') {
                        if($i++ == 3){
                            echo "</div><div class='train-description train-description_right'>";
                        }
                        $key = $row['Field'];

                        if($key == 'stanice1' || $key == 'stanice2'){
                            $depos = $con->query("SELECT id, nazev FROM stanice");
                            echo "<div class='train-description_item'><span class='train-label'>$routeMap[$key]: </span><select class='hidden' id='$key' name='$key'>";
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
                        }elseif($key == "vyluka"){
                            $depos = $con->query("SELECT id, nazev, dopad FROM vyluky order by dopad asc");
                            echo "<div class='train-description_item'><span class='train-label'>$routeMap[$key]: </span><select class='hidden' id='$key' name='$key'>";
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
                        }elseif ($row['Field'] == 'disabled') {
                            echo "<div class='train-description_item'><span class='train-label'>$routeMap[$key]: </span><select class='hidden' id='$key' name='$key'>";
                            echo "<option value='0' ".($data[$key] == '1' ? '' : 'selected').">Ano</option>";
                            echo "<option value='1' ".($data[$key] == '0' ? '' : 'selected').">Ne</option>";
                            echo "</select><span data-name='$key' class='adminizer adminizer-hide'>".($data[$key] == '0' ? 'Ne' : 'Ano')."</span></div>";

                        }
                        else {
                            echo "<div class='train-description_item'><span class='train-label'>$routeMap[$key]: </span><span data-name='$key' class='adminizer'>$data[$key]</span></div>";
                        }
                    }
                }
                echo "</div>";
                echo '<button class="btn-actions ajax-action" data-action="route-operation-update" data-id="id=\''.$data['id'].'\'" data-reload="detail_'.$data['id'].'">Uložit</button>';
                echo '<button class="btn-actions ajax-action" data-action="route-operation-delete" data-delete="id=\''.$data['id'].'\'" data-reload="list">Smazat</button>';
                echo "<button class='btn-actions load-page' data-action='route-list'>Zpět na seznam</button></div>";
            }else{
                echo "<div class='container'>Záznam neexistuje</div>";
            }
            echo "</section>";
            break;
            ?>
        <?php endswitch ?>
<?php }
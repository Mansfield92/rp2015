<?php
session_start();
include('../../config/config.db.php');
include('mapping.php');
$role = intval($_SESSION['login_role']);

if (isset($_SESSION['login_role']) && intval($_SESSION['login_role']) >= 1) {
    $view = $_POST['view'];
    if (strpos($view,'detail') !== false) {
        $id = substr($view, 7);
        $view = 'detail';
    }
    ?>
    <?php switch ($view): ?><?php case 'list': ?>
        <section class="plans">
            <div class="container">
                <?php if($role == 1 || $role > 4){?><button class="btn-actions btn-add ajax-action" data-action="plans-add_form"> Naplánovat výkon</button>
                    <button class="btn-actions btn-add export-xml" onclick="window.location.replace('app/export/export.php?id=all&extra=alive')" data-action="export-all"> Exportovat aktivní výkony</button>
                <button class="btn-actions btn-add export-xml" onclick="window.location.replace('app/export/export.php?id=all&extra=dead')" data-action="export-all"> Exportovat ukončené výkony</button>
                <?php } ?>
                <div class="route-list">
                    <?php
                    $query = "SELECT id_ukon, stav, pocet_vagonu, cas, cislo_zkv, jmeno, prijmeni from ukony left join zamestnanec on ukony.id_user = zamestnanec.id where stav < 5";

                    $userID = "SELECT id FROM `zamestnanec` WHERE login = '$_SESSION[login_name]'";
                    $userID = $con->query($userID);
                    $userID = $userID->fetch_row();

                    if($role == 3){
                        $query.= " AND id_user = $userID[0]";
                    }

                    $query = $con->query($query);
                    $flag = false;
                    if($query->num_rows > 0){$flag = true; ?>
                        <h2>Aktivní výkony</h2>
                        <div class="station-list-header">
                            <div class="station-list-header-item percent4">
                                Vlak
                            </div><div class="station-list-header-item percent4">
                                Strojvůdce
                            </div><div class="station-list-header-item percent4">
                                Doba trvání
                            </div><div class="station-list-header-item percent4">
                                Stav vlaku
                            </div><div class="station-list-header-item percent10">
                                Detail
                            </div>
                        </div>
                        <?php
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="route-list_item">
                            <div class="route-list_item_column">
                                <div class="route-list_item_text no-padding">
<!--                                    --><?php
                                    $states = "SELECT rada from vlak where cislo_zkv = '$row[cislo_zkv]'";
                                    $states = $con->query($states);
                                    $state = $states->fetch_row();
                                    echo $row['cislo_zkv'].' ('.$state[0].')';
//                                    ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php echo $row['jmeno'].' '.$row['prijmeni']; ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text">
                                    <?php
                                    $time = intval($row['cas']);
                                    $hours = floor($time / 60);
                                    $minutes = ($time % 60);

                                    echo $hours.'h'.($minutes > 0 ? ' '.$minutes.'m' : '');
                                    ?>
                                </div>
                            </div><div class="route-list_item_column">
                                <div class="route-list_item_text no-padding">
                                    <?php
                                        $states = 'SELECT * from stavy';
                                        $states = $con->query($states);
                                        echo '<select class="change_state" data-train="'.$row['cislo_zkv'].'" data-id="'.$row['id_ukon'].'">';
                                        while($state = $states->fetch_assoc()){
                                            if(($role == 3 && $state['id_stav'] == 2 ||  $state['id_stav']  == 3) || ($role == 1 || $role > 4)) {
                                                echo "<option value='$state[id_stav]' " . ($row['stav'] == $state['id_stav'] ? 'selected' : '') . ">$state[nazev]</option>";
                                            }elseif($role == 3 && $row['stav'] == $state['id_stav']){
                                                echo "<option value='$state[id_stav]' " . ($row['stav'] == $state['id_stav'] ? 'selected' : '') . ">$state[nazev]</option>";
                                            }
                                        }
                                        echo "</select>";
                                    ?>
                                </div>
                            </div><div class="route-list_item_column ajax-action" data-action="plans-detail_<?php echo $row['id_ukon']; ?>">
                                <img src="icons/search.svg" alt="icon"/>
                            </div>
                        </div>
                        <?php
                    } }else{
                        echo $role == 3 ? '<p>Zadne naplanovane ukony</p>' : '';
                    }?>
                    <?php
//                    if($role == 1 || $role > 4){

                    $userID = "SELECT id FROM `zamestnanec` WHERE login = '$_SESSION[login_name]'";
                    $userID = $con->query($userID);
                    $userID = $userID->fetch_row();
                    $query = "SELECT id_ukon, stav, pocet_vagonu, cas, cislo_zkv, jmeno, prijmeni from ukony left join zamestnanec on ukony.id_user = zamestnanec.id where stav >= 5";
                    $query.= $role == 3 ? ' AND id_user = '.$userID[0] : '';
                    $query = $con->query($query);
                    if($query->num_rows > 0){
                        echo '</div><div class="station-list '.($flag ? 'margin-top50' : '').'">';?>

                        <h2>Ukončené výkony</h2>
                        <?php if($role != 3){?>
                        <div class="search-row">
                            <input type="text" placeholder="Filtrace podle čísla vlaku a zaměstance" data-search="station-list" class="form-control search-input">
                        </div>
                            <?php }?>
                        <div class="station-list-header">
                            <div class="station-list-header-item percent30">
                                Vlak
                            </div><div class="station-list-header-item percent30">
                                Strojvůdce
                            </div><div class="station-list-header-item percent30">
                                Doba trvání
                            </div><div class="station-list-header-item percent10">
                                Detail
                            </div>
                        </div>
                        <?php
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="station-list_item <?php echo (intval($row['stav']) == 5 ? 'plan_canceled' : '') ?>" data-search="<?php echo $row['cislo_zkv'].$row['jmeno'].' '.$row['prijmeni']; ?>">
                            <div class="station-list_item_column">
                                <div class="station-list_item_text no-padding">
                                    <?php
                                    $states = "SELECT rada from vlak where cislo_zkv = '$row[cislo_zkv]'";
                                    $states = $con->query($states);
                                    $state = $states->fetch_row();
                                    echo $row['cislo_zkv'].' ('.$state[0].')';
                                    ?>
                                </div>
                            </div><div class="station-list_item_column">
                                <div class="station-list_item_text">
                                    <?php echo $row['jmeno'].' '.$row['prijmeni']; ?>
                                </div>
                            </div><div class="station-list_item_column">
                                <div class="station-list_item_text">
                                    <?php
                                    $time = intval($row['cas']);
                                    $hours = floor($time / 60);
                                    $minutes = ($time % 60);

                                    echo $hours.'h'.($minutes > 0 ? ' '.$minutes.'m' : '');
                                    ?>
                                </div>
                            </div><div class="station-list_item_column ajax-action" data-action="plans-detail_<?php echo $row['id_ukon']; ?>">
                                <img src="icons/search.svg" alt="icon"/>
                            </div>
                        </div>
                        <?php
                    }
//                    }
    }?>
                </div>
            </div>
        </section>
        <?php break;
        case 'add_form':
            $query = $con->query("SHOW COLUMNS from ukony");
            if ($query->num_rows > 0) {
                echo "<section class='plans'><div class='container'><div class='add_form'>";
                while ($row = $query->fetch_assoc()) {
                    $type = $row['Type'];
                    if($row['Field'] == 'id_ukon' || $row['Field'] == 'finished'){
                    }elseif ($row['Field'] == 'cislo_zkv') {
                        $depos = $con->query("SELECT cislo_zkv FROM vlak WHERE cislo_zkv not in (select cislo_zkv from ukony where stav < 5)");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$plansMap[$row['Field']]."</label><select id='$row[Field]' name='$row[Field]'>";
                        if($depos->num_rows > 0) {
                            while ($r = $depos->fetch_row()) {
                                echo "<option value='$r[0]'>$r[0]</option>";
                            }
                        }else{
                            echo "<option value='--'>----</option>";
                        }
                        echo "</select></div>";
                    } elseif ($row['Field'] == 'id_user') {
                        $depos = $con->query("SELECT id, jmeno, prijmeni FROM zamestnanec WHERE role = '3' AND id not in (select id_user from ukony where stav < 5)");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$plansMap[$row['Field']]."</label><select id='$row[Field]' name='$row[Field]'>";
                        if($depos->num_rows < 1){
                            echo "<option value='--'>----</option>";
                        }
                        else {
                            while($r = $depos->fetch_row()){
                                echo "<option value='$r[0]'>$r[1] $r[2]</option>";
                            }
                        }
                        echo "</select></div>";
                    } elseif ($row['Field'] == 'id_trasa') {
                        $depos = $con->query("SELECT id, nazev_trasy, vyluka, delka FROM trasa WHERE disabled != '1' AND vyluka != '5'");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$plansMap[$row['Field']]."</label><select id='$row[Field]' name='$row[Field]'>";
                        if($depos->num_rows > 0){
                            while($r = $depos->fetch_row()){
                                $vyluka = $con->query("Select nazev from vyluky where id = $r[2] AND id != 6");
                                $rr = $vyluka->fetch_row();

                                echo "<option value='$r[0]'>$r[1]          ".($rr[0] ? '('.$rr[0].')' : '').", Vzdálenost: $r[3] km</option>";
                            }
                        }else{
                            echo "<option value='--'>----</option>";
                        }
                        echo "</select></div>";
                    } elseif ($row['Field'] == 'stav') {
                    } elseif ($type != 'date') {
                        if($row['Field'] == 'id'){
                            $id = 'NULL';
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$plansMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='$id' disabled placeholder='$row[Type]' /></div>";
                        }else {
                            $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                            $type = substr($type, 0, stripos($type, '('));
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$plansMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                        }
                    } else {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$plansMap[$row['Field']]."</label><input type='text' class='datepicker' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                    }
                }
                echo "<button data-action='plans-operation-insert'  data-validate='notEmpty,id_user,Vyberte nebo vytvořte strojvůdce.
                |notEmpty,id_trasa,Vyberte nebo vytvořte trasu.
                |notEmpty,cislo_zkv,Vyberte nebo vytvořte vlak.
                |isNum,pocet_vagonu,Vyplňte počet vagónů.
                |isNum,cas,Vyplňte předpokládaný čas v minutách.' class='btn-basic btn-only-top center-block ajax-action'>Naplánovat úkon</button>";
                echo "</div></div></section>";
            }
            break;
        case
        'detail':
            echo "<section class='detail'>";
            $query = $con->query("SELECT * from ukony WHERE id_ukon = '$id'");
            $rows = $con->query("SHOW COLUMNS from ukony");
            if($query->num_rows > 0) {
                $data = $query->fetch_assoc();
                echo "<div class='container'>";
                echo "<div class='train-description'>";

                $i = 0;
                while ($row = $rows->fetch_assoc()) {
                    if ($row['Field'] != 'img_url' && $row['Field'] != 'id' && $row['Field'] != 'kapacita') {
                        if($i++ == 4){
                            echo "</div><div class='train-description train-description_right'>";
                        }
                        $key = $row['Field'];

                        if ($row['Field'] == 'disabled') {
                            echo "<div class='train-description_item'><span class='train-label'>$plansMap[$key]: </span><select class='hidden' id='$key' name='$key'>";
                            echo "<option value='0' ".($data[$key] == '1' ? '' : 'selected').">Ano</option>";
                            echo "<option value='1' ".($data[$key] == '0' ? '' : 'selected').">Ne</option>";
                            echo "</select><span data-name='$key' class='adminizer adminizer-hide'>".($data[$key] == '0' ? 'Ne' : 'Ano')."</span></div>";
                        }elseif ($row['Field'] == 'id_user') {
                            $users = $con->query("SELECT jmeno, prijmeni FROM zamestnanec WHERE id = '$data[id_user]'");
                            $user = $users->fetch_row();
                            echo "<div class='train-description_item'><span class='train-label'>$plansMap[$key]: </span><span data-name='$key' >$user[0] $user[1]</span></div>";
                        }elseif ($row['Field'] == 'id_ukon' || $row['Field'] == 'finished') {
                        }elseif ($row['Field'] == 'id_trasa') {
                            $routes = $con->query("SELECT trasa.id, nazev_trasy, vyluky.nazev, delka FROM trasa left join vyluky on trasa.vyluka = vyluky.id WHERE disabled != '1' AND vyluka != '5' AND trasa.id = $data[id_trasa]");
                            $rr = $routes->fetch_row();
                            echo "<div class='train-description_item'><span class='train-label'>$plansMap[$key]: </span><span data-name='$key' >$rr[1]          ".($rr[2] ? '('.$rr[2].')' : '').", Vzdálenost: $rr[3] km</span></div>";
                        }elseif ($row['Field'] == 'stav') {
                            $states = $con->query("SELECT * FROM stavy WHERE id_stav = $data[stav]");
                            $s = $states->fetch_row();
                            $states = 'SELECT * from stavy';
                            $states = $con->query($states);
                            if(intval($data['stav']) < 5) {
                                echo '<div class=\'train-description_item\'><span class=\'train-label\'>' . $plansMap[$key] . ': </span><select class="change_state hidden" data-id="' . $data['id_ukon'] . '" data-train="'.$data['cislo_zkv'].'" >';

                                while($state = $states->fetch_assoc()){
                                    if(($role == 3 && $state['id_stav'] == 2 ||  $state['id_stav']  == 3) || ($role == 1 || $role > 4)) {
//                                        if(!($role == 3 && $state['id_stav'] != 1 && $row['stav'] == 1)){
                                        echo "<option value='$state[id_stav]' " . ($data['stav'] == $state['id_stav'] ? 'selected' : '') . ">$state[nazev]</option>";
//                                            }
                                    }elseif($role == 3 && $data['stav'] == $state['id_stav']){
                                        echo "<option value='$state[id_stav]' " . ($data['stav'] == $state['id_stav'] ? 'selected' : '') . ">$state[nazev]</option>";
                                    }
                                }
                                echo "</select><span data-name='$key' class='adminizer adminizer-hide'>$s[1]</span></div>";
                            }else {
                                echo "<div class='train-description_item'><span class='train-label'>$plansMap[$key]: </span><span data-name='$key' >$s[1]</span></div>";
                            }

                        }else {
                            echo "<div class='train-description_item'><span class='train-label'>$plansMap[$key]: </span><span data-name='$key' >$data[$key]</span></div>";
                        }
                    }
                }
                echo "</div>";
//                echo '<button class="btn-actions ajax-action" data-action="plans-operation-update" data-id="id=\''.$data['id_ukon'].'\'" data-reload="detail_'.$data['id_ukon'].'">Uložit</button>';
                if($role == 1 || $role > 4) {
                    echo '<button class="btn-actions" onclick="window.location.replace(\'app/export/export.php?id=' . $data['id_ukon'] . '\')" data-action="export-all"> Exportovat výkon</button>';
                    echo '<button class="btn-actions ajax-action" data-action="plans-operation-delete" data-delete="id_ukon=\'' . $data['id_ukon'] . '\'" data-reload="list">Smazat</button>';
                }
                echo "<button class='btn-actions load-page' data-action='plans-list'>Zpět na seznam</button></div>";
            }else{
                echo "<div class='container'>Záznam neexistuje</div>";
            }
            echo "</section>";
            break;
            ?>
        <?php endswitch ?>
<?php }
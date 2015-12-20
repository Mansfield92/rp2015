<?php
session_start();
include('../../config/config.db.php');
include('../../config/config.roles.php');
include('mapping.php');

if (isset($_SESSION['login_role']) && intval($_SESSION['login_role']) > 2) {
    $view = $_POST['view'];
    if (strpos($view,'detail') !== false) {
        $id = substr($view, 7);
        $view = 'detail';
    }
    ?>
    <?php switch ($view): ?><?php case 'list': ?>
        <section class="users">
            <div class="container">
                <button class="btn-actions btn-add ajax-action" data-action="users-add_form"> Přidat zaměstnance</button>
                <div class="search-row">
                    <input type="text" id="search" class="form-control" placeholder="Vyhledávání podle jména a příjmení">
                </div>
                <div class="users-list">
                    <?php $query = "SELECT img_url,id, jmeno, prijmeni, smlouva_od, role, email from zamestnanec where login != 'admin'";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="users-list_item" data-search="<?php echo $row['jmeno'].' '.$row['prijmeni']; ?>">
                            <div class="users-list_item_column">
                                <div class="users-list_item_text">
                                    <?php echo $row['jmeno']." ".$row['prijmeni']; ?>
                                </div>
                            </div><div class="users-list_item_column">
                                <div class="users-list_item_text">
                                    <?php echo $row['email'] ?>
                                </div>
                            </div><div class="users-list_item_column">
                                <div class="users-list_item_text">
                                    <?php echo $roles[$row['role']]; ?>
                                </div>
                            </div><div class="users-list_item_column ajax-action" data-action="users-detail_<?php echo $row['id']; ?>">
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
            $query = $con->query("SHOW COLUMNS from zamestnanec");
            if ($query->num_rows > 0) {
                echo "<section class='trains'><div class='container'><div class='add_form'>";
                while ($row = $query->fetch_assoc()) {
                    $type = $row['Type'];
                    if ($row['Field'] == 'img_url') {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$usersMap[$row['Field']]."</label><button id='upload_link' data-name='$row[Field]'>Choose File</button></div>";
                    }
                    elseif($row['Field'] == 'depo'){
                        $depos = $con->query("SELECT id, nazev FROM depo");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$usersMap[$row['Field']]."</label><select id='depo' name='depo'>";
                        while($r = $depos->fetch_assoc()){
                            echo "<option value='$r[id]'>$r[nazev]</option>";
                        }
                        echo "</select></div>";
                    }
                    elseif($row['Field'] == 'role'){
                        $depos = $con->query("SELECT id, nazev FROM role where id != '69'");
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$usersMap[$row['Field']]."</label><select id='role' name='role'>";
                        while($r = $depos->fetch_assoc()){
                            echo "<option value='$r[id]'>$r[nazev]</option>";
                        }
                        echo "</select></div>";
                    }
                    elseif($row['Field'] == 'password'){

                        $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                        $type = substr($type, 0, stripos($type, '('));
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$usersMap[$row['Field']]."</label><input type='password' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";                    }
                    elseif ($type != 'date') {
                        if($row['Field'] == 'id'){
                            $id = 'NULL';
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$usersMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='$id' disabled placeholder='$row[Type]' /></div>";
                        }else{
                            $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                            $type = substr($type, 0, stripos($type, '('));
                            echo "<div class='add_form__row'><label for='$row[Field]'>".$usersMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                        }
                    } else {
                        echo "<div class='add_form__row'><label for='$row[Field]'>".$usersMap[$row['Field']]."</label><input type='text' class='datepicker' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                    }
                }
                echo "<button data-action='users-operation-insert' class='btn-basic btn-only-top center-block ajax-action'>Uložit Data</button>";
                echo "</div></div></section>";
            }
            break;
        case
            'detail':
                echo "<section>";
                $query = $con->query("SELECT * from zamestnanec WHERE id = '$id'");
                $fieldCount = ($query->field_count);
                $fieldCount = round(($fieldCount-2)/2);
                $rows = $con->query("SHOW COLUMNS from zamestnanec");
                if($query->num_rows > 0) {
                    $data = $query->fetch_assoc();
                    echo "<div class='container'>";
                    echo "<img class='train-detail user-detail' alt='User' src='upload_pic/$data[img_url]'><button id='upload_link' data-name='img_url'>Choose File</button><div class='train-description'>";
                    $i = 0;
                    while ($row = $rows->fetch_assoc()) {
                        if ($row['Field'] != 'id' && $row['Field'] != 'img_url') {
                            if ($i++ == $fieldCount) {
                                echo "</div><div class='train-description train-description_right'>";
                            }
                            $key = $row['Field'];
                            if($row['Type'] == 'date'){
                                echo "<div class='train-description_item'><span class='train-label'>$key: </span><span data-name='$row[Field]' class='adminizer datepick'>$data[$key]</span></div>";
                            }
                            elseif($row['Field'] == 'depo'){
                                $depos = $con->query("SELECT id, nazev FROM depo");
                                echo "<div class='train-description_item'><span class='train-label'>$usersMap[$key]: </span><select class='hidden' id='depo' name='depo'>";
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
                            }
                            elseif($row['Field'] == 'role'){
                                $roles = $con->query("SELECT id, nazev FROM role where id != '69'");
                                echo "<div class='train-description_item'><span class='train-label'>$usersMap[$key]: </span><select class='hidden' id='role' name='role'>";
                                $val = $data[$key];
                                while($r = $roles->fetch_assoc()){
                                    if($r['id'] == $data[$key]){
                                        echo "<option value='$r[id]' selected>$r[nazev]</option>";
                                        $val = $r['nazev'];
                                    }else{
                                        echo "<option value='$r[id]'>$r[nazev]</option>";
                                    }
                                }
                                echo "</select><span data-name='$key' class='adminizer adminizer-hide'>$val</span></div>";
                            }else echo "<div class='train-description_item'><span class='train-label'>$key: </span><span data-name='$row[Field]' class='adminizer'>$data[$key]</span></div>";
                        }
                    }
                    echo "</div>";
                    echo '<button class="btn-actions ajax-action" data-action="users-operation-update" data-id="id=\''.$data['id'].'\'" data-reload="detail_'.$data['id'].'">Uložit</button>';
                    echo '<button class="btn-actions ajax-action" data-action="users-operation-delete" data-delete="id=\''.$data['id'].'\'" data-reload="list">Smazat</button>';
                    echo "<button class='btn-actions load-page' data-action='users-list'>Zpět na seznam</button></div>";
                }else{
                    echo "<div class='container'>Záznam neexistuje</div>";
                }
                echo "</section>";
            break;
                ?>
    <?php endswitch ?>
<?php }
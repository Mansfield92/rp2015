<?php
session_start();
include('../../config/config.db.php');
include('mapping.php');
if (isset($_SESSION['login_role']) && intval($_SESSION['login_role']) >= 1) {
    $view = $_POST['view'];
    if (strpos($view,'detail') !== false) {
        $id = substr($view, 7);
        $view = 'detail';
    }
    ?>
    <?php switch ($view): ?><?php
        case 'add_form':
            $query = $con->query("SHOW COLUMNS from stanice");
            if ($query->num_rows > 0) {
                echo "<section class='station'><div class='container'><div class='add_form'>";
                while ($row = $query->fetch_assoc()) {
                    $type = $row['Type'];
                    if($row['Field'] == 'id') {
                        $id = 'NULL';
//                        echo "<div class='add_form__row'><label for='$row[Field]'>" . $stationMap[$row['Field']] . "</label><input type='text' name='$row[Field]' value='$id' disabled placeholder='$row[Type]' /></div>";
                    }else echo "<div class='add_form__row'><label for='$row[Field]'>".$stationMap[$row['Field']]."</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                }
                echo "<button data-action='station-operation-insert' class='btn-basic btn-only-top center-block ajax-action'>Uložit Data</button>";
                echo "</div></div></section>";
            }
            break;
        case
        'detail':
            echo "<section>";
            $query = $con->query("SELECT * from stanice WHERE id = '$id'");
            $rows = $con->query("SHOW COLUMNS from stanice");
            if($query->num_rows > 0) {
                $data = $query->fetch_assoc();
                echo "<div class='container'><div class='station-header'>$data[nazev]</div>";
                echo "<div class='train-description'>";


                $i = 0;
                while ($row = $rows->fetch_assoc()) {
                    if ($row['Field'] != 'img_url' && $row['Field'] != 'id' && $row['Field'] != 'kapacita') {
                        if($i++ == 3){
                            echo "</div><div class='train-description train-description_right'>";
                        }
                        $key = $row['Field'];
                        echo "<div class='train-description_item'><span class='train-label'>$stationMap[$key]: </span><span data-name='$key' class='adminizer'>$data[$key]</span></div>";
                    }
                }
                echo "</div>";
                echo '<button class="btn-actions ajax-action" data-action="station-operation-update" data-id="id=\''.$data['id'].'\'" data-reload="detail_'.$data['id'].'">Uložit</button>';
                echo '<button class="btn-actions ajax-action" data-action="station-operation-delete" data-delete="id=\''.$data['id'].'\'" data-reload="#route-list">Smazat</button>';
                echo "<button class='btn-actions load-page' data-action='route-list'>Zpět na seznam</button></div>";
            }else{
                echo "<div class='container'>Záznam neexistuje</div>";
            }
            echo "</section>";
            break;
            ?>
        <?php endswitch ?>
<?php }
<?php
    session_start();
    include('../../config/config.db.php');
    if(isset($_SESSION['login_role']) && intval($_SESSION['login_role']) > 2){
        switch($_POST['view']){
            case 'list':
                ?>
                <section class="trains">
<!--                    <div class="container container-back">-->
<!--                        <button class="btn-back btn-basic" onclick="window.history.back();$App.init();">Zpět</button>-->
<!--                    </div>-->
                    <div class="container">
                        <button class="btn-actions btn-add ajax-action" data-action="trains-add_form"> Přidat nový</button>
                        <div class="search-row">
                            <input type="text" id="search" class="form-control" placeholder="Zadejte hledané číslo vlaku">
                        </div>
                        <div class="train-list">
                            <div class="train-list_item">
                                <div class="train-list_item_column">
                                    <img src="img/train-reference.jpg" alt="" />
                                </div><div class="train-list_item_column">
                                    <div class="train-list_item_text">
                                        <strong>ID:</strong> 164968395047
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Rok výroby:</strong> 1978
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Výrobce:</strong> ŠKODA Plzeň
                                    </div>
                                </div><div class="train-list_item_column">
                                    <div class="train-list_item_text">
                                        <strong>Typ VZ:</strong> 150, 151 | E 499.2
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Maximální rychlost:</strong> 140-160 km/h
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Délka přes nárazník:</strong> 16 740 mm
                                    </div>
                                </div><div class="train-list_item_column">
                                    <img src="icons/search.svg" alt="icon" />
                                </div>
                            </div>
                            <div class="train-list_item">
                                <div class="train-list_item_column">
                                    <img src="img/train-reference.jpg" alt="" />
                                </div><div class="train-list_item_column">
                                    <div class="train-list_item_text">
                                        <strong>ID:</strong> 164968395047
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Rok výroby:</strong> 1978
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Výrobce:</strong> ŠKODA Plzeň
                                    </div>
                                </div><div class="train-list_item_column">
                                    <div class="train-list_item_text">
                                        <strong>Typ VZ:</strong> 150, 151 | E 499.2
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Maximální rychlost:</strong> 140-160 km/h
                                    </div>
                                    <div class="train-list_item_text">
                                        <strong>Délka přes nárazník:</strong> 16 740 mm
                                    </div>
                                </div><div class="train-list_item_column">
                                    <img src="icons/search.svg" alt="icon" />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <?php
                break;
            case 'detail':
                break;
            case 'add_form':
                $query = $con->query("SHOW COLUMNS from vlak");
                if($query->num_rows > 0){
                    echo "<section class='trains'><div class='container'><div class='add_form'>";
                    while ($row = $query->fetch_assoc()) {
                        $type = $row['Type'];
                        if($row['Field'] == 'img_url'){
                            echo "<div class='add_form__row'><label for='$row[Field]'>$row[Field]</label><button id='upload_link' data-name='$row[Field]'>Choose File</button></div>";
                        }elseif($type != 'date') {
                            $size = substr($type, stripos($type, '(') + 1, (stripos($type, ')') - stripos($type, '(')) - 1);
                            $type = substr($type, 0, stripos($type, '('));
//                            echo "<div class='add_form__row'><label for='$row[Field]'>$row[Field]</label><input type='text' name='$row[Field]' value='' placeholder='$row[Field]' /></div>";
                            echo "<div class='add_form__row'><label for='$row[Field]'>$row[Field]</label><input type='text' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                        }else{
                            echo "<div class='add_form__row'><label for='$row[Field]'>$row[Field]</label><input type='text' class='datepicker' name='$row[Field]' value='' placeholder='$row[Type]' /></div>";
                        }
                    }
                    echo "<button data-action='trains-operation-insert' class='btn-basic btn-only-top center-block ajax-action'>Uložit Data</button>";
                    echo "</div></div></section>";
                }
                break;
        }
    }
?>
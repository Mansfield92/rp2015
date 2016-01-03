<?php
session_start();
include('../../config/config.db.php');
if (isset($_SESSION['login_role']) && intval($_SESSION['login_role']) > 2) {?>

        <section class="servis">
            <div class="container">

                <?php

                $km = "select sum(delka) from ukony left join trasa on ukony.id_trasa = trasa.id where ukony.finished >= DATE_SUB(NOW(),INTERVAL 1 YEAR) and ukony.stav = 6";
                $km = $con->query($km);
                $km = $km->fetch_row();
                $km = $km[0];

                $kmAll = "select sum(delka) from ukony left join trasa on ukony.id_trasa = trasa.id where ukony.stav = 6 AND ukony.finished < DATE_SUB(NOW(),INTERVAL 1 YEAR)";
                $kmAll= $con->query($kmAll);
                $kmAll= $kmAll->fetch_row();
                $kmAll = $kmAll[0];

                $events = "select count(*) from ukony where ukony.stav = 6 AND ukony.finished >= DATE_SUB(NOW(),INTERVAL 1 YEAR)";
                $events= $con->query($events);
                $events= $events->fetch_row();
                $events = $events[0];

                $eventsAll = "select count(*) from ukony where ukony.stav = 6 AND ukony.finished < DATE_SUB(NOW(),INTERVAL 1 YEAR)";
                $eventsAll= $con->query($eventsAll);
                $eventsAll= $eventsAll->fetch_row();
                $eventsAll = $eventsAll[0];

                $servis = "select count(*) from kontrola where kontrola_od >= DATE_SUB(NOW(),INTERVAL 1 YEAR)";
                $servis= $con->query($servis);
                $servis= $servis->fetch_row();
                $servis = $servis[0];
//                $servis = 1;

                $servisAll = "select count(*) from kontrola where kontrola_od < DATE_SUB(NOW(),INTERVAL 1 YEAR)";
                $servisAll= $con->query($servisAll);
                $servisAll= $servisAll->fetch_row();
                $servisAll = $servisAll[0];

                echo "<div class='graph' id='graph-km' data-val1='$km' data-val2='$kmAll'></div>";
                echo "<div class='graph' id='graph-event' data-val1='$events' data-val2='$eventsAll'></div>";
                echo "<div class='graph' id='graph-servis' data-val1='$servis' data-val2='$servisAll'></div>";

                ?>

                <div class="search-row">
                    <input type="text" class="form-control search-input" data-search="report-list" placeholder="Filtrace podle čísla vlaku">
                </div>
                <div class="report-list">
                    <div class="report-list-header">
                        <div class="report-list-header-item size33">
                            Číslo vlaku
                        </div><div class="report-list-header-item size33">
                            Datum kontroly
                        </div><div class="report-list-header-item size33">
                            Příští kontrola
                        </div>
                    </div>
                    <?php
                    $query = "SELECT cislo_zkv, datum_expirace, kontrola_od from kontrola left join zamestnanec on kontrola.id_user = zamestnanec.id  group by cislo_zkv order by datum_expirace ASC";
                    $query = $con->query($query);
                    while ($row = $query->fetch_assoc()) { ?>
                        <div class="report-list_item"  data-search="<?php echo $row['cislo_zkv'] ?>">
                            <div class="report-list_item_column size33">
                                <div class="report-list_item_text">
                                    <?php echo $row['cislo_zkv']; ?>
                                </div>
                            </div><div class="report-list_item_column size33">
                                <div class="report-list_item_text">
                                    <?php echo $row['kontrola_od']; ?>
                                </div>
                            </div><div class="report-list_item_column size33">
                                <div class="report-list_item_text">
                                    <?php echo $row['datum_expirace']; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    } ?>
                </div>
            </div>
        </section>
<?php }
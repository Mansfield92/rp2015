<?php

include('../config/config.db.php');

$old = $_GET['old'] == '1' ? true : false;
//$query = "SELECT jmeno, prijmeni, vysledek, cislo_zkv, datum_expirace, kontrola_od from kontrola left join zamestnanec on kontrola.id_user = zamestnanec.id  group by cislo_zkv order by datum_expirace ASC";

//if($old){
//    $query = "SELECT jmeno, prijmeni, vysledek, cislo_zkv, datum_expirace, kontrola_od from kontrola left join zamestnanec on kontrola.id_user = zamestnanec.id  WHERE kontrola.id not in (SELECT kontrola.id from kontrola left join zamestnanec on kontrola.id_user = zamestnanec.id  group by cislo_zkv order by datum_expirace ASC) group by cislo_zkv order by datum_expirace ASC";
//}else{
    $query = "SELECT jmeno, prijmeni, vysledek, cislo_zkv, datum_expirace, kontrola_od from kontrola left join zamestnanec on kontrola.id_user = zamestnanec.id  group by cislo_zkv order by datum_expirace ASC";
//}

$report = $con->query($query);

if($report->num_rows > 0) {
    $export = "<?xml version='1.0' encoding='UTF-8' ?>\n";
    $export .= "<Servisni_ukony>";

    while ($row = $report->fetch_assoc()) {
        $export .= "<Servis>";
        $export .= "<Vlak>$row[cislo_zkv]</Vlak>";
        $export .= "<Technik>$row[jmeno] $row[prijmeni]</Technik>";
        $export .= "<vysledek>$row[vysledek]</vysledek>";
        $export .= "<Posledni_kontrola>$row[kontrola_od]</Posledni_kontrola><Pristi_kontrola>$row[datum_expirace]</Pristi_kontrola></Servis>";
    }
    $export .= "</Servisni_ukony>";
    $cesta = 'export-servis.xml';
}else{
    $export = 'Žádné záznamy k exportu';
    $cesta = 'empty.txt';
}
//echo $export;

$handle = fopen($cesta,'w+');
fwrite($handle,$export);
fclose($handle);
$path = $cesta;
header("Content-Type: application/octet-stream; charset=utf-8");
header("Content-Length: " . filesize($path));
header('Content-Disposition: attachment; filename='.$path);
readfile($path);
unlink($path);
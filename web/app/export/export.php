<?php

include('../config/config.db.php');

$id = $_GET['id'];
$all = $id == 'all';
$extra = isset($_GET['extra']) ? $_GET['extra'] : false;
$query = "SELECT pocet_vagonu, cas, stavy.nazev as stav, s1.nazev as stanice1, vyluky.nazev as vyluka, stanice.nazev as stanice2, trasa.delka as delka,jmeno, prijmeni,cislo_zkv
FROM ukony
LEFT JOIN vlak USING (cislo_zkv)
LEFT JOIN zamestnanec on ukony.id_user = zamestnanec.id
LEFT JOIN trasa on ukony.id_trasa = trasa.id
LEFT JOIN stavy on ukony.stav = stavy.id_stav
LEFT JOIN stanice s1 on trasa.stanice1 = s1.id
LEFT JOIN stanice on trasa.stanice2 = stanice.id
LEFT JOIN vyluky on trasa.vyluka = vyluky.id";
if(!$all){
    $query .= " WHERE id_ukon = $id";
}
$query .= $extra ? ($extra == 'dead' ? " WHERE ukony.stav = 6" : " WHERE ukony.stav != 6") : '';


//echo $query;
$ukony = $con->query($query);

if($ukony->num_rows > 0) {

    $export = "<?xml version='1.0' encoding='UTF-8' ?>\n";
    $export .= $all ? "<Ukony>" : '';

    while ($row = $ukony->fetch_assoc()) {
        $time = intval($row['cas']);
        $hours = floor($time / 60);
        $minutes = ($time % 60);
        $export .= "<Ukon>";
        $export .= "<Zamestnanec>$row[jmeno] $row[prijmeni]</Zamestnanec>";
        $export .= "<Trasa><Zacatek>$row[stanice1]</Zacatek><Cil>$row[stanice2]</Cil><Vzdalenost>$row[delka] km</Vzdalenost><Vyluka>$row[vyluka]</Vyluka></Trasa>";
        $export .= "<Pocet_vagonu>$row[pocet_vagonu]</Pocet_vagonu>";
        $export .= "<Cas>" . $hours . 'h' . ($minutes > 0 ? ' ' . $minutes . 'm' : '') . "</Cas>";
        $export .= "<Stav>$row[stav]</Stav></Ukon>";
    }
    $export .= $all ? "</Ukony>" : '';
    $cesta = 'export.xml';
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
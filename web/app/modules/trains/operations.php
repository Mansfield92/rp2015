<?php

$data = $_POST['data'];
$operation = $_POST['operation'];

include("../../config/config.db.php");


switch ($operation) {
    case 'insert':
        $data = explode("$^$", $data);
        $query = "INSERT INTO vlak (";
        $keys = "";
        $values = "";
        for ($i = 0; $i < count($data); $i++) {
            $elem = explode("[^]", $data[$i]);
            $keys .= '`' . $elem[0] . '`,';
            $values .= "'" . $elem[1] . "',";
//            echo $elem[0] . ",'" . $elem[1] . "'";
        }
        $query = $query . substr($keys, 0, count($keys) - 2) . ") VALUES (" . substr($values, 0, count($values) - 2) . ")";
            echo $con->query($query);

        $return["json"] = json_encode(array("response" => "finished", "module" => "bagr"));
        break;
}
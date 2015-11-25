<?php

session_start();

header('Content-Type: application/json');

$data = isset($_POST['data']) ? $_POST['data'] : false;
$operation = $_POST['operation'];
$module = $_POST['module'];
$mapping = array('trains'=>'vlak', 'users'=>'zamestnanec');

include("config/config.db.php");

switch ($operation) {
    case 'insert':
        $data = explode("$^$", $data);
        $query = "INSERT INTO $mapping[$module] (";
        $keys = "";
        $values = "";
        for ($i = 0; $i < count($data); $i++) {
            $elem = explode("[^]", $data[$i]);
            $keys .= '`' . $elem[0] . '`,';
            $values .= "'" . $elem[1] . "',";
        }
        $query = $query . substr($keys, 0, count($keys) - 2) . ") VALUES (" . substr($values, 0, count($values) - 2) . ")";
        $result = $con->query($query);
        $return = array("response" => $result, "module" => $module, "operation" => $operation, "query" => $query);
//        $return["json"] = json_encode($return);
        echo json_encode($return);
        break;
    case 'delete':
        $delete = $_POST['delete'];
        $query = "DELETE FROM $mapping[$module] WHERE $delete";
        $return = array("response" => $con->query($query), "module" => $module, "operation" => $operation, "query" => $query);
        echo json_encode($return);
        break;
    case 'login_role':
        $return = array(true, "module" => $module, "operation" => $operation, "role"=>$_SESSION["login_role"]);
        echo json_encode($return);
        break;
    case 'update':
        $data = explode("$^$", $data);
        $query = "UPDATE $mapping[$module] SET ";
        $values = "";
        $id = $_POST['id'];
        for ($i = 0; $i < count($data); $i++) {
            $elem = explode("[^]", $data[$i]);
            $values .= $elem[0]." = '" . $elem[1] . "',";
        }
        $query = $query.substr($values, 0, count($values) - 2)." WHERE $id";
        $return = array("response" => $con->query($query), "module" => $module, "operation" => $operation, "query"=>$query);
        echo json_encode($return);
        break;
}
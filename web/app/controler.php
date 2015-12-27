<?php

session_start();

header('Content-Type: application/json');

$data = isset($_POST['data']) ? $_POST['data'] : false;
$operation = $_POST['operation'];
$module = $_POST['module'];
$mapping = array('trains'=>'vlak', 'users'=>'zamestnanec', 'depo'=>'depo', 'station'=>'stanice','route'=>'trasa');

include("config/config.db.php");

switch ($operation) {
    case 'insert':
        $data = explode("$^$", $data);
        $query = "INSERT INTO $mapping[$module] (";
        $keys = "";
        $values = "";
        $cislo_zkv = false;
        for ($i = 0; $i < count($data); $i++) {
            $elem = explode("[^]", $data[$i]);
            $keys .= '`' . $elem[0] . '`,';
            $values .= "'" . $elem[1] . "',";
            if($elem[0] == 'cislo_zkv'){
                $cislo_zkv = $elem[1];
            }
        }
        $query = $query . substr($keys, 0, count($keys) - 2) . ") VALUES (" . substr($values, 0, count($values) - 2) . ")";
        $result = $con->query($query);
        $return = array("response" => $result, "module" => $module, "operation" => $operation, "query" => $query, "id" => $module == 'trains' ? $cislo_zkv : $con->insert_id);
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
        $return = array(true, "module" => $module, "operation" => $operation, "role"=>$_SESSION["login_role"], "nickname"=> $_SESSION['login_name']);
        echo json_encode($return);
        break;
    case 'change-profile':

        $nick = isset($_POST['nick']) ? $_POST['nick'] : false;
        $pw = isset($_POST['password']) ? $_POST['password'] : false;

        if($nick != false && $pw != false){
            $query = "UPDATE `zamestnanec` SET `password` = '$pw', login = '$nick' WHERE `login` = '$_SESSION[login_name]'";
            $_SESSION['login_name'] = $nick;
        }elseif($nick != false){
            $query = "UPDATE `zamestnanec` SET login = '$nick' WHERE `login` = '$_SESSION[login_name]'";
            $_SESSION['login_name'] = $nick;
        }else{
            $query = "UPDATE `zamestnanec` SET `password` = '$pw' WHERE `login` = '$_SESSION[login_name]'";
        }



        $return = array("result" => $con->query($query),"query" => $query, "module" => $module, "operation" => $operation);
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
    case 'enable':
        $id = $_POST['id'];
        $enable = $_POST['enable'];
        $query = "UPDATE `rocnikovy_projekt`.`trasa` SET `disabled` = ".($enable == 'true' ? '0' : '1')." WHERE `trasa`.`id` = $id";
        $return = array("response" => $con->query($query), "module" => $module, "operation" => $operation, "query"=>$query);
        echo json_encode($return);
        break;
}
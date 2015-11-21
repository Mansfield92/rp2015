<?php
session_start();
include('../../config/config.db.php');
//if(isset($_SESSION['login_role']) && intval($_SESSION['login_role']) > 2) {
if(isset($_SESSION['login_role'])) {
    switch ($_POST['view']) {
        case 'list':
            echo '<section>Servis</section>';
            break;
    }
}
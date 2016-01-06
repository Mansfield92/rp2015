<?php
session_start();
include("app/config/config.db.php");
include("app/modules/login/class.login.php");
if (isset($login)) {
    $login->is_logged = $login->logged();
} else {
    $login = new login($con);
}
if (isset($_POST['logout'])) {
    $login->logout();
}
if ($login->is_logged != 1) {
    header('location: login.php');
}
$role = intval($_SESSION['login_role']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $page["title"]; ?></title>
    <link href="css/jquery-ui.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/jquery.switchButton.css" rel="stylesheet">
    <link href="css/main.min.css" rel="stylesheet">
    <link href="img/favicon.png" rel="icon" type="image/png" />
    <link href="css/morris.css" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="loader_back" class="ui-widget-overlay ui-front">
    <div id="loader" class="ui-front"><img src="img/loading.gif" alt="loader"/></div>
</div>
<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <button aria-expanded="false" data-target="#bs-example-navbar-collapse-8" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="../web/" class="navbar-brand active">Dashboard
                <img src="icons/logo.svg" height="30px" />
            </a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div id="bs-example-navbar-collapse-8" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <?php if($role >= 4){?><li><a class="load-page" href="#" data-action="depo-list">Depa</a></li>
                <li><a class="load-page" href="#" data-action="trains-list">Lokomotivy</a></li><?php } ?>
                <?php if($role == 2 || $role > 4 ){?><li><a class="load-page" href="#" data-action="users-list">Zaměstnanci</a></li><?php } ?>
                <?php if($role == 1 || $role > 4 ){?><li><a class="load-page" href="#" data-action="route-list">Trasy</a></li><?php } ?>
                <?php if($role == 1 || $role == 3 || $role > 4 ){?><li><a class="load-page" href="#" data-action="plans-list">Plánování</a></li><?php } ?>
                <?php if($role == 4){?><li><a class="load-page" href="#" data-action="servis-list">Servis</a></li><?php } ?>
<!--                <li><a class="load-page" href="#" data-action="servis-list">Servis</a></li>-->
                <?php if($role == 1){?><li><a class="load-page" href="#" data-action="reports-list">Reporty</a></li><?php } ?>
                <li class="icon-menu">
                    <a href="#"><img src="icons/profile_white.svg" height="25px" />Profil</a>
                    <div class="user-panel">
                        <div class="user-panel-left">
                            <img class="user-panel-avatar" src="upload_pic/<?php echo $_SESSION["avatar"]; ?>" width="100" height="100" alt="avatar">
                        </div><div class="user-panel-right">
                            <div class="user-panel-text"><?php echo $_SESSION['login_name']; ?></div>
                            <button data-user="<?php echo $_SESSION['login_name']; ?>" data-action="login-change-profile" class="btn-actions ajax-action">Úprava profilu</button>
                            <?php if(intval($_SESSION["login_role"]) >= 3){?><button data-action="users-list" class="btn-actions load-page">Úprava uživatelů</button><?php }?>
                            <button onclick="logout();" class="btn-actions ajax-action">Odhlásit se</button>
                        </div>
                    </div>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
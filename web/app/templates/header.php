<?php
session_start();

include("/app/modules/login/class.login.php");
if (isset($login)) {
    $login->is_logged = $login->logged();
} else {
    $login = new login;
}
if (isset($_POST['logout'])) {
    $login->logout();
}
if ($login->is_logged != 1) {
    header('location: login.php');
}
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
    <link href="css/main.min.css" rel="stylesheet">
    <link href="img/favicon.png" rel="icon" type="image/png" />
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
                <li><a class="load-page" data-action="depo-list">Depa</a></li>
                <li><a class="load-page" data-action="trains-list">Lokomotivy</a></li>
                <li><a class="load-page" data-action="users-list">Zaměstnanci</a></li>
                <li><a class="load-page" data-action="servis-list">Servis</a></li>
                <li><a class="load-page" data-action="reports-list">Reporty</a></li>
                <li class="icon-menu"><a href="#" onclick="logout();"><img src="icons/profile_white.svg" height="25px" />Profil</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</nav>
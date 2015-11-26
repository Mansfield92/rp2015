<?php
include("app/modules/login/class.login.php");
session_start();
if (isset($login)) {
    $login->is_logged = $login->logged();
} else {
    $login = new login;
}
if ($login->is_logged == 1) {
}
?>
    <!doctype html>
    <html>
<head>
    <meta charset="utf-8">
<?php
if ($login->is_logged == 1) {
    echo '<meta http-equiv="refresh" content="0;url=../web">';
    echo '<link href="css/login.min.css" rel="stylesheet"><link href="css/vader/jquery-ui-1.9.2.custom.min.css" rel="stylesheet"><script src="js/jquery.min.js"></script>';
    echo '<script>$(function() {
            $.fn.center = function () {
                this.css("position","absolute");
                this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) +
                    $(window).scrollTop()) + "px");
                this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) +
                    $(window).scrollLeft()) + "px");
                return this;
            };$("#loader").center();$(window).resize(function(){$("#loader").center();})});</script></head>';
    echo '<body><div id="loader_back" class="ui-widget-overlay ui-front"><div id="loader" class="ui-front"><img src="img/loading.gif" alt="loader" /></div></div></body></html>';
} else {
    ?>
    <link href="css/login.min.css" rel="stylesheet">
    <link href="css/vader/jquery-ui-1.9.2.custom.min.css" rel="stylesheet">
    <link type="image/png" rel="icon" href="img/favicon.png">
    <script src="js/jquery.min.js"></script>
    <script type='text/javascript' src='js/jquery.tipsy.js'></script>
    <script src="js/jquery-ui-1.9.2.custom.min.js"></script>
    <title>Login</title>
    <script>

        $(function () {
            $.fn.center = function () {
                this.css("position", "absolute");
                this.css("top", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop() / 2) + "px");
                this.css("left", Math.max(0, (($(window).width() - $(this).outerWidth()) / 2) + $(window).scrollLeft()) + "px");
                return this;
            };
            $.fn.centerTop = function () {
                this.css("marginTop", Math.max(0, (($(window).height() - $(this).outerHeight()) / 2) + $(window).scrollTop() / 2) + "px");
                return this;
            };
        });
        $(document).keypress(function (e) {
            if (e.which == 13) {
                try_login();
            }
        });


        window.onload = function () {
            <?php
             if ($login->is_logged != 1) {
                    echo "destroy_loader();";
                }
             ?>
            if (/msie/.test(navigator.userAgent.toLowerCase())) {
                alert('Go fuck yourself with ' + navigator.userAgent);
            }
            $('input').tipsy({fade: true, gravity: 'w'});
            $('.login').centerTop();
            $('input[name="login_name"]').focus();
            $("input[type=checkbox]").button();
            $("label[for='animation']").attr('class', 'enviar');
            $("#animation").button();

        };
        $(window).resize(function () {
            var h = parseInt(($(window).height() / 2) - $('.login').height() / 2, 10);
            $('body').height($(window).height() - h + 20);
            $('.login').css('marginTop', h - 20);
        });
        function show_loader() {
            $("#loader").center();
            $("#loader_back").show();
            $("#loader").show();
        }
        function try_login() {
            if ($('#wrong_pass')) {
                $('#wrong_pass').remove();
            }
            show_loader();
            $.ajax({
                type: 'POST', url: '#', dataType: 'jsonp', data: $('#login_form').serialize(),
                error: function (jqXHR, textStatus, errorThrow) {
                    destroy_loader();
                    if (jqXHR.responseText.search('login_form') == -1) {
                        window.location.reload(true);
                    } else {
                        $('body').append("<div id='wrong_pass'>Wrong username or password.</div>");
                        $('#wrong_pass')
                            .css('left', (parseInt($(window).width() / 2) - $('#wrong_pass').width() / 2) - 20)
                            .show()
                            .addClass('active')
                            .animate({"top": "+=100px"}, "slow")
                            .effect("bounce", {times: 4}, 500).fadeOut(5000);
                    }
                }
            });
        }

        $(window).resize(function () {
            $('.login').centerTop();
        });

        function destroy_loader() {
            $("#loader").hide();
            $("#loader_back").fadeOut('slow');
        }
    </script>
    </head>

    <body>
    <div id="loader_back" class="ui-widget-overlay ui-front">
        <div id="loader" class="ui-front"><img src="img/loading.gif" alt="loader"/></div>
    </div>
    <div id="bagr"></div>
    <section class="login">
        <div class="titulo">Log on to application</div>
        <form method="post" enctype="application/x-www-form-urlencoded" id="login_form">
            <input type="text" name="login_name" title="Username here" placeholder="Username" data-icon="U">
            <input type="password" name="login_pw" title="Your password here" placeholder="Password" data-icon="x">
            <a onclick="try_login();" class="enviar btn-basic btn-back">Log in</a>
        </form>
    </section>
    </body>
    </html>
<?php } ?>
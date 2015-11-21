/**
 * Created by Neo on 17.11.2015.
 */
var $App = $App || {};

$App.titles = {'default': 'Dashboard', 'trains-list': 'Seznam Vlaku', 'trains-add_form': 'Přidání vlaku', 'reports-list': 'Seznam reportů'};

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

function logout() {
    $.post('#', {logout: true}, function (data) {
        F5();
    });
}

function log(msg) {
    console.log(msg);
}

function F5() {
    window.location.reload(true);
}

function destroy_loader() {
    $("#loader").hide();
    $("#loader_back").fadeOut('slow');
}

function show_loader() {
    $("#loader").center();
    $("#loader_back").show();
    $("#loader").show();
}

function loadingmessage(msg, show_hide) {
    if (show_hide == "show") {
        $('#uploaded_image').html('');
    } else if (show_hide == "hide") {
    } else {
        $('#uploaded_image').html('');
    }
}

function fullLenght(elem){
    elem = elem.toString();
    return elem.length == 1 ? ("0" + elem) : elem
}

function timestamp(){
    var d = new Date();
    var day = fullLenght(d.getDate());
    var month= fullLenght(d.getMonth() + 1);
    var year = d.getFullYear();
    var hours = fullLenght(d.getHours());
    var minutes = fullLenght(d.getMinutes());
    var seconds = fullLenght(d.getSeconds());
    return ("" + year+month+day+"_"+hours+minutes+seconds);
}

$App.getAjaxData = function (target, data) {
    if (typeof(data) === 'undefined') {
        data = {view: target};
    }
    var getData = $.ajax({
        type: 'POST',
        url: "app/modules/" + target + "/views.php",
        data: data,
        success: function (data) {
            getData = data;
        },
        async: false,
        timeout: 5000,
        global: false
    }).responseText;
    return getData;
};

$App.loadPage = function (module, view) {
    var response = $App.getAjaxData(module, {view: view});
    response = response.indexOf('section') == -1 ? '<section><div class="container">Spatna stranka, pico! :D (Modul neexistuje)</div></section>' : response;
    window.history.pushState({"html": response, "pageTitle": module + view}, "", "#" + module + "-" + view);
    $App.body.replaceWith(response);
    $App.body = $('body').find('> section');
    document.title = $App.titles[module + '-' + view];
    $App.dynamic();
};

$App.executeOperation = function (module, $data) {
    var getData = $.ajax({
        dataType: 'json',
        type: 'POST',
        url: "app/controler.php",
        data: $data,
        success: function (data) {
            switch ($data){
                case 'insert':

                    break;
                case 'update':

                    break;
                case 'delete':

                    break;
            }
            if (data["response"] !== false) {
                getData = data;
                log(data["operation"]);
            }else{
                // @TODO pokud se form neodesle
                log('error');
                log($data.operation);
            }
        },
        async: false,
        timeout: 5000,
        global: false
    }).responseText;
    return getData;
};

$App.dynamic = function () {
    $('.datepicker').datepicker();
    $App.myUpload = $('#upload_link').upload({
        name: 'image',
        action: 'app/modules/image_upload/image_handling.php',
        enctype: 'multipart/form-data',
        autoSubmit: false,
        onSubmit: function () {
            $('#upload_link').html('Choose File');
        },
        onSelect: function(){
            $('#upload_link').html($('input[name="image"]').val());
        }
    });
    $('.load-page').unbind('click').bind('click',function () {
        var $action = $(this).data('action');
        $action = $action.split('-');
        log($action);
        $App.loadPage($action[0], $action[1]);
    });
    $('.ajax-action').unbind('click').bind('click', function () {
        var $action = $(this).data('action');
        $action = $action.split('-');
        if ($action.length == 2) {
            $App.loadPage($action[0], $action[1]);
        } else {
            var $data = "";
            $('.add_form__row input').each(function () {
                $data += $(this).attr('name') + "[^]" + $(this).val() + "$^$";
            });
            if($('#upload_link').length > 0) {
                var $filename = timestamp();
                var $ext = $('input[name="image"]').val();
                $ext = $ext.substr($ext.lastIndexOf('.'));
                log($filename+ "" + $ext);
                $App.myUpload.set({
                    params: {upload: 'Upload',filename: $filename}
                });
                $App.myUpload.submit();
            }
            var $response = $App.executeOperation($action[0], {operation: $action[2], module: $action[0], data: $data.substr(0, $data.length - 3)});
            log($response);
            log($response["json"]);
            //log(toJSON($response));
            //log(jsonp($response));
            log($response[0]);
            //log($response.module + ";" + $response.operation + ";" + $response.response );
        }
    });
};


$App.init = function () {
    $App.body = $('body').find('> section');
    var $path = window.location.href.toString();
    console.log($path);
    $path = $path.substr($path.lastIndexOf('/') + 1);
    if ($path.length > 0) {
        $path = $path.substr(1);
        console.log($path);
        var $modules = $path.split('-');
        $App.loadPage($modules[0], $modules[1]);
    } else {
        if ($App.body.hasClass('index')) {
            $('.grid-item').click(function () {
                var $action = $(this).data('action');
                $action = $action.split('-');
                $App.loadPage($action[0], $action[1]);
            });
            $App.dynamic();
        } else {
            F5();
        }
    }
};

$(document).ready(function () {
    $App.init();
});

window.onpopstate = function (e) {
    //$App.init();
    if (e.state) {
        //console.log(e.state.html);
        //$App.body.replaceWith(e.state.html);
        //document.title = e.state.pageTitle;
        //$App.init();
    }
};
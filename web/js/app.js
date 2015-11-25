/**
 * Created by Neo on 17.11.2015.
 */
var $App = $App || {};

$App.titles = {'default': 'Dashboard', 'trains-list': 'Seznam Vlaku', 'trains-add_form': 'Přidání vlaku', 'reports-list': 'Seznam reportů'};

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
    show_loader();
    var response = $App.getAjaxData(module, {view: view});
    response = response.indexOf('section') == -1 ? '<section><div class="container">Spatna stranka, pico! :D (Modul neexistuje)</div></section>' : response;
    window.history.pushState({"html": response, "pageTitle": module + view}, "", "#" + module + "-" + view);
    $App.body.replaceWith(response);
    $App.body = $('body').find('> section');
    //log($App.titles[module + '-' + view] === undefined);
    document.title = $App.titles[module + '-' + view] === undefined ? (module + '-' + view) : $App.titles[module + '-' + view];
    $App.dynamic();
};

$App.executeOperation = function (module, $data, $reload) {
    show_loader();
    var getData = $.ajax({
        dataType: 'json',
        type: 'POST',
        url: "app/controler.php",
        data: $data,
        success: function (data) {
            switch ($data.operation){
                case 'insert':
                    if (data["response"] !== false) {
                        getData = data;
                        utils.showDialog('Položka byla úspěšně vložena','Chyba',true,true);
                    }else{
                        // @TODO pokud se form neodesle
                        utils.showDialog('Nepodařilo se uložit záznam','Chyba',true,true);
                    }
                    break;
                case 'update':
                    if(data['response']){
                        F5();
                    }else utils.showDialog('Response ' + data['response'],'Info',true,true);
                    break;
                case 'delete':
                    $('<div id="dialog">').appendTo('body');$('#dialog').html('').html('<p>Proveden dotaz: ' + data.query + '</p>');$("#dialog").dialog({modal: true,title: 'Info',width: 'auto',draggable: true,buttons: {Ok: function () {utils.showDialog.removeDialog();$App.loadPage(module,$reload)}},close: function (event, ui){utils.showDialog.removeDialog();$App.loadPage(module,$reload);}});
                    break;
                case 'login_role':
                    //utils.showDialog('Login role je: ' + data["role"],'Chyba',true,true);
                    $App.login_role = parseInt(data["role"]);
                    break;
            }
        },
        async: false,
        timeout: 5000,
        global: false
    }).responseText;
    return getData;
};

$App.dynamic = function () {
    $App.executeOperation('login',{operation: 'login_role',module: 'login'});

    $('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });
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
    $('#search').keyup(function(){
        var valThis = $(this).val().toString();
        valThis = valThis.toLowerCase();
        $('.train-list_item').each(function(){
            var text = $(this).data('search').toString();
            text = text.toLowerCase();
            if(text.indexOf(valThis) != -1){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });
    $('.load-page').unbind('click').bind('click',function () {
        var $action = $(this).data('action');
        $action = $action.split('-');
        $App.loadPage($action[0], $action[1]);
    });
    $('.ajax-action').unbind('click').bind('click', function () {
        var $action = $(this).data('action');
        $action = $action.split('-');
        if ($action.length == 2) {
            $App.loadPage($action[0], $action[1]);
        } else {
            if($action[2] == 'delete'){
                var $del = $(this).data('delete');
                var $reload = $(this).data('reload');
                $('<div id="dialog">').appendTo('body');
                $('#dialog').html('').html('<p>Opravdu chcete vymazat tuto položku?</p>');
                $("#dialog").dialog({modal: true,title: 'Mazání',width: 'auto',draggable: true,buttons: {
                    Ok: function () {utils.showDialog.removeDialog();$App.executeOperation($action[0], {operation: $action[2], module: $action[0], delete: $del},$reload);},
                    Cancel: function(){utils.showDialog.removeDialog();}},close: function (event, ui){utils.showDialog.removeDialog();}}
                );
            }else {
                var $data = "";
                $('.add_form__row > input, .train-description_item > input').each(function () {
                    $data += $(this).attr('name') + "[^]" + $(this).val() + "$^$";
                });
                //log($action[0] + " = " + $action[1] + " = " + $action[2]);
                if ($('#upload_link').length > 0) {
                    var $filename = timestamp();
                    var $ext = $('input[name="image"]').val();
                    if($ext.length < 3 && $action[2] == 'insert'){
                        utils.showDialog('error','error kurva pica',true,true);
                        return false;
                    }else if($ext.length > 3) {
                        $ext = $ext.substr($ext.lastIndexOf('.'));
                        log($filename + "" + $ext);
                        $App.myUpload.set({
                            params: {upload: 'Upload', filename: $filename}
                        });
                        $App.myUpload.submit();
                        $data += "img_url[^]" + $filename + $ext + "$^$";
                        $('input[name="image"]').val('');
                    }
                }
                var $params;
                if($action[2] == 'update') {
                    var data_id = $(this).data('id');
                    $params = {id: data_id, operation: $action[2], module: $action[0], data: $data.substr(0, $data.length - 3)};
                }else $params = {operation: $action[2], module: $action[0], data: $data.substr(0, $data.length - 3)};
                var $response = $App.executeOperation($action[0], $params);
                log($response);
            }
        }
    });
    if($App.login_role >= 2){
        $App.adminizer();
    }
    setTimeout(function(){destroy_loader()},200);
};

$App.adminizer = function(){
    $('.adminizer').each(function () {
        var $this = $(this);
        var $html = $this.html();
        var $datepic = $this.hasClass('datepick');
        var $name = $this.data('name');
        if($datepic) {
            $this.replaceWith('<input class="datepic" name="'+$name+'" type="text" value="' + $html + '" />');
            $('.datepic').datepicker({ dateFormat: 'yy-mm-dd' });
        }else{
            $this.replaceWith('<input type="text" name="'+$name+'" value="' + $html + '" />');
        }
    });
};


$App.init = function () {
    $App.body = $('body').find('> section');
    var $path = window.location.href.toString();
    $path = $path.substr($path.lastIndexOf('/') + 1);
    if ($path.length > 0) {
        $path = $path.substr(1);
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
    show_loader();
    $App.init();
});

window.onpopstate = function (e) {
    //$App.init();
    if (e.state) {
        F5();
        //console.log(e.state.html);
        //$App.body.replaceWith(e.state.html);
        //document.title = e.state.pageTitle;
        //$App.init();
    }
};
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
    response = response.indexOf('section') == -1 ? '<section><div class="container">Modul neexistuje</div></section>' : response;
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
                        destroy_loader();
                        $App.loadPage(module,'detail_'+data['id']);
                        //utils.showDialog('Položka byla úspěšně vložena' + "<br/>" + data['id'],'Info',true,true);
                    }else{
                        destroy_loader();
                        utils.showDialog('Nepodařilo se uložit záznam','Chyba',true,true);
                    }
                    break;
                case 'update':
                    if(data['response']){
                        F5();
                    }else utils.showDialog('Response ' + data['response'],'Info',true,true);
                    break;
                case 'delete':
                    destroy_loader();
                    if($reload.indexOf('#') != -1){
                        var $view = $reload.split('-');
                        var $module = $view[0].substr(1);
                        $view = $view[1];
                        $App.loadPage($module,$view);
                    }else{
                        $App.loadPage(module,$reload);
                    }
                    //$('<div id="dialog">').appendTo('body');$('#dialog').html('').html('<p>Proveden dotaz: ' + data.query + '</p>');$("#dialog").dialog({modal: true,title: 'Info',width: 'auto',draggable: true,buttons: {Ok: function () {utils.showDialog.removeDialog();$App.loadPage(module,$reload)}},close: function (event, ui){utils.showDialog.removeDialog();$App.loadPage(module,$reload);}});
                    break;
                case 'login_role':
                    $App.login_role = parseInt(data["role"]);
                    $App.login_name = data["nickname"];
                    break;
                case 'change-profile':
                    destroy_loader();
                    utils.showDialog('Zmena profilu cajk','Info',true,true);
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
    if($('.route-switch').length > 0){
        $( window ).resize(function() {
            setTimeout(function(){
            $('.to_switch').each(function(){
                $(this).find('input').removeAttr('style');
                $(this).html($(this).find('input').get( 0 ));
            });
                $App.routes();},550);
        });
    }
    $App.routes();
    $App.executeOperation('login',{operation: 'login_role',module: 'login'});

    $('.datepicker').each(function(){
        if($(this).hasClass('nofuture')){
            $(this).datepicker({maxDate: new Date,dateFormat: 'yy-mm-dd'});
        }else if($(this).hasClass('future')){
            $(this).datepicker({minDate: new Date,dateFormat: 'yy-mm-dd'});
        }else{
            $(this).datepicker({dateFormat: 'yy-mm-dd'});
        }
    });

    if($('.graph').length > 0){
        $( window ).resize(function() {
            setTimeout(function(){
                F5();
            },10);
        });
    }

    if($('#graph-km').length > 0) {
        var $val1 = $('#graph-km').data('val1');
        var $val2 = $('#graph-km').data('val2');

        new Morris.Donut({
            element: 'graph-km',
            data: [
                {label: "Kilometrů tento rok", value: $val1},
                {label: "Kilometrů ostatní roky", value: $val2}
            ]
        });
    }

    if($('#graph-event').length > 0) {
        var $val1 = $('#graph-event').data('val1');
        var $val2 = $('#graph-event').data('val2');

        new Morris.Donut({
            element: 'graph-event',
            data: [
                {label: "Úkonů tento rok", value: $val1},
                {label: "Úkonů ostatní roky", value: $val2}
            ]
        });
    }

    if($('#graph-servis').length > 0) {
        var $val1 = $('#graph-servis').data('val1');
        var $val2 = $('#graph-servis').data('val2');

        new Morris.Donut({
            element: 'graph-servis',
            data: [
                {label: "Servisů tento rok", value: $val1},
                {label: "Servisů ostatní roky", value: $val2}
            ]
        });
    }

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

    $('.change_state').change(function(){
        var $id = $(this).data('id');
        var $train = $(this).data('train');
        var $newState = $(this).val();
        log($id + ' - ' + $newState + ' - ' + $train);

        var getData = $.ajax({
            dataType: 'json',
            type: 'POST',
            url: "app/controler.php",
            data: {module:'plans',operation:'change_state',id:$id, state: $newState, train:$train},
            success: function (data) {
                if($newState >= 5)$App.loadPage('plans', 'list');
            }
        });
    });

    $('#search, .search-input').keyup(function(){
        var valThis = $(this).val().toString();
        valThis = valThis.toLowerCase();
        var $list = $(this).data('search');
        $('.'+$list+'_item').each(function(){
            var text = $(this).data('search').toString();
            text = text.toLowerCase();
            if(text.indexOf(valThis) != -1){
                $(this).show();
            }else{
                $(this).hide();
            }
        });
    });
    $('.load-page').unbind('click').bind('click',function (e) {
        e.preventDefault();
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
                $("#dialog").dialog({modal: true,title: 'Mazání',width: 'auto',draggable: true, resizable: false,buttons: {
                    Ano: function () {utils.showDialog.removeDialog();$App.executeOperation($action[0], {operation: $action[2], module: $action[0], delete: $del},$reload);},
                    Ne: function(){utils.showDialog.removeDialog();}},close: function (event, ui){utils.showDialog.removeDialog();}}
                );
            }else if($action[0] == 'login' && $action[2] == 'profile'){
                $('<div id="dialog">').appendTo('body');
                var html ='<form id="profile-form">';

                if($App.login_role != 69){
                    html += '<label for="login">Přihlašovací jméno: </label><input type="text" name="login" value="'+$App.login_name+'" placeholder="Přihlašovací jméno" >';
                }
                html += '<label for="password">Heslo: </label><input type="password" name="password" value="" placeholder="Heslo" >' +
                    '<label for="password_check">Heslo znovu: </label><input type="password" name="password_check" value="" placeholder="Heslo znovu" ></form>'
                $('#dialog').html('').html(html);
                $("#dialog").dialog({modal: true,title: 'Úprava profilu',width: 'auto',draggable: true,
                    buttons: {
                        Upravit: function () {
                            var $form = $('#profile-form');
                            var $nick = $form.find('input[name="login"]').val() || "";
                            var $pass = $form.find('input[name="password"]').val();
                            var $check = $form.find('input[name="password_check"]').val();

                            if($nick.length > 0 && $nick != $App.login_name && $pass.length == 0){
                                $App.executeOperation('login',{operation: 'change-profile',module: 'login',nick:$nick});
                            }else if($pass == $check){
                                if($pass.length >= 5){
                                    if($nick.length > 0 && $nick != $App.login_name){
                                        $App.executeOperation('login',{operation: 'change-profile',module: 'login',nick:$nick, password: $pass});
                                    }else{
                                        $App.executeOperation('login',{operation: 'change-profile',module: 'login',password: $pass});
                                    }
                                }
                            }
                    },Zavřít: function (event, ui){
                        utils.showDialog.removeDialog();
                        }
                }});
            }else {
                var $data = "";
                $('.add_form > input,.add_form > div > input,.add_form > div > textarea, .train-description_item > input,.train-description_item > select, .add_form__row select').each(function () {
                    $data += $(this).attr('name') + "[^]" + $(this).val() + "$^$";
                });
                //log($action[0] + " = " + $action[1] + " = " + $action[2]);
                if ($('#upload_link').length > 0) {
                    var $filename = timestamp();
                    var $ext = $('input[name="image"]').val();
                    if($ext.length < 3 && $action[2] == 'insert'){
                        utils.showDialog('Prosim vyberte obrazek','Chyba',true,true);
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

                var $validate = $(this).data('validate');
                var $params;
                if(typeof $validate != 'undefined'){
                    var $items = $validate.split('|');
                    for(var $i = 0; $i < $items.length; $i++){
                        $validate = $items[$i].split(',');
                        if(!validate($validate[0],$validate[1])){
                            utils.showDialog($validate[2],'Chyba',true,true);
                            return false;
                        }
                    }
                    if($action[2] == 'update') {
                        var data_id = $(this).data('id');
                        $params = {id: data_id, operation: $action[2], module: $action[0], data: $data.substr(0, $data.length - 3)};
                    }else $params = {operation: $action[2], module: $action[0], data: $data.substr(0, $data.length - 3)};
                    var $response = $App.executeOperation($action[0], $params);
                    log($response);
                }else{
                    if($action[2] == 'update') {
                        data_id = $(this).data('id');
                        $params = {id: data_id, operation: $action[2], module: $action[0], data: $data.substr(0, $data.length - 3)};
                    }else $params = {operation: $action[2], module: $action[0], data: $data.substr(0, $data.length - 3)};
                    $response = $App.executeOperation($action[0], $params);
                    log($response);
                }

            }
        }
    });
    if($App.login_role >= 1){
        $App.adminizer();
    }
    setTimeout(function(){destroy_loader()},200);
};

function validate(type,data){
    switch (type){
        case 'notSame':
            data = data.split('+');
            var item1 = $('#'+data[0]).val();
            var item2 = $('#'+data[1]).val();
            //log($('select#'+data[0]));
            //var item2 = $('#'+data[1]).val();
            //log(item1 + "=" + item2);
            return item1.length > 0 && item2.length > 0 && item1 != item2;
            break;
        case 'notEmpty':
            var $val = $('#'+data).val();
            return $val != '--';
            break;
        case 'isNum':
            $val = $('input[name="'+data+'"]').val();
            log($val);
            return (!isNaN(parseInt($val)) && isFinite($val));
            break;
    }
    return false;
}

$App.routes = function(){
    var $width = parseInt($('.route-list_item_column:first').width());

    $('.route-switch').switchButton({
        width: $width+2,
        height: 40,
        button_width: ($width/2)+1,
        //show_labels: false
        on_label: 'Zapnuta',
        off_label: 'Vypnuta'
    });

    $('.switch-button-button').each(function () {
        var $state = $(this).parent().is('.checked');
        log($state);
        $(this).html(!$state ? 'Zapnout' : 'Vypnout');
    });

    $('.switch-button-background').css('height','100%');

    $('.switch-button-background, .switch-button-button, .switch-button-label').click(function() {
    //$('.to_switch').click(function() {
    //    alert('bagr');
        var $check = $(this).parent().parent().find('input');
        //var $check = $(this).find('input');
        var $id = $check.data('action');
        //var $state = !($(this).parent().parent().find('.switch-button-label:first').is('.on'));
        var $state = $check.is(':checked');
        var $this = $check;


        setTimeout(function(){
        log($state);

        var getData = $.ajax({
            dataType: 'json',
            type: 'POST',
            url: "app/controler.php",
            data: {module:'route',operation:'enable',id:$id, enable: $state},
            success: function (data) {
                //var $parent = (($this.parent()  ).parent()).parent();
                var $parent = $this.parent().parent().parent();
                //alert($parent.attr('class'));
                if($state){
                    $parent.removeClass('disabled');
                }else{
                    $parent.addClass('disabled');
                }

                $parent.find('.switch-button-button').html(!$state ? 'Zapnout' : 'Vypnout');
                //utils.showDialog('Response ' + data['response'],'Info',true,true);
            }
        });});
    });
};

$App.adminizer = function(){
    $('.adminizer').each(function () {
        if($(this).hasClass('adminizer-hide')){
            $(this).parent().find('select').removeClass('hidden');
            $(this).remove();
        }else {
            var $this = $(this);
            var $html = $this.html();
            var $datepic = $this.hasClass('datepick');
            var $name = $this.data('name');
            if ($datepic) {
                if($this.hasClass('nofuture')){
                    $this.replaceWith('<input class="datepic" name="' + $name + '" type="text" value="' + $html + '" />');
                    $('.datepic').datepicker({maxDate: new Date,dateFormat: 'yy-mm-dd'});
                }else if($this.hasClass('future')){
                    $this.replaceWith('<input class="datepic" name="' + $name + '" type="text" value="' + $html + '" />');
                    $('.datepic').datepicker({minDate: new Date,dateFormat: 'yy-mm-dd'});
                }else{
                    $this.replaceWith('<input class="datepic" name="' + $name + '" type="text" value="' + $html + '" />');
                    $('.datepic').datepicker({dateFormat: 'yy-mm-dd'});
                }
            } else {
                if($name == 'password')$this.replaceWith('<input type="password" name="' + $name + '" value="' + $html + '" />');
                else $this.replaceWith('<input type="text" name="' + $name + '" value="' + $html + '" />');
            }
        }
    });
};


$App.init = function () {

    $('html').unbind('click').bind('click',function(e) {
        //log(e.target);
        if(!$(e.target).is('.navbar-toggle')) {
            if ($('#bs-example-navbar-collapse-8').hasClass('in')) {
                log($(e.target).is('.navbar-toggle'));
                $('#bs-example-navbar-collapse-8').addClass('navbar-collapsing').animate({
                    height: '1px'
                }, 500, function () {
                    $(this).removeClass('in navbar-collapsing').removeAttr('style');
                });
                //$(this).removeClass('collapsed');
                //if($('#bs-example-navbar-collapse-8').hasClass('in')){
                //    log('outside click');
                //}
            }
        }
    });

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
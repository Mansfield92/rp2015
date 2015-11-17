/**
 * Created by Neo on 17.11.2015.
 */
var $App = $App || {};

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

$App.getAjaxData = function (target, data) {
    if (typeof(data) === 'undefined') {
        data = {view: target};
    }
    var getData = $.ajax({
        type: 'POST',
        url: "app/modules/"+target+"/views.php",
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

$App.loadPage = function(module, view){
    var response = $App.getAjaxData(module,{view: view});
    window.history.pushState({"html":response,"pageTitle":module+view},"", "#" + module+"-"+view);
    $App.body.html(response);
    document.title = module + "_" + view;

    //window.history.pushState("object or string", "Title", module+"/"+view);
};


$App.init = function () {
    $App.body = $('body').find('> section');
    var $path = window.location.href.toString();
    $path = $path.substr($path.lastIndexOf('/'));
    $path = $path.substr($path.indexOf('.php')+4);
    if($path.length > 1){
        $path = $path.substr(1);
        $modules = $path.split('-');
        $App.loadPage($modules[0],$modules[1]);
    }else {
        if ($App.body.hasClass('index')) {
            $('.grid-item').click(function () {
                var $action = $(this).data('action');
                $action = $action.split('-');
                $App.loadPage($action[0], $action[1]);
            });
        }
    }
};

$( document ).ready(function() {
    $App.init();
});

window.onpopstate = function(e){
    if(e.state){
        $App.body.html(e.state.html);
        document.title = e.state.pageTitle;
        $App.init();
    }
};
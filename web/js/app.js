/**
 * Created by Neo on 17.11.2015.
 */
function logout() {
    $.post('#', {logout: true}, function (data) {
        F5();
    });
}
function F5() {
    window.location.reload(true);
}
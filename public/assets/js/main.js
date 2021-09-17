//Set footer fixed in the bottom of the page when body height is smaller than window height
$( document ).ready(function() {
    if ($('html').height() <= $(window).height()) {
        $('footer').css('marginTop', $(document).innerHeight() - $('main').innerHeight() - $('header').innerHeight() - $('footer').innerHeight());
    }
});

function showModal(icon, msg, callback) {
    Swal.fire({
        icon: icon,
        title: msg,
        showConfirmButton: false,
        timer: 2500,
        willClose: typeof callback !== 'undefined' ? callback : null
    });
}
var $jslink = jQuery.noConflict();
$jslink(function() {
    $jslink(document.body).on('click', '.jslink', function(event){
        event.preventDefault();
        var l = $jslink(this).attr('data-url');
        if (l !== undefined) {
            var t = $jslink(this).attr('data-target');
            if (event.ctrlKey || (t !== undefined && t === '_blank')){
                window.open(l,'_blank')
            } else {
                window.location.href = l;
            }
        }
    });
});
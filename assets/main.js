jQuery(document).on('contextmenu', '.grid-view tbody > tr > td', function (event) {
    jQuery(document).dropdown2('hide');
    var dropdown = jQuery(this).siblings('.gv-actions').find('.dropdown2');
    if (dropdown) {
        event.preventDefault();
        event.stopPropagation();
        jQuery('<div id="contextmenu-trigger"></div>').css({
            'position': 'absolute',
            'width': 30,
            'height': 1
        }).attr('data-dropdown2', '#' + dropdown.attr('id')).offset({
            'left': event.pageX - 15,
            'top': event.pageY
        }).prependTo(this).dropdown2('show');
    }
});

jQuery(document).on('hide', '.dropdown2', function (event) {
    jQuery('#contextmenu-trigger').remove();
});

jQuery(document).on('contextmenu', '.grid-view tbody > tr > td', function (event) {
    var $dropdown = jQuery(this).siblings('.actions').find('.dropdown2');
    if ($dropdown) {
        event.preventDefault();
        event.stopPropagation();
        jQuery('<div></div>').css({
            'position': 'absolute',
            'width': 30,
            'height': 1
        }).offset({
            'left': event.pageX - 15,
            'top': event.pageY
        }).prependTo(this).attr('data-dropdown2', '#' + $dropdown.attr('id')).dropdown2('show').remove();
    }
});

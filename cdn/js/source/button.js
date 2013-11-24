app.button = app.button || {

    ing : function ( button, restore ) {
        if (typeof restore == 'undefined') restore = true;
        button.addClass('ing');
        if ( restore ) {
            setTimeout(function(){
                button.removeClass('ing');
            }, 1000);
        }

    }
}
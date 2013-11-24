app.keys = [];
app.keymap = {
	'F2' : 113,
	'ESC' : 27
};

/**
 * 注册登录热键 F2
 */
app.registerLoginKey = function() {
	app.registerKey( app.keymap.F2, function(){
		app.login();
	} );
};

/**
 * 注册热键
 */
app.registerKey = function( key, fun ) {
	app.keys[ key ] = fun;
	app.bindKeys();
};

/**
 * 注销热键
 */
app.removeKey = function( key ) {
	app.keys[ key ] = null;
	app.bindKeys();
};

/**
 * 绑定热键事件
 */
app.bindKeys = function( ) {
	$( 'body' ).unbind( 'keydown' ).bind( 'keydown', function( event ) {
		var keyCode;
        try {
			keyCode = event.keyCode;
        } catch ( a ) {
			//keyCode = KeyDown.arguments[ 0 ].keyCode;
        }
		app.keys[ keyCode ] && app.keys[ keyCode ]();
	} );
};
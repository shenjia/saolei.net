var app = app || {};
app.callbacks = app.callbacks || {};
app.timers = [];
app.client = [];
app.client.mobile = ( /iPhone|iPod|iPad/i ).test( navigator.userAgent );
app.client.oldie = $.browser.msie && $.browser.version <= 7;
app.config = {};

/**
 * 输出调试信息
 */
app.log = function() {
	return ( typeof console !== 'undefined' ) ?
		function ( msg ) { console.log( msg ); } :
		function ( msg ) { alert( msg ); };
}();

/**
 * 统计运行时间
 */
app.timer = function() {
	return ( typeof console !== 'undefined' ) ?
		function( name ) {
			name = name || 'script';
			if ( app.timers[ name ] ) {
				console.timeEnd( name );
				delete( app.timers[ name ] );
			} else {
				console.time( name );
				app.timers[ name ] = true;
			}
		} :
		function( name ) {
			name = name || 'script';
			if ( app.timers[ name ] ) {
				app.log( name + ': ' + ( +new Date() - app.timers[ name ] ) + 'ms' );
				delete( app.timers[ name ] );
			} else {
				app.timers[ name ] = +new Date();
			}
		};
}();

/**
 * 填充模板
 */
app.render = function ( template, data ) {
	for ( var index in data ) {
		if ( data.hasOwnProperty( index ) ) {
			template = template.replace( new RegExp( '{' + index + '}', 'g' ), data[ index ] );
		}
	}
	return template;
};

/**
 * 持续尝试，延时递增（返回true时终止尝试）
 */
app.keepTry = function ( fn, delay ) {
	(function tryagain( fn, delay ){
		if ( !fn() ) {
			setTimeout( function(){
				tryagain( fn, delay * 2 );
			}, delay );
		}
	})( fn, delay || 300 );
};


/**
 * 防止连续触发
 */
app.noRepeat = function ( name, handler, delay, immediate ) {
	if ( app.timers[ name ] ) { return; }
	delay = delay || 400;
	if ( typeof immediate === 'undefined' ) { immediate = true; }
	if ( immediate ) {
		app.timers[ name ] = setTimeout( function(){
			delete app.timers[ name ];
		}, delay );
		handler();
	} else {
		app.timers[ name ] = setTimeout( function(){
			handler();
			delete app.timers[ name ];
		}, delay );
	}
};

/**
 * 异步加载Js
 */
app.loadJs = function ( src ) {
	var script = document.createElement( 'script' );
	script.type = 'text/javascript';
	script.src = src;
	document.body.appendChild( script );
};

/**
 * 跳转
 */
app.go = function ( url ) {
	location.href = url;
};

/**
 * 打开新窗口
 */
app.open = function ( url ) {
	window.open( url );
};

/**
 * 刷新
 */
app.refresh = function() {
	location.reload();
};

/**
 * 回调
 */
app.callback = function( name, fn ) {
	if ( fn ) {
		app.callbacks[ name ] = fn;
	} else if ( fn = app.callbacks[ name ] ) {
		fn();
		delete app.callbacks[ name ];
	}
};
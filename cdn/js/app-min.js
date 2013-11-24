

/* **********************************************
     Begin _base.js
********************************************** */

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

/* **********************************************
     Begin app.js
********************************************** */


/**
 * 切换语言
 */
app.switchLanguage = function ( language ) {
	$.getScript( '/site/switch/to/' + language );
};

/**
 * 回到顶部
 */
app.goTop = function() {
	var goTop = $( '.gotop' );
	var goTopTimer;
	$( window ).bind( 'scroll', function(){
		app.noRepeat( 'gotop', function(){
			var needGoTop = $( window ).scrollTop() > ( $( window ).height() / 2 );
			needGoTop ? goTop.slideDown() : goTop.slideUp();
		}, false );
	} );
	goTop.find( 'a' ).click(function(){
		$( 'html, body' ).animate( { scrollTop : 0 }, 500 );
	});
};


/* **********************************************
     Begin form.js
********************************************** */

/**
 * 表单
 */
app.form = app.form || {

	//初始化
	init :  function ( options ) {

		options = $.extend({
			'container' : 'tr',
			'name' : 'form'
		}, options);

		var form = $( '#' + options.name );
		var elements = form.find( 'input, textarea, select' );

		//监控focus状态
		elements.focus(function(){
			$( this ).closest( options.container ).addClass( 'focus' );
		}).blur(function(){
			$( this ).closest( options.container ).removeClass( 'focus' );
		}).keydown(function(){
			$( this ).closest( options.container ).removeClass( 'success error' );
		});

		//绑定提交事件
		if ( options.submit ) {
			form.submit(function(){
				options.submit();
				return false;
			});
		}
		form.find( '.submit' ).click(function(){
			form.submit();
		});

		//绑定恢复事件
		form.find( '.reset' ).click(function(){
			form.reset();
		});

	},

	//单选框
	radioButton : {

		select : function ( one ) {
			var name = $( one ).attr( 'name' );
			var value = $( one ).attr( 'value' );
			var items = $( '.radioButton[name=' + name + ']' );
			var input = $( 'input#' + name );
			items.each( function( index, item ){
				$( item ).removeClass( 'checked' );
			} );
			$( one ).addClass( 'checked' );
			input.val( value );
		}
	},

	//多选框
	checkBox : {

		select : function ( one ) {
			var name = $( one ).attr( 'name' );
			var value = $( one ).attr( 'value' );
			var input = $( 'input#' + name );
			$( one ).toggleClass( 'checked' );
			input.click();

		}
	},

	//清除提交按钮状态
	resitSubmit : function() {
		$('button[type=submit]').removeClass('ing');
	}
};

	

/* **********************************************
     Begin key.js
********************************************** */

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

/* **********************************************
     Begin pop.js
********************************************** */


/**
 * 在弹出窗口中加载指定url
 */
app.pop = function ( url ) {
	$.get( url, function( html ) {
		app.showPop( html );
	} );
};

/**
 * 显示指定内容的弹出窗口
 */
app.showPop = function ( content, allowCancel ) {
	
	if ( typeof allowCancel === 'undefined' ) { allowCancel = true; }

	//插入弹出窗口，清除已显示的弹出窗口
	$( '#pop, #pop_mask' ).remove();
	$( 'body' ).append( '<div id="pop">' + content + '</div><div id="pop_mask"></div>' );
	
	//调整弹出窗口和阴影尺寸
	app.resizePop();
	$( '#pop_mask' ).height( $( document ).height() );
	
	//点击阴影关闭弹出窗口
	if ( allowCancel ) {
		$( '#pop_mask' ).click( function() { app.hidePop(); } );
	}
	
	//改变窗口尺寸同时更新弹出窗口
	$( window ).bind( 'resize', app.resizePop );
	
	//注册ESC热键关闭弹出窗口
	app.registerKey( app.keymap.ESC, function(){ app.hidePop(); } );
};

/**
 * 更新弹出窗口的尺寸
 */
app.resizePop = function ( ) {
	app.noRepeat( 'resizePopWindow', function(){
		
		var pop = $( '#pop' ),
			shadow = $( '#pop_mask' );
		
		//计算居中位置
		var containerWidth = app.client.mobile ? $( document ).width() : $( window ).width();
		var containerHeight = $( window ).height();
		var leftPX = ( containerWidth - pop.width() ) / 2;
		var topPX = ( containerHeight - pop.height() ) / 2 + ( app.client.mobile ? $( window ).scrollTop() : 0 );
		
		//移动设备不支持fixed，使用absolute代替
		var position = app.client.mobile ? 'absolute' : 'fixed';
		
		//若弹出窗口超出屏幕尺寸，优先保证内容可完整呈现
		if ( topPX < 0 || leftPX < 0 ) { position = 'absolute'; }
		if ( topPX < 0 ) { topPX = 0; }
		if ( leftPX < 0 ) { leftPX = 0; }
		
		//调整弹出窗口尺寸
		pop.css( { 'left' : leftPX + 'px', 'top' : topPX + 'px', 'position': position } );

	}, 16);
};

/**
 * 隐藏弹出窗口
 */
app.hidePop = function() {
	clearTimeout( app.timers.flash );
	$( '#pop, #pop_mask' ).remove();
	app.removeKey( app.keymap.ESC );
	app.callback( 'pop' );
};

/**
 * 弹出确认窗口
 */
app.confirm = function( content, fnOk, fnCancel ) {
	
	//显示确认框
	var confirmView = '<div class="content confirm"><span id="content_span">{content}</span></div><div id="pop_button_container_div"><div id="pop_button_div"><a href="javascript:;" id="pop_cancel" class="box_button l"><span>取消</span></a><a href="javascript:;" id="pop_ok" class="box_button r"><span>确定</span></a></div></div>';
	app.showPop( app.render( confirmView, { 'content' : content } ) );
	
	//为确定和取消按钮绑定点击事件
	$( '#pop_ok' ).click( function() { fnOk && fnOk(); app.hidePop(); } );
	$( '#pop_cancel' ).click( function() { fnCancel && fnCancel(); app.hidePop(); } );
	
	//修复ie7下div的宽度不继承问题
	if ( app.client.oldie ) {
		$( '#pop_button_div' ).width( $( '#pop' ).width() - 1 );
	}
};

/**
 * 弹出消息提示
 */
app.flash = function( content, delay, callback ) {
	
	//显示消息框
	content = content || '';
	var flashView = '<div class="flash box">{content}</div>';
	app.showPop( app.render( flashView, { 'content' : content.replace( /###(\w+)###/g, '<img src="' + app.servers.resource + 'images/pop/$1.gif">') } ) );
	app.callback( 'pop', callback );
	
	//延时关闭消息窗口，执行回调
	delay = delay || 2000;
	app.timers.flash = setTimeout( function() {
		app.hidePop();
	}, delay );
};


/* **********************************************
     Begin user.js
********************************************** */


/**
 * 弹出登陆框
 */
app.login = function() {
	app.pop( '/login?type=pop' );
};

/* **********************************************
     Begin board.js
********************************************** */

app.config.board = {
    'beg' : { 'x' : 8, 'y' : 8 },
    'int' : { 'x' : 16, 'y' : 16 },
    'exp' : { 'x' : 30, 'y' : 16 }
};

app.drawBoard = function (id) {
    var container = $('#board_' + id);
    if (container.data('drawed')) return;
    
    var level = container.data('level'),
        boardX = app.config.board[level].x,
        boardY = app.config.board[level].y,
        table = $('<table cellpadding="0" cellspacing="0"></table>'),
        board = container.attr('data-board').replace(/\*/g, 'b').split('');

    for (var y = 1; y <= boardY; y++) {
        var line = '';
        for (var x = 1; x <= boardX; x++) {
            line += '<td class="b' + board.shift() + '"></td>';
        }
        table.append($('<tr>' + line + '</tr>'));
    }

    container.append(table);
    container.data('drawed', true);
};


/* **********************************************
     Begin button.js
********************************************** */

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
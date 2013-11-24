
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


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

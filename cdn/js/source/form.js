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

	
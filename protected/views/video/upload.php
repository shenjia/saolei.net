<div class="video_upload box center">
	<h1>上传录像</h1>
	<p>本站目前只接受 <em>Minesweeper Arbiter 0.51</em> 版本所保存的 <em>avf</em> 录像。</p>
	<p>上传成功后的录像，需要通过管理员的审核，成绩才能计入排行。</p>
	<hr/>
	<div id="container">
		<?php 
		$this->widget('Buttons', array( 
		    'options' => array('class' => 'left'),
		    'buttons' => array(
		        array('id' => 'selectFile', 'name' => 'selectFile'),
		        array('id' => 'startUpload', 'name' => 'startUpload', 'class' => 'disabled')
		    )
		) );
		?>
	    <div id="filelist" class="form"><?= Yii::t('video', 'old_broswer')?></div>
	</div>
</div>
<?php Resource::loadJs( array( 'upload/plupload.full', 'upload/i18n/cn' ) );?>
<script type="text/javascript">
$(function(){
	var uploader = new plupload.Uploader({
		runtimes : 'gears,html5,flash,silverlight,browserplus,html4',
		browse_button : 'selectFile',
		container: 'container',
		max_file_size : '500kb',
		url : '/video/upload',
		resize : {width : 320, height : 240, quality : 90},
		flash_swf_url : '/js/upload/plupload.flash.swf',
		silverlight_xap_url : '/js/upload/plupload.silverlight.xap',
		filters : [
			{title : "Minesweeper Arbiter Videos (*.avf)", extensions : "avf"},
		]
	});

	uploader.bind('Init', function(up, params) {
		$('#filelist').html('');
		//$('#filelist').html('<div id="aoeu" class="progressBar success"><div class="title"><span class="name"><em>我的.mvf</em></span><span class="checkStatus"></span><span class="errorMessage">aoeu</span></div><span class="progress"></span></div>');
	});

	uploader.bind('FilesAdded', function(up, files) {
		for (var i in files) {
			$('#filelist').append( '<div id="' + files[i].id + '" class="progressBar">'
					+ '<div class="title"><span class="name">' + files[i].name + ' (' 
					+ plupload.formatSize(files[i].size) + ')</span><span class="checkStatus"></span><span class="message"></span></div>'
					+ '<span class="progress"></span><span class="status"></span><a href="javascript:$(\'#' + files[i].id + '\').fadeOut();void(0);" class="cancel"></a></div>' );
		}
		$('#startUpload').removeClass('disabled').addClass('active');
	});
	
	uploader.bind('FileUploaded', function(up, file, response) {
		var result = $.parseJSON( response.response );
		console.log(result);
		if ( result.error ) {
			$( '#' + file.id ).addClass( 'error' ).find( '.message' ).text( result.error.message );
			return;
		}
		$( '#' + file.id ).addClass( 'success' ).find( '.message' ).text( '<?= Yii::t('video', 'upload_success')?>' );
		$( '#' + file.id ).delay(2000).fadeOut();
	});
	
	uploader.bind('UploadComplete', function(up, files) {
		$('#startUpload').removeClass('disabled').addClass('active');
	});

	uploader.bind('UploadProgress', function(up, file) {
		$('#'+file.id).find('.progress').width(file.percent+'%');
	});
	
	uploader.bind('Error', function(up, error) {
		if ( error.file ) {
			$( '#' + error.file.id ).addClass( 'error' ).find( '.message' ).text( error.message );
			return;
		}
		//console.log(error.message);
	});

	$('#startUpload').click(function() {
		$('#startUpload').removeClass('active').addClass('disabled');
		uploader.start();
		return false;
	});

	uploader.init();
});
</script>

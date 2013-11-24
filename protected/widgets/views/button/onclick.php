<?
//确认开始
if ( $this->confirm ) {
    echo 'if(confirm(\'' . ( strlen( $this->confirm ) > 1 ? $this->confirm : '您确定吗?' ) . '\')){';
}
//按钮变状态
echo 'app.button.ing($(this));';
//直接给出代码
if ( $this->click ) {
	echo $this->click;
}
//ajax调用
else if ( $this->ajax ) {
	?>$.ajax({
type:'POST',
url:'<?= $this->ajax ?>',
<?if ( is_array( $this->data ) ):?>
data:'<?= Request::mergeParams( $this->data ) ?>',
<?elseif ( is_string( $this->data ) ):?>
data:<?= $this->data ?>,
<?endif;?>
<?if ( $this->success ):?>
success:function(data){<?= $this->success ?>;$('#<?= $this->id ?>').removeClass('ing');},
<?endif;?>
dataType:'<?= $this->dataType ?>',
error:function(e){alert(e.responseText);$('#<?= $this->id ?>').removeClass('ing');}
}).done(function(){$('#<?= $this->id ?>').removeClass('ing');})<?
}
//url跳转
else if ( $this->url ) {
    echo $this->blank ? 'window.open(\'' . $this->url . '\');'
                      : 'location.href=\'' . $this->url . '\';';
}
//确认结束
if ( $this->confirm ) {
    echo '}';
}
?>
<div id="colors" class="box">
<?
$colors = array(
	'black', 'darker', 'dark', 'grayDarker', 'gray', 'grayLighter', 'light', 'lighter', 'white',
	'red', 'pink', 'golden', 'yellow', 'green', 'cyan', 'blue', 'purple', 'magenta'
);
foreach ($colors as $color) {
	echo '<span class="colorblock ' . $color . '"></span> <span class="' . $color . '">' . $color . '</span><br/>';
}
?>
</div>
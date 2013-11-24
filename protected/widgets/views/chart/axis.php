{
	title : {
		text : '<?= $axis[ 'title' ] ?>'
	},
    <?php if ( isset( $axis[ 'categories' ] ) ): ?> 
    categories: <?= json_encode( $axis[ 'categories' ] ) ?>,
    <?php endif;?>
    labels: {
        <?php if ( isset( $axis[ 'step' ] ) ): ?>
        step : <?= $axis[ 'step' ] ?>,
        <?php endif;?>
		<?
        $name = 'chart/formatter/' . $axis[ 'type' ];
        if ( file_exists($this->getViewPath() . '/' . $name . '.php' ) ) {
		    echo 'formatter: function() {';
        	$this->render( $name );
        	echo '}';
        }
        ?>
    },
    gridLineColor: '<?= $this->lineColor ?>',
    <?php if ( $axis[ 'type' ] == 'polygon' ): ?>
    gridLineInterpolation: 'polygon',
    lineWidth: 0,
    min: 0,
    max: 99,
    tickInterval: 33,
    <?php endif;?>
    startOnTick: false,
    <?php if ( $this->polar ): ?>
    tickmarkPlacement: 'on',
	lineWidth: 0,
    <?php endif;?>
    <?php if ( isset( $axis[ 'plotBands' ] ) ): ?>
    plotBands: <?= json_encode( $axis[ 'plotBands' ] ) ?>
    <?php endif;?>
}
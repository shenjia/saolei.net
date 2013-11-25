<div id="<?= $this->id ?>" class="chart" style="<?= $this->style ?>"></div>
<script type="text/javascript">
$(function () {
    var <?= $this->id ?>;
    $(document).ready(function() {
        <?= $this->initScript ?>;
        var chart = new Highcharts.Chart({ 
            chart: {
                renderTo: '<?= $this->id ?>',
                type: '<?= $this->type ?>',
                polar: <?= $this->polar ? 'true' : 'false' ?>,
                backgroundColor: '<?= $this->background ?>',
                spacingTop: <?= $this->spacing ?>,
                spacingRight: <?= $this->spacing ?>,
                spacingBottom: <?= $this->spacing ?>,
                spacingLeft: <?= $this->spacing ?>
            },
            title: {
                text: '<?= $this->title ?>'
            },
            xAxis: <? $this->render( 'chart/axis', array( 'axis' => $this->x ) ) ?>,
            yAxis: <? $this->render( 'chart/axis', array( 'axis' => $this->y ) ) ?>,
            tooltip: {
            	<? $this->render( 'chart/tooltip/' . $this->tooltip ) ?>
            },
            plotOptions: {
                <?
                $name = 'chart/plotOptions/' . $this->type;
                if ( file_exists($this->getViewPath() . '/' . $name . '.php' ) ) {
                	$this->render( $name );
                }
                ?>
            },
            credits : {
                enabled : false
            },
            legend : <?= $this->render( 'chart/legend/' . $this->legend ) ?>,
            series: <?= json_encode( $this->series ) ?>,
            colors: <?= json_encode( $this->colors ) ?>
        });
        <?php if ( $this->showFirstOnly ):?>
        //避免多次redraw导致卡顿
        var _redraw = chart.redraw;
        chart.redraw = function(){};
        for ( var i in chart.series ) {
            chart.series[ i ].hide();
        }
        chart.series[ 0 ].show();
        chart.redraw = _redraw;
        chart.redraw();
        <?php endif;?>
        <?= $this->id ?> = chart;
    });
    
});
</script>
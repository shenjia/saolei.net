<div class="radar box">
	<h2>实力</h2>
	<?php
	$data = array();
	$grades = array();
    foreach (array('time', '3bvs') as $order) {
        foreach (array('sum', 'exp', 'int', 'beg') as $level) {
            if ($user->scores) {
                $raw_score = $user->scores->score($level, $order);
                $score = $user->scores->score($level, $order, false);
                $percent = Assess::percent($level, $order, $raw_score);
                $grade = Assess::grade($level, $order, $raw_score);
            } else {
                $score = 0;
                $percent = 0;
                $grade = '?';
            }
            array_push($grades, $grade);
            array_push($data, array(
                'name'    => Yii::t('video', $level) . Yii::t('video', $order),
                'score'   => $score,
                'y'       => $percent,
                'grade'   => $grade,
            ));
        }
    }
	$this->widget('Chart', array(
	    'type' => 'area',
	    'polar' => true,
	    'series' => array(array(
	        'name' => $user->chinese_name,
	        'data' => $data
	    )),
	    'x' => array(
	        'type' => 'grade',
	    	'categories' => $grades
	    ),
	    'y' => 'polygon',
	    'legend' => 'hide',
	    'tooltip' => 'score',
	    'spacing' => 20,
	    'initScript' => 'app.grades={SSS:"#E6DB74", SS:"#E6DB74", S:"#E6DB74", A:"#ff0080", B:"#df00a6", C:"#bf00cc", D:"#a000e5", E:"#8000ff", F:"#6600cc"}'
	))?>
</div>
<?php
$grade = Assess::grade($level, $order, $score);
$class = substr($grade, 0, 1);
?>
<span class="grade"><em class="<?= $class?>"><?= $grade ?></em></span>


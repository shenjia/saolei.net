<?php
if (!$title && $placehold) {
    echo '<span class="title"></span>';
} else {
    echo $link ? CHtml::openTag('a', array('href' => '/page/titles', 'class' => 'title', 'target' => '_blank'))
               : CHtml::openTag('span', array('class' => 'title'));
    ?><em class="<?= TitleConfig::$classes[$title] ?>"><?= $title ?></em><?php
    echo $link ? CHtml::closeTag('a')
               : CHtml::closeTag('span');
}
?>
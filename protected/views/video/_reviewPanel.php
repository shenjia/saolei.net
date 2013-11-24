<?php
$role = UserAuth::getRole();
$auth_checked = (UserAuth::isManager($role) && $video->status == VideoConfig::STATUS_NORMAL) || UserAuth::isAdministrator($role); 
$this->widget('Buttons', array( 
    'options' => array(
    	'class' => 'left', 'size' => 'small'
    ),
    'buttons' => array(
        array(
        	'name' => 'pass', 'class' => 'active',
        	'ajax' => '/video/review/status/' . VideoConfig::STATUS_REVIEWED . '/id/' . $video->id,
            'display' => $auth_checked && $video->status != VideoConfig::STATUS_REVIEWED,
            'confirm' => $video->status == VideoConfig::STATUS_BANNED
        ),
        array(
        	'name' => 'ban', 'class' => 'danger', 
        	'ajax' => '/video/review/status/' . VideoConfig::STATUS_BANNED . '/id/' . $video->id,
            'display' => $auth_checked && $video->status != VideoConfig::STATUS_BANNED,
        	'confirm' => true
        )
    )
) );
?>
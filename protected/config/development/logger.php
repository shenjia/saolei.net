<?php
/**
 * Logger
 */
$GLOBALS['LOG'] = array(
    // LOG_LEVEL_FATAL|LOG_LEVEL_WARNING||LOG_LEVEL_DEBUG
    'intLevel'            => 0x17,
    // 日志文件路径，wf日志为bingo.log.wf
    'strLogFile'        => dirname(__FILE__).'/../../runtime/business.log',
    // 0表示无限
    'intMaxFileSize'    => 0,
    // 特殊日志路径，根据需要自行配置
    'arrSelfLogFiles'    => array()
);
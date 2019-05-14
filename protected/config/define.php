<?php
define('DOC_ROOT', __DIR__.'/../..');

define('ENV', 'development');
//define('ENV', 'production');

/**
 * 环境配置
 */
require_once __DIR__ . '/' . ENV . '/debug.php';
require_once __DIR__ . '/' . ENV . '/resource.php';
require_once __DIR__ . '/' . ENV . '/logger.php';

/**
 * 错误代码
 */
require_once __DIR__ . '/ErrorCode.php';

/**
 * 缺省配置
 */
define('PAGECOUNT', 15);
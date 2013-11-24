<?php
require_once __DIR__.'/../config/VideoConfig.php';
require_once __DIR__.'/../helpers/Parser.php';
require_once __DIR__.'/../helpers/BitBoard.php';

$filepath = __DIR__.'/aaa.avf';
var_dump(Parser::parse($filepath));
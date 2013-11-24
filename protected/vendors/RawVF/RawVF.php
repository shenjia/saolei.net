<?php
require_once __DIR__.'/../../config/define.php';
require_once __DIR__.'/../../helpers/Logger.php';
class RawVF
{
    
    public static function parse($type, $file) 
    {
        $dir = __DIR__;
        $parser = $type . '_parser';
        $filepath = addcslashes($file, ' ()');
        $result = explode("\n", `$dir/$parser $filepath 2>&1`);
        
        if (strstr($result[0], 'RawVF')) {
            Logger::notice('parse ok!', 100, array('file' => $file));
            return $result;
        } else {
            Logger::warning('parse failed!', 100, array('file' => $file, 'result' => $result[0]));
            return ErrorCode::parseFailed;
        }
    }
}
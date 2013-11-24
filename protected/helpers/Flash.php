<?php
class Flash
{
    const COLOR_ERROR   = 'magenta';
    const COLOR_NOTICE  = 'golden';
    const COLOR_SUCCESS = 'green';
    
    public static function error($msg, $callback = '') 
    {
        self::send($msg, self::COLOR_NOTICE, $callback);
    }
    
    public static function notice($msg, $callback = '') 
    {
        self::send($msg, self::COLOR_NOTICE, $callback);
    }

    public static function success($msg, $callback = '') 
    {
        self::send($msg, self::COLOR_SUCCESS, $callback);
    }

    public static function send($msg, $color = self::COLOR_NOTICE, $callback = '') 
    {
        Javascript::flash(self::getMessage($msg), $color, $callback);
    }
    
    private static function getMessage($msg, $catalog = 'error') 
    {
        if (strpos($msg, '/')) {
            $parts = explode('/', $msg);
            $msg = array_pop($parts);
            $catalog = implode('/', $parts);
        }
        return Yii::t($catalog, $msg);
    }
}
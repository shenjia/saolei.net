<?php
class BitBoard
{
    public static function pack($input) 
    {
        if (!is_string($input)) return null;
        $str = '';
        foreach (str_split($input, 4) as $v) {
            $str .= base_convert($v, 2, 16);
        }
        return pack('H*', $str);
    }   
    
    public static function unpack($input) 
    {
        if (!is_string($input)) return null;
        $bin = '';
        $value = unpack('H*', $input);
        $value = str_split($value[1], 1);
        foreach ($value as $v){
            $b = str_pad(base_convert($v, 16, 2), 4, '0', STR_PAD_LEFT);
            $bin .= $b;
        }
        return $bin;        
    }
}
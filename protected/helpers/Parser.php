<?php
require_once __DIR__.'/../vendors/RawVF/RawVF.php';
class Parser
{
    private static $valid_types = array(
        'avf', 'mvf'
    );
    
    private static $valid_levels = array(
        'beg', 'int', 'exp'
    );
    
    private static $valid_modes = array(
        'classic'
    );
    
    private static $invalid_version = array(
        '<=0.96'
    );
    
    public static function parse($file) 
    {
        if (!file_exists($file)) {
            Logger::warning('file not exists!', 100, array('file' => $file));
            return ErrorCode::parseFileNotExists;            
        }
        
        $pathinfo = pathinfo($file);
        if (!in_array($pathinfo['extension'], self::$valid_types)) {
            Logger::warning('invalid video type!', 100, array('file' => $file));
	        return ErrorCode::parseInvalidType;
        }
        
        $parsed = RawVF::parse($pathinfo['extension'], $file);
        if (is_int($parsed)) return $parsed;
        
        $data = self::format($parsed);
        if ($content = file_get_contents($file)) {
            $data['hash'] = md5($content);
        }
    
        if (in_array($data['version'], self::$invalid_version)) {
            Logger::warning('invalid software version!', 100, array('file' => $file, 'version' => $data['version']));
	        return ErrorCode::parseInvalidVersion;
        }
        
        if (!in_array($data['level'], self::$valid_levels)) {
            Logger::warning('invalid video level!', 100, array('file' => $file, 'level' => $data['level']));
	        return ErrorCode::parseInvalidLevel;
        }
        
        if (!in_array($data['mode'], self::$valid_modes)) {
            Logger::warning('invalid video mode!', 100, array('file' => $file, 'mode' => $data['mode']));
	        return ErrorCode::parseInvalidMode;
        }
        
        if (isset($data['solved3bv']) && $data['solved3bv'] < $data['3bv']) {
            Logger::warning('not finished yet!', 100, array('file' => $file));
	        return ErrorCode::parseNotFinished;
        }
        
        return $data;
    }
    
    public static function format($result) 
    {
        if (!$result) return $result;
        $formatted = array();
        foreach ($result as $i => $line) {
            $parts = explode(':', $line);
            if (count($parts) == 1) continue;
            $formatted[strtolower($parts[0])] = $parts[1] == '' ? $i + 1 : trim($parts[1]);
        }
        $formatted['level'] = strtolower(substr($formatted['level'], 0, 3));
        $formatted['program'] = str_replace('Minesweeper ', '', $formatted['program']);
        $formatted['player'] = mb_convert_encoding($formatted['player'], 'utf-8', 'gb2312');
        $formatted['board'] = self::renderBoard(array_slice($result, $formatted['board'], $formatted['height']));
        $formatted['events'] = array_slice($result, $formatted['events']);
        $formatted['noflag'] = 1;
        foreach ($formatted['events'] as $e) {
            if (strpos($e, 'rc') > 0) {
                $formatted['noflag'] = 0;
                break;
            }
        }
        return $formatted;
    }
    
    public static function renderBoard($board) 
    {
        $boardX = strlen($board[0]);
        $boardY = count($board);
        
        for ($y = 0; $y < $boardY; $y++) {
            for ($x = 0; $x < $boardX; $x++) {
                if ($board[$y][$x] == '*') continue;
                $count = 0;
                if (isset($board[$y-1][$x-1]) && $board[$y-1][$x-1] == '*') $count++;
                if (isset($board[$y-1][$x])   && $board[$y-1][$x]   == '*') $count++;
                if (isset($board[$y-1][$x+1]) && $board[$y-1][$x+1] == '*') $count++;
                if (isset($board[$y][$x-1])   && $board[$y][$x-1]   == '*') $count++;
                if (isset($board[$y][$x+1])   && $board[$y][$x+1]   == '*') $count++;
                if (isset($board[$y+1][$x-1]) && $board[$y+1][$x-1] == '*') $count++;
                if (isset($board[$y+1][$x])   && $board[$y+1][$x]   == '*') $count++;
                if (isset($board[$y+1][$x+1]) && $board[$y+1][$x+1] == '*') $count++;
                $board[$y][$x] = $count;
            }
        }
        return implode('', $board);
    }
    
    public static function verify($data)
    {
        return $data;
    } 
}
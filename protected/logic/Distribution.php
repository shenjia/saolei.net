<?php
require_once __DIR__.'/../logic/UserScores.php';
require_once __DIR__.'/../models/DistributionModel.php';

class Distribution 
{
    public static $distribution;
    
    public static function get($name) 
    {
        if (!isset(self::$distribution)) {
            $distribution = DistributionModel::model()->find(array(
                'order' => 'id desc'
            ));
            if (!$distribution) $distribution = self::init();
            self::$distribution = $distribution->getAttributes();
        }
        if (isset(self::$distribution[$name])) {
            return strpos(self::$distribution[$name], ',') > 0 
                ? explode(',', self::$distribution[$name]) 
                : self::$distribution[$name];
        } else {
            Logger::warning('get distribution error!', 100, array('name' => $name));
        }
    }
    
    public static function init()
    {
        $distribution = new DistributionModel();
        foreach (RankingConfig::$levels as $level) {
            foreach (array('time', '3bvs') as $order) {
                $distribution->{$level . '_' . $order} = self::generateGradeDistribution($level, $order);
            }
        }
        $distribution->title = self::generateTitleDistribution();
        $distribution->size = UserScores::count(TitleConfig::LEVEL, TitleConfig::ORDER);
        $distribution->create_time = time();
        if ($distribution->save()) {
            return $distribution;    
        } else {
            Logger::warning('init distribution failed', 100);
        }
    }
    
    private static function generateGradeDistribution($level, $order) 
    {
        $distribution = '';
        $total = UserScores::count($level, $order);
        $last = end(GradeConfig::$grades); 
        foreach (GradeConfig::$grades as $grade) {
            if (in_array($grade, GradeConfig::$fixed_grades)) {
                $fixed = GradeConfig::$distribution[$grade];
                $distribution .= ',' . UserScores::getDistributionScore($level, $order, $fixed);
                continue;
            } else if ($grade == $last) {
                $direct = RankingConfig::$order_direction[$order] ? 'asc' : 'desc';
                $distribution .= ',' . UserScores::getDistributionScore($level, $order, 0, $direct);
            } else {
                $offset = $fixed + intval(($total - $fixed) * GradeConfig::$distribution[$grade]);
                $distribution .= ',' . UserScores::getDistributionScore($level, $order, $offset);
            }
        }
        return trim($distribution, ',');
    }
    
    private static function generateTitleDistribution() 
    {
        $distribution = '';
        $total = UserScores::count(TitleConfig::LEVEL, TitleConfig::ORDER);
        $last = end(TitleConfig::$titles); 
        foreach (TitleConfig::$titles as $title) {
            if (in_array($title, TitleConfig::$fixed_titles)) {
                $fixed = TitleConfig::$distribution[$title] - 1;
                $distribution .= ',' . UserScores::getDistributionScore(TitleConfig::LEVEL, TitleConfig::ORDER, $fixed);
                continue;
            } else if ($title == $last) {
                $direct = RankingConfig::$order_direction[TitleConfig::ORDER] ? 'asc' : 'desc';
                $distribution .= ',' . UserScores::getDistributionScore(TitleConfig::LEVEL, TitleConfig::ORDER, 0, $direct);
            } else {
                $rate = TitleConfig::$distribution[$title];
                $offset = $fixed + intval(($total - $fixed) * $rate);
                $distribution .= ',' . UserScores::getDistributionScore(TitleConfig::LEVEL, TitleConfig::ORDER, $offset);
            }
        }
        return trim($distribution, ',');
    }
}
<?php
require_once __DIR__.'/../models/NewsModel.php';

class News
{
    public static function publish($type, &$user, $refId = 0, $details = array(), $time = null) 
    {
        $news = new NewsModel();
        $news->type = $type;
        $news->user = $user->id;
        $news->user_score = $user->scores->{TitleConfig::LEVEL . '_' . TitleConfig::ORDER};
        $news->reference = $refId;
        $news->details = $details;
        $news->create_time = isset($time) ? $time : time();
        $news->save();
    }
    
    public static function getRecentNews($type = null, $user = null, $cursor = null, $limit = PAGECOUNT) 
    {
        $condition = '1=1';
        if ($user) $condition .= ' and user=' . $user;
        if ($cursor) $condition .= ' and `t`.id<' . $cursor;
        if ($type) $condition .= ' and type=' . $type;
        return NewsModel::model()->with('author')->findAll(array(
            'condition' => $condition,
            'order'     => '`t`.id desc',
            'offset'    => $offset,
            'limit'     => $limit
        ));
    }
    
}
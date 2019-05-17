<?php
require_once __DIR__.'/../models/CommentModel.php';

class Comment 
{
    public static function getList($videoId, $offset, $limit) 
    {
        return CommentModel::model()->with('author')->findAll(array(
            'condition' => 'video=' . intval($videoId),
            'order'     => '`t`.id desc',
            'offset'    => $offset, 
            'limit'     => $limit
        ));
    }
    
    public static function init(&$data) 
    {
        $comment = new CommentModel();
        foreach ($data as $key => $value) {
            $comment->$key = $value;
        }
        $comment->status = CommentConfig::STATUS_NORMAL;
        $comment->create_time = time();
        $comment->update_time = time();
        if ($result = $comment->save()) {
            $data['id'] = $comment->id;
        }
        return $result;
    }
    
}
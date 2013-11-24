<?php
class UserModel extends BaseModel
{
    public $avatar = '0';
    
    public function tableName() 
    {
        return 'user';
    }    
    
    public function relations() 
    {
        return array(
            'info' => array(self::HAS_ONE, 'UserInfoModel', 'id'),
            'auth' => array(self::HAS_ONE, 'UserAuthModel', 'id'),
            'stat' => array(self::HAS_ONE, 'UserStatModel', 'id'),
            'scores' => array(self::HAS_ONE, 'UserScoresModel', 'id'),
            'scores_nf' => array(self::HAS_ONE, 'UserScoresNFModel', 'id'),
            'signatures' => array(self::HAS_MANY, 'UserSigModel', 'user'),
            'news' => array(self::HAS_MANY, 'NewsModel', 'user')
        );
    }
    
    public function getTitle()
    {
        if ($this->scores) {
            $score = $this->scores->score(TitleConfig::LEVEL, TitleConfig::ORDER, true);
            return Assess::title($score);
        } else {
            return '';
        }
    }
}
<?php
class UserInfoModel extends BaseModel
{
    public $mouse = '杂牌';
    public $pad = '杂牌';
    
    public function tableName() 
    {
        return 'user_info';
    }
}
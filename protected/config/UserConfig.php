<?php
class UserConfig 
{
    const ROLE_UNKNOWN       = -1;
    const ROLE_PLAYER        = 0;
    const ROLE_MANAGER       = 10;
    const ROLE_ADMINISTRATOR = 100;
    
    const INFO_QQ_LIMIT          = 15;
    const INFO_NICKNAME_LIMIT    = 10;
    const INFO_MOUSE_LIMIT       = 30;
    const INFO_PAD_LIMIT         = 30;
    const INFO_SELF_INTRO_LIMIT  = 50;
    const INFO_INTEREST_LIMIT    = 50;
    
    const STATUS_NORMAL = 0;
    const STATUS_BANNED = 10;
    
    const NEWS_NUMBER = 5;
    const NEWS_PAGECOUNT = 15;
    
    public static $roles = array(
        self::ROLE_ADMINISTRATOR,
        self::ROLE_PLAYER,
        self::ROLE_MANAGER
    );
}
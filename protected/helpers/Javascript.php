<?php
class Javascript
{
    /**
     * 跳转到指定url
     * @param string $url
     * @param boolean $wrap
     */
    public static function location( $url, $wrap = false )
    {
        self::script( 'location.href=\'' . $url . '\'', $wrap );
        exit();
    }

    /**
     * 刷新页面
     * @param boolean $wrap
     */
    public static function refresh( $wrap = false )
    {
        self::script( 'location.reload()', $wrap );
        exit();
    }

    /**
     * 弹出提示框
     * @param string $msg
     */
    public static function alert( $msg = null, $wrap = false )
    {
        if ( $msg ) {
            self::script( 'alert(\'' . $msg .'\')', $wrap );
        }
    }
    
    /**
     * 弹出消息框
     * @param string $message
     * @param string $callback
     */
    public static function flash ( $msg, $color = 'golden', $callback = null )
    {
        $flash = CHtml::tag( 'span', array( 'class' => $color ), $msg, true );
        if (Yii::app()->request->isAjaxRequest) {
            echo "app.flash('{$flash}',null,function(){{$callback}});";
        } else {
            Yii::app()->user->setFlash( 'flash', $flash );
            if ( $callback ) Yii::app()->user->setFlash( 'flash_callback', $callback );
        }
    }

    /**
     * 输出javascript
     */
    public static function script( $script, $wrap = false )
    {
        echo $wrap ? CHtml::script( $script ) : $script . ';';
    }

    /**
     * 注册一段JS
     */
    public static function register( $scripts = array() )
    {
        foreach ( $scripts as $for => $script ) {
            Yii::app()->clientScript->registerScript( $for, $script, CClientScript::POS_READY );
        }
    }
}

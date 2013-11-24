<?php
class ErrorHandler extends CErrorHandler
{
    protected function handleException($exception) 
    {
        if ($exception instanceof FlashException) {
            $msg = $exception->getMessage();
            if (strpos($msg, ':')) {
                $parts = explode(':', $msg);
                $msg = $parts[1];
                $color_name = 'Flash::COLOR_' . strtoupper($parts[0]);
                $color = defined($color_name) ? constant($color_name) : Flash::COLOR_ERROR;
            } else {
                $color = Flash::COLOR_ERROR;
            }
            Flash::send($msg, $color);
            Yii::app()->end();
        } else {
            return parent::handleException($exception);
        }
    }
}
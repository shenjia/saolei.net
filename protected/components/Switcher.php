<?php 
class Switcher extends CWidget
{
    public $id;
    public $name = 'switcher';
    public $param;
    public $cases = array();
    public $default;
    public $current;
    
    public function init()
    {
        if (!isset($this->id)) $this->id = 'Switcher_' . uniqid();
        if (!Yii::app()->user->hasState($this->name)) {
            Yii::app()->user->setState($this->name, $this->default);
        }
        if ($case = Request::getParam($this->param)) {
            if (in_array($case, $this->cases)) {
                Yii::app()->user->setState($this->name, $case);
            }
        }
        $this->current = Yii::app()->user->getState($this->name);
    }
    
    public function run()
    {
        $this->render('switcher/switcher');
    }
    
    protected function generateURL($case) 
    {
        $params = URL::parseParams(Yii::app()->request->querystring);
        $params[$this->param] = $case;
        return '/' . Yii::app()->request->pathinfo . '?' . URL::generateParams($params);
    }   
}
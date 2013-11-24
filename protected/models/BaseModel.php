<?php
require_once __DIR__.'/../vendors/allain/ActiveRecordIterator.php';

class BaseModel extends CActiveRecord
{
    public static function model($className = null) 
    {
        return parent::model($className ? $className : get_called_class());
    }
    
    public function tableName()
    {
        $class = str_replace( 'Model', '', get_called_class() );
        $parts = preg_split( '/(?=[A-Z])/', $class );
        $table = trim( strtolower( implode( '_', $parts ) ), '_' );
    }
    
    public function iterate($criteria = array(), $pageCount = PAGECOUNT)
    {
        $dataProvider = new CActiveDataProvider(get_called_class());
        $dataProvider->setCriteria($criteria);
        return new ActiveRecordIterator($dataProvider, $pageCount);
    }    
}
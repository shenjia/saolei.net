<?php
class CommentForm extends CFormModel
{
    public $video;
	public $user;
	public $content;
	
	public function rules()
	{
		return array(
			array( 'content', 'length', 'encoding' => 'utf-8', 'max' => CommentConfig::CONTENT_LIMIT),
		);
	}
	
	public function save()
	{
	    $data = $this->attributes;
        return Comment::init($data); 	    
	}
	
	public function attributeLabels()
	{
		return array(
		    'content' => Yii::t('form/comment-form', 'content'),
		);
	}
	
}
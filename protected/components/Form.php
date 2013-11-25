<?php
class Form extends CActiveForm 
{
    public $enableAjaxValidation = true;
	public $enableClientValidation = true;
	public $clientOptions = array(
		'inputContainer'=>'tr',
		'validateOnSubmit'=>true,
        'beforeValidate'=>'app.form.resetSubmit'
	);
	public $htmlOptions = array(
		'autocomplete'=>'off'
	);

	/**
	 * 显示检查状态和提示
	 */
	public function hint ( $model, $attribute, $htmlOptions = array() ) 
	{
		Value::append( $htmlOptions[ 'class' ], $attribute );
		Value::append( $htmlOptions[ 'class' ], 'hintMessage' );
		$content = Yii::t( 'form/' . $this->id, $attribute . '_hint' );
		$tags = CHtml::tag( 'span', array( 'class' => 'checkStatus' ), '', true );
		$tags .= CHtml::tag( 'div', $htmlOptions, $content, true );
		return $tags;
				
	}

	/**
	 * 模拟单选框列表
	 * @see CActiveForm::radioButtonList()
	 */
	public function radioButtonList ( $model, $attribute, $data, $htmlOptions = array() ) 
	{
		$tags = CHtml::tag( 'div', $htmlOptions );
		foreach ( $data as $name => $value ) {
			$htmlOptions[ 'value' ] = $value;
			$htmlOptions[ 'content' ] = $name;
			$tags .= $this->radioButton( $model, $attribute, $htmlOptions );
		}
		$tags .= CHtml::hiddenField( CHtml::activeName( $model, $attribute ), current( $data ), array( 'id' => $attribute ) );
		$tags .= CHtml::closeTag( 'div' );
		return $tags;
	}
	
	/**
	 * 模拟单选框
	 * @see CActiveForm::radioButton()
	 */
	public function radioButton ( $model, $attribute, $htmlOptions = array() )
	{
		$htmlOptions[ 'name' ] = $attribute;
		$htmlOptions[ 'onclick' ] = 'app.form.radioButton.select(this)';
		Value::append( $htmlOptions[ 'class' ], 'radioButton' );
		if ( $model->$attribute == $htmlOptions[ 'value' ] ) {
			Value::append( $htmlOptions[ 'class' ], 'checked' );
		}
		$content = Value::pickFrom( $htmlOptions, 'content' );
		return CHtml::tag( 'span', $htmlOptions, $content, true );
	}
	
	/**
	 * 模拟多选框
	 * @see CActiveForm::checkBox()
	 */
	public function checkBox ( $model, $attribute, $htmlOptions = array() )
	{
		$htmlOptions[ 'name' ] = $attribute;
		$htmlOptions[ 'onclick' ] = 'app.form.checkBox.select(this)';
		Value::append( $htmlOptions[ 'class' ], 'checkBox' );
		if ( $checked = $model->$attribute ) {
			Value::append( $htmlOptions[ 'class' ], 'checked' );
		}
		$tags = CHtml::tag( 'span', $htmlOptions, $model->getAttributeLabel( $attribute ), true );
		$tags .= CHtml::checkBox( CHtml::activeName( $model, $attribute ), $checked, array( 'class' => 'hide', 'id' => $attribute ) );
		return $tags;
	}
}
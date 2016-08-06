<?php

/**
 * PaymentForm class.
 * PaymentForm is the data structure for keeping
 */
class PaymentForm extends CFormModel
{
	public $amount_tendered;
	public $change;
	public $type;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('amount_tendered', 'required'),
			array('change, type','safe'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'amount_tendered'=>Yii::t('order','Amount Tendered'),
			'change'=>Yii::t('order','Change'),
			'type'=>Yii::t('order','Order Type'),
		);
	}

}

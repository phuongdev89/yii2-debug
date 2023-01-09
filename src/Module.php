<?php

namespace phuong17889\debug;
class Module extends \yii\debug\Module
{

    public $traceLine = '<a href="phpstorm://open?url=file://{file}&line={line}">{text}</a>';

	/**
	 * {@inheritdoc}
	*/
	public function init()
	{
		parent::init();
		$this->viewPath = '@vendor/yiisoft/yii2-debug/src/views';
	}
}

<?php

class FilesUploadWidget extends CWidget {
	/**
	 * @var EActiveRecord
	 */
	public $model;

	public $buttonTitle;

	public function init () {
		parent::init();

		if ( !$this->model instanceof CActiveRecord ) {
			throw new CException('Model must be instanceof CActiveRecord');
		}

		if ( !$this->buttonTitle ) {
			$this->buttonTitle = Yii::t('filesModule.common', 'Добавить файл');
		}
	}

	public function run () {
		$this->_registerAssets();
		$this->render('filesUpload',
			array(
				'model'       => $this->model,
				'modelName'   => $this->model->resolveClassName(),
				'buttonTitle' => $this->buttonTitle,
			));
	}

	private function _registerAssets () {
		$cs = Yii::app()->getClientScript();

		$cs->registerCssFile(Yii::app()->getModule('files')->getAssetsUrl() . '/css/uploads.css');
	}
}
<?php

class CommentsModule extends CWebModule {
	public $backendController = 'commentsBackend';
	private $_assetsUrl;

	public function init () {
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
		                      'comments.models.*',
		                      'comments.components.*'
		                 ));
	}

	public function getAssetsUrl () {
		if ( $this->_assetsUrl === null ) {
			$this->_assetsUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.comments.assets'));
		}
		return $this->_assetsUrl;
	}

	public static function register () {
		self::_addUrlRules();
		self::_addModelsRelations();
		self::_addBehaviors();
		self::_setImport();
		Yii::app()->pd->addAdminModule('comments', 'Models management');
	}

	private static function _addUrlRules () {
		Yii::app()->pd->addUrlRules(array(
		                                 'yiiadmin/comments/backend/<action:\w+>/*' => 'comments/commentsBackend/<action>',
		                                 'yiiadmin/comments/backend/*'              => 'comments/commentsBackend',

		                                 'comments/<action:\w+>/*'                  => 'comments/default/<action>',
		                                 'comments/<controller:\w+>/<action:\w+>/*' => 'comments/<controller>/<action>',
		                            ));
	}

	private static function _addModelsRelations () {
		Yii::app()->pd->addRelations('Comment',
			'user',
			array(
			     CActiveRecord::BELONGS_TO,
			     'User',
			     'ownerId',
			),
			'application.modules.comments.models.*');

		Yii::app()->pd->addRelations('User',
			'commentsCount',
			array(
			     CActiveRecord::STAT,
			     'Comment',
			     'ownerId',
			),
			'application.modules.comments.models.*');

		Yii::app()->pd->addRelations('modules\torrents\models\TorrentGroup',
			'commentsCount',
			array(
			     CActiveRecord::HAS_ONE,
			     'CommentCount',
			     'modelId',
			     'condition' => 'commentsCount.modelName = :modelName',
			     'params'    => array(
				     'modelName' => 'modules_torrents_models_TorrentGroup'
			     )
			),
			'application.modules.comments.models.*');

		Yii::app()->pd->addRelations('modules\torrents\models\TorrentGroup',
			'comments',
			array(
			     CActiveRecord::HAS_MANY,
			     'Comment',
			     'modelId',
			     'condition' => 'modelName = :modelName',
			     'params'    => array(
				     'modelName' => 'modules_torrents_models_TorrentGroup'
			     )
			),
			'application.modules.comments.models.*');

		Yii::app()->pd->addRelations('modules\blogs\models\BlogPost',
			'commentsCount',
			array(
			     CActiveRecord::HAS_ONE,
			     'CommentCount',
			     'modelId',
			     'condition' => 'modelName = :modelName',
			     'params'    => array(
				     'modelName' => 'modules_blogs_models_BlogPost'
			     )
			),
			'application.modules.comments.models.*');
	}

	private static function _addBehaviors () {
		Yii::app()->pd->registerBehavior('modules\torrents\models\TorrentGroup',
			array(
			     'deleteComments' => array(
				     'class' => 'application.modules.comments.behaviors.DeleteCommentsBehavior'
			     )
			));
		Yii::app()->pd->registerBehavior('BlogPost',
			array(
			     'deleteComments' => array(
				     'class' => 'application.modules.comments.behaviors.DeleteCommentsBehavior'
			     )
			));
	}

	private static function _setImport() {
		Yii::app()->pd->setImport(
			array('application.modules.comments.models.*')
		);
	}
}

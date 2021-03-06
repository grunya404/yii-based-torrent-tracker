<?php
$this->getController()->beginClip('afterContent');

$cs = Yii::app()->getClientScript();
$cs->registerScript(__FILE__,
	'$("#restoreModalLink").click(function(){$("#loginModal").modal("hide")});
		$(".modal").on("hidden", function () {$(".help-block.error").hide();});');

$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'loginModal')); ?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
	array(
	     'id'                     => 'login-form',
	     'enableAjaxValidation'   => true,
	     'enableClientValidation' => false,
	     'action'                 => Yii::app()->createUrl('/user/default/login'),
	     'clientOptions'          => array(
		     'validateOnSubmit' => true,
		     'beforeValidate'   => 'js:function(form){
			        form.find("button[type=submit]").button("reset");
			        return true;
			     }',
	     ),
	)); ?>
<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php echo Yii::t('userModule.common', 'Вход'); ?></h4>
	</div>

	<div class="modal-body">
		<?php Yii::app()->getController()->renderPartial('application.modules.user.views.default._login',
			array(
			     'model' => $loginModel,
			     'form'  => $form
			)); ?>
	</div>

	<div class="modal-footer">
			<?php $this->widget('bootstrap.widgets.TbButton',
				array(
				     'buttonType'  => 'submit',
				     'type'        => 'primary',
				     'loadingText' => Yii::t('userModule.common', 'Идет вход...'),
				     'label'       => Yii::t('userModule.common', 'Войти'),
				)); ?>

			<?php $this->widget('bootstrap.widgets.TbButton',
				array(
				     'buttonType'  => 'link',
				     'type'        => 'link',
				     'label'       => Yii::t('userModule.common', 'Забыли пароль?'),
				     'url'         => Yii::app()->createUrl('/user/default/restore'),
				     'id'          => 'restoreModalLink',
				     'htmlOptions' => array(
					     'data-toggle' => 'modal',
					     'data-target' => '#restoreModal',
				     ),
				)); ?>
	</div>

<?php $this->endWidget(); ?><!-- endform -->
<?php $this->endWidget(); ?><!-- endmodal -->


<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'registerModal')); ?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
	array(
	     'id'                   => 'register-form',
	     'enableAjaxValidation' => true,
	     //'enableClientValidation' => true,
	     'action'               => Yii::app()->createUrl('/user/default/register'),
	     'clientOptions'        => array(
		     'validateOnSubmit' => true,
		     'beforeValidate'   => 'js:function(form){
			        form.find("button[type=submit]").button("reset");
			        return true;
			     }',
	     ),
	));
?>
<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php echo Yii::t('userModule.common', 'Регистрация'); ?></h4>
	</div>

	<div class="modal-body">
	<?php Yii::app()->getController()->renderPartial('application.modules.user.views.default._register',
		array(
		     'model' => $registerModel,
		     'form'  => $form,
		)); ?>
	</div>

	<div class="modal-footer">
			<?php $this->widget('bootstrap.widgets.TbButton',
				array(
				     'buttonType'  => 'submit',
				     'type'        => 'primary',
				     'loadingText' => Yii::t('userModule.common', 'Идет регистрация...'),
				     'label'       => Yii::t('userModule.common', 'Зарегистрироваться'),
				)); ?>
	</div>

<?php $this->endWidget(); ?><!-- endform -->
<?php $this->endWidget(); ?><!-- endmodal -->

<?php $this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'restoreModal')); ?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm',
	array(
	     'id'                   => 'restore-form',
	     'enableAjaxValidation' => true,
	     //'enableClientValidation' => true,
	     'action'               => Yii::app()->createUrl('/user/default/restore'),
	     'clientOptions'        => array(
		     'validateOnSubmit' => true,
	     ),
	));
?>

<div class="modal-header">
		<a class="close" data-dismiss="modal">&times;</a>
		<h4><?php echo Yii::t('userModule.common', 'Восстановить пароль'); ?></h4>
	</div>

	<div class="modal-body">
	<?php Yii::app()->getController()->renderPartial('application.modules.user.views.default._restore',
		array(
		     'model' => $restoreModel,
		     'form'  => $form
		)); ?>
	</div>

	<div class="modal-footer">
		<?php $this->widget('bootstrap.widgets.TbButton',
			array(
			     'buttonType' => 'submit',
			     'type'       => 'primary',
			     'label'      => Yii::t('userModule.common', 'Восстановить'),
			)); ?>
	</div>

<?php $this->endWidget(); ?><!-- endform -->
<?php $this->endWidget(); ?><!-- endmodal -->

<?php $this->getController()->endClip('afterContent'); ?>
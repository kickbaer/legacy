<?php
/* @var $this TestController */
/* @var $model Test */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'test-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'player1Id'); ?>
		<?php echo $form->textField($model,'player1Id'); ?>
		<?php echo $form->error($model,'player1Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'player2Id'); ?>
		<?php echo $form->textField($model,'player2Id'); ?>
		<?php echo $form->error($model,'player2Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'player3Id'); ?>
		<?php echo $form->textField($model,'player3Id'); ?>
		<?php echo $form->error($model,'player3Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'player4Id'); ?>
		<?php echo $form->textField($model,'player4Id'); ?>
		<?php echo $form->error($model,'player4Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'score1'); ?>
		<?php echo $form->textField($model,'score1'); ?>
		<?php echo $form->error($model,'score1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'score2'); ?>
		<?php echo $form->textField($model,'score2'); ?>
		<?php echo $form->error($model,'score2'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
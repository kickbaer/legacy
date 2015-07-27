<?php
/* @var $this TestController */
/* @var $model Test */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'player1Id'); ?>
		<?php echo $form->textField($model,'player1Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'player2Id'); ?>
		<?php echo $form->textField($model,'player2Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'player3Id'); ?>
		<?php echo $form->textField($model,'player3Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'player4Id'); ?>
		<?php echo $form->textField($model,'player4Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'score1'); ?>
		<?php echo $form->textField($model,'score1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'score2'); ?>
		<?php echo $form->textField($model,'score2'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
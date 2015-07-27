<?php
/* @var $this TestController */
/* @var $data Test */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('player1Id')); ?>:</b>
	<?php echo CHtml::encode($data->player1Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('player2Id')); ?>:</b>
	<?php echo CHtml::encode($data->player2Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('player3Id')); ?>:</b>
	<?php echo CHtml::encode($data->player3Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('player4Id')); ?>:</b>
	<?php echo CHtml::encode($data->player4Id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score1')); ?>:</b>
	<?php echo CHtml::encode($data->score1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score2')); ?>:</b>
	<?php echo CHtml::encode($data->score2); ?>
	<br />


</div>
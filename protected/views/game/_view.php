<?php
/* @var $this GameController */
/* @var $data Game */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

<?php $this->widget('zii.widgets.CDetailView', array(
    'data'=>$data,
    'attributes'=>array(
        'player1.name',
        'player2.name',
        'player3.name',
        'player4.name',
        'score1',
        'score2',
    ),
)); ?>
	<br />


</div>
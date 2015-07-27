<?php
/* @var $this GameController */
/* @var $model Game */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'game-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>
	<?php echo $form->errorSummary($model); ?>
<div class="game-form-inside">
    <h3>Team 1</h3>
<div class="row">

		<?php echo $form->labelEx($model,'player1Id'); ?>
        <?php
            
	$criteria = new CDbCriteria;
	$criteria->order = "name";
	$user_list = CHtml::listData(Player::model()->findAll($criteria), 'id', 'name');
            $options = array(
             # 'tabindex' => '0',
              'empty' => '-- Select --',
            );
            $score = array();
            for ($i=0;$i<=8;$i++){
              $score[$i] = $i;
            }
if ($model->isNewRecord){
$criteria = new CDbCriteria;
$criteria->select = array("player1Id","player2Id","player3Id","player4Id");
$criteria->order = "id DESC";
$model = Game::model()->find($criteria);
if (!is_object($model)){
 $model = Game::model();
}
}
        ?>
        <?php echo CHtml::activeDropDownList($model,'player1Id',$user_list,$options); ?>
        <?php echo $form->error($model,'player1Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'player2Id'); ?>
        <?php echo CHtml::activeDropDownList($model,'player2Id',$user_list,$options); ?>
		<?php echo $form->error($model,'player2Id'); ?>
	</div>

    <div class="row">
        <?php echo $form->labelEx($model,'score1'); ?>
        <?php echo CHtml::activeDropDownList($model,'score1',$score,$options); ?>
        <?php echo $form->error($model,'score1'); ?>
    </div>
  <h3>Team 2</h3>

	<div class="row">
		<?php echo $form->labelEx($model,'player3Id'); ?>
        <?php echo CHtml::activeDropDownList($model,'player3Id',$user_list,$options); ?>
		<?php echo $form->error($model,'player3Id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'player4Id'); ?>
        <?php echo CHtml::activeDropDownList($model,'player4Id',$user_list,$options); ?>
		<?php echo $form->error($model,'player4Id'); ?>
	</div>


	<div class="row">
		<?php echo $form->labelEx($model,'score2'); ?>
        <?php echo CHtml::activeDropDownList($model,'score2',$score,$options); ?>
		<?php echo $form->error($model,'score2'); ?>
	</div>
</div>
	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>
  
  
<?php $this->endWidget(); ?>

</div><!-- form -->

<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$connection=Yii::app()->db;
?>
0<input type="range" name="difference" min="1" max="400">400

<?php
$Rteam1 = ELO($team1,$team2,$game["score1"],$game["score2"]);
$Rteam2 = ELO($team2,$team1,$game["score2"],$game["score1"]);

function ELO($Ra,$Rb,$Sa,$Sb){
  $Sa = (1/($Sa+$Sb))*$Sa;
 if (abs($Rb-$Ra) < 400){
  $diff = $Rb-$Ra;
 }
 else{
  if ($Rb-$Ra > 0){
    $diff  = 400;
  }
  else{
    $diff  = -400;
  }
 }
 $Ea = 1/(1+ Pow(10, ($diff)/400));
 $k = 30;
 #only add 
 $Ra = $k*($Sa - $Ea);
 return $Ra;
// return $diff;
}


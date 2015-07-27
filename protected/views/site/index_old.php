<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$connection=Yii::app()->db;
$sql='SELECT * from game';
$command=$connection->createCommand($sql);
$games=$command->queryAll(); 

$sql='SELECT * from player';
$command=$connection->createCommand($sql);
$players=$command->queryAll(); 

foreach ($players as $player){
  #$ELO[$player["id"]] = $player["elo"];
  $ELO[$player["id"]] = 1500;
}

foreach ($games as $game){
   $game["player1Id"];
   $game["player2Id"];
   
   $game["player3Id"];
   $game["player4Id"];
   
   $game["score1"];
   $game["score2"];

   $team1 = ($ELO[$game["player1Id"]] + $ELO[$game["player2Id"]]) / 2;
   $team2 = ($ELO[$game["player3Id"]] + $ELO[$game["player4Id"]]) / 2;

   $Rteam1 = ELO($team1,$team2,$game["score1"],$game["score2"]);
   $Rteam2 = ELO($team2,$team1,$game["score2"],$game["score1"]);

   $ELO[$game["player1Id"]] += $Rteam1;
   if ($game["player1Id"] != $game["player2Id"]){
    $ELO[$game["player2Id"]] += $Rteam1;
   }
   $ELO[$game["player3Id"]] += $Rteam2;
   if ($game["player3Id"] != $game["player4Id"]){
    $ELO[$game["player4Id"]] += $Rteam2;
   }
}

foreach ($ELO as $id => $elo){
  Player::model()->updateByPk($id,array("elo" => $elo));
}


function ELO($Ra,$Rb,$Sa,$Sb){
  $max = max($Sb,$Sa);
  $Sa = 1 / $max * $Sa;
 if (abs($Rb-$Ra) < 400){
  $diff = $Rb-$Ra;
 }
 else{
  $diff  = 400;
 }
 $Ea = 1/(1+ Pow(10, -($diff)/400));
 $k = 15;
 #only add 
 $Ra = $k*($Sa - $Ea);
  return $Ra;
}

$sql='SELECT * from player order by elo DESC';
$command=$connection->createCommand($sql);
$players=$command->queryAll(); 
?>
<table class="elo">
<?php
foreach($players as $player){
  echo "<tr class='odd'><th>".$player["name"]."</th><td>".$player["elo"]."</td></tr>";
}
?>
</table>
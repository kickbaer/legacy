<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$connection=Yii::app()->db;

/**
* Calculate a unique integer based on two integers (cantor pairing).
*/
function cantor_pair_calculate($x, $y) {
return (($x + $y) * ($x + $y + 1)) / 2 + $y;
}
function cantor_pair_calculate_sorted($array) {
sort($array);
return (($array[0] + $array[1]) * ($array[0] + $array[1] + 1)) / 2 + $array[1];
}

/**
* Return the source integers from a cantor pair integer.
*/
function cantor_pair_reverse($z) {
$t = floor((-1 + sqrt(1 + 8 * $z))/2);
$x = $t * ($t + 3) / 2 - $z;
$y = $z - $t * ($t + 1) / 2;
return array($x, $y);
} 

$sql='SELECT * from game order by id DESC limit 120';
$command=$connection->createCommand($sql);
$last60games=$command->queryAll();
foreach ($last60games as $row){
  $group1ID = cantor_pair_calculate_sorted(array($row["player1Id"],$row["player2Id"]));
  $group2ID = cantor_pair_calculate_sorted(array($row["player3Id"],$row["player4Id"]));
  if (!in_array($group1ID,$last60attend)){
 	 $last60attend[] = $group1ID;
  }
  if (!in_array($group2ID,$last60attend)){
 	 $last60attend[] = $group2ID;
  }
}


$sql='SELECT * from game';
$command=$connection->createCommand($sql);
$games=$command->queryAll(); 

$sql='SELECT * from player';
$command=$connection->createCommand($sql);
$players=$command->queryAll(); 

foreach ($players as $player){ 
  $player_name[$player["id"]] = $player["name"];  
}


$message = array();

foreach ($games as $game_id => $game){
   $game["player1Id"];
   $game["player2Id"];
   
   $game["player3Id"];
   $game["player4Id"];
   
   $game["score1"];
   $game["score2"];

   $group1ID = cantor_pair_calculate_sorted(array($game["player1Id"],$game["player2Id"]));
   $group2ID = cantor_pair_calculate_sorted(array($game["player3Id"],$game["player4Id"]));  

   $count[$group1ID] ++;
   $count[$group2ID] ++;

   if (!$ELO[$group1ID]){
     $ELO[$group1ID] = 1500;
     $groupnames[$group1ID] = $player_name[$game["player1Id"]]." + ".$player_name[$game["player2Id"]];
   }
   if (!$ELO[$group2ID]){
     $ELO[$group2ID] = 1500;
     $groupnames[$group2ID] = $player_name[$game["player3Id"]]." + ".$player_name[$game["player4Id"]];
   }

   $team1 = $ELO[$group1ID];
   $team2 = $ELO[$group2ID];

   $Rteam1 = ELO($team1,$team2,$game["score1"],$game["score2"]);
   $Rteam2 = ELO($team2,$team1,$game["score2"],$game["score1"]);

   $message[]= array(
    "player1" => $player_name[$game["player1Id"]],
    "player2" => $player_name[$game["player2Id"]],
    "player3" => $player_name[$game["player3Id"]],
    "player4" => $player_name[$game["player4Id"]],
    "eloP1"   => $ELO[$group1ID],
   # "eloP2"   => $ELO[$game["player2Id"]],
    "eloP3"   => $ELO[$group2ID],
   # "eloP4"   => $ELO[$game["player4Id"]],
    "score1" => $game["score1"],
    "score2" => $game["score2"],
    "team1" => $team1,
    "team2" => $team2,
    "Rteam1" => $Rteam1,
    "Rteam2" => $Rteam2,
   );


   $ELO[$group1ID] += $Rteam1;
   $ELO[$group2ID] += $Rteam2;
   $dataset[$player_name[$game["player1Id"]]][$game["id"]." + ".$player_name[$game["player2Id"]]][$game["id"]] = $ELO[$group1ID];
   $dataset[$player_name[$game["player3Id"]]][$game["id"]." + ".$player_name[$game["player4Id"]]][$game["id"]] = $ELO[$group2ID];
 
}


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

?>
<div class="tableContainer" style="width: 100%;height: 500px;">
<table class="elo score" >
<tr class="header">
<th class="player">Player Name</th><th class="elo">ELO</th><th class="elo">Games</th>
</tr>
<?php
arsort($ELO);
foreach($ELO as $groupID => $elo){
 if (in_array($groupID,$last60attend)){
  echo "<tr><td class='player'>".$groupnames[$groupID]."</td><td class='elo'><span title='".$elo."'>".round($elo)."</span></td><td class='elo'>".$count[$groupID]."</td></tr>";
 }
 else if (!in_array($groupID,$last60attend)){
  echo "<tr><td class='player_grey'>".$groupnames[$groupID]."</td><td class='elo_grey'><span title='".$elo."'>".round($elo)."</span></td><td class='elo_grey'>".$count[$groupID]."</td></tr>";
 }

}

?>
</table>
<p>
<?php
#print_r($dataset);
?>
</div>
<div class="tableContainer" style="height: 200px; width: 100%; margin-top: 50px;">
<table class="elo score fixedHeader" id="history" style="width: 100%">
<thead>
<tr>
<th colspan="3">
Team 1
</th>
<th></th>
<th colspan="3">
Team 2
</th>
</tr>
<tr>
<th class="">P1 (ELO)
<br/>P2 (ELO)</th>
<th>ELO</th>
<th>Change</th>
<th>Score</th>
<th>Change</th>
<th>ELO</th>
<th class="">P3 (ELO)
<br/>P4 (ELO)</th>
</tr>
</thead>
<tbody>
<?php
$message = array_reverse($message);
foreach($message as $line){
  echo "<tr class='odd'>
<td class='player t1'>".$line["player1"]."
<br/>
".$line["player2"]."
</td>
<td><span title='".$line["team1"]."'>".round($line["team1"])."</span></td>
<td><span title='".$line["Rteam1"]."'>".round($line["Rteam1"])."</span></td>
<td>".round($line["score1"])." : ".round($line["score2"])."</td>
<td><span title='".$line["Rteam2"]."'>".round($line["Rteam2"])."</span></td>
<td><span title='".$line["team2"]."'>".round($line["team2"])."</span></td>
<td class='player t2'>".$line["player3"]."
<br/>".$line["player4"]."</td>
</tr>";
}
?>
</tbody>
</table>
</div>

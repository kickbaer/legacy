<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$connection=Yii::app()->db;

$sql='SELECT * from game order by id DESC limit 120';
$command=$connection->createCommand($sql);
$last60games=$command->queryAll();
foreach ($last60games as $row){
  if (!in_array($row["player1Id"],$last60attend)){
 	 $last60attend[] = $row["player1Id"];
  }
  if (!in_array($row["player2Id"],$last60attend)){
	  $last60attend[] = $row["player2Id"];
  }
  if (!in_array($row["player3Id"],$last60attend)){
	  $last60attend[] = $row["player3Id"];
}
  if (!in_array($row["player4Id"],$last60attend)){
    $last60attend[] = $row["player4Id"];
  }
}


$sql='SELECT * from game';
$command=$connection->createCommand($sql);
$games=$command->queryAll();

$sql='SELECT * from player';
$command=$connection->createCommand($sql);
$players=$command->queryAll();

foreach ($players as $player){
  #$ELO[$player["id"]] = $player["elo"];
  $ELO[$player["id"]] = 1500;
  $player_name[$player["id"]] = $player["name"];
 #$dataset[$player["name"]][0] = 1500;
}
$message = array();

$gameCounter = 1;
$trend = array();
foreach ($games as $game_id => $game){
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

   $message[]= array(
    "player1" => $player_name[$game["player1Id"]],
    "player2" => $player_name[$game["player2Id"]],
    "player3" => $player_name[$game["player3Id"]],
    "player4" => $player_name[$game["player4Id"]],
    "eloP1"   => $ELO[$game["player1Id"]],
    "eloP2"   => $ELO[$game["player2Id"]],
    "eloP3"   => $ELO[$game["player3Id"]],
    "eloP4"   => $ELO[$game["player4Id"]],
    "score1" => $game["score1"],
    "score2" => $game["score2"],
    "team1" => $team1,
    "team2" => $team2,
    "Rteam1" => $Rteam1,
    "Rteam2" => $Rteam2,
   );


   $ELO[$game["player1Id"]] += $Rteam1;
   $trend[$game["player1Id"]][$game_id%3] = $Rteam1;
   if ($game["player1Id"] != $game["player2Id"]){
    $ELO[$game["player2Id"]] += $Rteam1;
    $trend[$game["player2Id"]][$game_id%3] = $Rteam1;
   }
   $ELO[$game["player3Id"]] += $Rteam2;
   $trend[$game["player3Id"]][$game_id%3] = $Rteam2;
   if ($game["player3Id"] != $game["player4Id"]){
    $ELO[$game["player4Id"]] += $Rteam2;
    $trend[$game["player4Id"]][$game_id%3] = $Rteam2;
   }
   /*
   $dataset[$player_name[$game["player1Id"]]][$game["id"]] = $ELO[$game["player1Id"]];
   $dataset[$player_name[$game["player2Id"]]][$game["id"]] = $ELO[$game["player2Id"]];
   $dataset[$player_name[$game["player3Id"]]][$game["id"]] = $ELO[$game["player3Id"]];
   $dataset[$player_name[$game["player4Id"]]][$game["id"]] = $ELO[$game["player4Id"]];
   */
   $dataset[$player_name[$game["player1Id"]]][$gameCounter] = $ELO[$game["player1Id"]];
   $dataset[$player_name[$game["player2Id"]]][$gameCounter] = $ELO[$game["player2Id"]];
   $dataset[$player_name[$game["player3Id"]]][$gameCounter] = $ELO[$game["player3Id"]];
   $dataset[$player_name[$game["player4Id"]]][$gameCounter] = $ELO[$game["player4Id"]];
   $gameCounter++;
}
asort($dataset);
foreach ($ELO as $id => $elo){
  Player::model()->updateByPk($id,array("elofloat" => $elo));
  $PIdElo[$id] = $elo;
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

function getColorClass($value){
  $colorClass = "";
  if ($value > 0){
    $colorClass = "positive";
  } elseif($value < 0){
    $colorClass = "negative";
  }
  return $colorClass;
}

$sql='SELECT * from player order by elofloat DESC';
$command=$connection->createCommand($sql);
$players=$command->queryAll();
?>
<div class="tables">
<div class="scrollContent">
<table class="elo standings">
<tr class="header">
<th class="player">Player</th><th class="elo">ELO</th><th>Last 3</th>
</tr>
<?php
foreach($players as $player){
$playerTrend = array_sum($trend[$player["id"]]);
  if (in_array($player["id"],$last60attend)){
    echo "<tr>
<td class='player'>".$player["name"]."</td><td class='elo'><span title='".$player["elofloat"]."'>".round($player["elofloat"])."</span></td>
<td class='elo'><span class='".getColorClass($playerTrend)."' title='".$playerTrend."'>".round($playerTrend)."</span></td>
</tr>";
  }
}
foreach($players as $player){
  if (!in_array($player["id"],$last60attend)){
    echo "<tr><td class='player_grey'>".$player["name"]."</td><td class='elo_grey'><span title='".$player["elofloat"]."'>".round($player["elofloat"])."</span></td>
<td class='elo'><span title='".array_sum($trend[$player["id"]])."'>".round(array_sum($trend[$player["id"]]))."</span></td>
</tr>";
  }
}

?>
</table>
</div>
<?php
#print_r($dataset);
?>
<link rel="stylesheet" type="text/css" href="jq/jquery.jqplot.css" />
<script language="javascript" type="text/javascript" src="jq/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="jq/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="jq/plugins/jqplot.highlighter.min.js"></script>
<script type="text/javascript" src="jq/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="jq/plugins/jqplot.pointLabels.min.js"></script>
<script type="text/javascript" src="jq/plugins/jqplot.enhancedLegendRenderer.min.js"></script>
<script type="text/javascript" src="jq/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

var datasets = [];
<?php
asort($dataset);
foreach($dataset as $playername => $data){
echo "\ndatasets[\"$playername\"] = []";
  foreach($data as $game_id => $elo){
    echo  "\ndatasets['$playername'].push([$game_id,".$elo."]);";
  }
}
?>
var plot3 = $.jqplot('chart3',[ <?php
foreach($dataset as $playername => $data){
 echo "datasets['$playername'],";
}
?>],
    {
      // Series options are specified as an array of objects, one object
      // for each series.
      series:[
<?php
foreach($dataset as $playername => $data){
 echo "\n{label: '$playername'},";
}
?>
            // Change our line width and use a diamond shaped marker.
      ],
        legend: {
	renderer: $.jqplot.EnhancedLegendRenderer,
	rendererOptions: {
          numberRows: 2,
          seriesToggle: 'normal',
	},
        show: true,
        location: 'n',
        placement: 'outsideGrid',
	marginBottom: '20px',
	border: 'none',
        },
      grid: {
	background: '#15023b',
	borderColor: 'white',
      },
      axes: {
      xaxis: {
        pad: 1,
        renderer: $.jqplot.CategoryAxisRenderer,
        tickOptions: {
          labelPosition: 'middle',
        },
	autoscale:true,
      },
      yaxis: {
        pad: 1.1,
        autoscale:true,
        tickRenderer: $.jqplot.CanvasAxisTickRenderer,
        tickOptions: {
          labelPosition: 'start',
	  formatString:'%g',
        },
      },
      },
      highlighter: {
	show: true,
        sizeAdjust: 7.5,
	tooltipAxes: 'y',
	formatString:'%g',
      },
      cursor: {
	show: true,
        zoom:true,
      },
      seriesDefaults: {
	lineWidth: 1.5,
	markerOptions: {
	  size: 4.5,
	  lineWidth: 1,
	}
      }
    }
  );

});
</script>
<div class="tableContainer">
<table class="elo score fixedHeader" id="history">
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
<td class='player t1'>".$line["player1"]." (<span title='".$line["eloP1"]."'>".round ($line["eloP1"])."</span>)
<br/>
".$line["player2"]." (<span title='".$line["eloP2"]."'>".round ($line["eloP2"])."</span>)
</td>
<td><span title='".$line["team1"]."'>".round($line["team1"])."</span></td>
<td><span class='".getColorClass($line["Rteam1"])."' title='".$line["Rteam1"]."'>".round($line["Rteam1"])."</span></td>
<td>".round($line["score1"])." : ".round($line["score2"])."</td>
<td><span class='".getColorClass($line["Rteam2"])."' title='".$line["Rteam2"]."'>".round($line["Rteam2"])."</span></td>
<td><span title='".$line["team2"]."'>".round($line["team2"])."</span></td>
<td class='player t2'>".$line["player3"]." (<span title='".$line["eloP3"]."'>".round ($line["eloP3"])."</span>)
<br/>".$line["player4"]." (<span title='".$line["eloP4"]."'>".round ($line["eloP4"])."</span>)</td>
</tr>";
}
?>
</tbody>
</table>
</div>
</div>
<button style="margin-top: 20px;" onclick='$("td.jqplot-table-legend-label").not(".jqplot-series-hidden").click()'>hide all</button>
<button style="margin-top: 20px;" onclick='$("td.jqplot-table-legend-label.jqplot-series-hidden").click()'>show all</button>
<button style="margin-top: 20px;" onclick='$("td.jqplot-table-legend-label").click()'>toggle all</button>
<div id="chart3" style="height: 650px; margin-top: 20px 0 30px 0;"></div>
<!--
<div class="explain">
<p>Die angezeigten Werte sind gerundet. <a href="http://us1.php.net/manual/de/function.round.php">round()</a> Mouseover zeigt die ganzen Werte an.
<br/>ELO: <a href="http://de.wikipedia.org/wiki/Elo-Zahl">http://de.wikipedia.org/wiki/Elo-Zahl</a>
<p><b>Im Schach:</b>
<br/>S<sub>A</sub>: Tatsächlich gespielter Punktestand (1 für jeden Sieg, 0,5 für jedes Unentschieden, 0 für jede Niederlage)
<p><b>Hier:</b>
<br/>S<sub>A</sub> = (1 / (ToreTeam<sub>A</sub> + ToreTeam<sub>B</sub>)) * ToreTeam<sub>A</sub>
<br/>k = 30
<p>Den höchsten Betrag, den es als Punktveränderung geben kann ist 27.2727272727272
</div>
-->

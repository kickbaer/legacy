<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

$connection=Yii::app()->db;

class PlayerCard {

  function __construct($player,$diff,$min) {
    $this->id = $player["id"];
    $this->name = $player["name"];
    $this->img = $player["img"];
    $this->elofloat = $player["elofloat"];
    $this->progress = $player["elofloat"]-$min;
    $this->diff = $diff;
  }

  function getHtml() {
    ?>
    <div class = "card" style='background: #808080 url("/images/player/<?php echo $this->img ?>"); background-size: cover' draggable = "true" data-playerID = "<?php echo $this->id ?>">
      <div style="height: 100%"></div>
      <div class = "cardElo"><progress value = "<?php echo $this->progress ?>" max = "<?php echo $this->diff; ?>"></progress></div>
      <div class = "cardHead"><?php echo $this->name ?></div>
    </div>
    <?php
  }
}

function prefill($connection){
  $sql = 'SELECT * from game order by id DESC limit 3';
  $command = $connection->createCommand($sql);
  $games = $command->queryAll();
  foreach ($games as $id => $game){
    $team1[$id][0] = $game["player1Id"];
    $team1[$id][1] = $game["player2Id"];
    $team2[$id][0] = $game["player3Id"];
    $team2[$id][1] = $game["player4Id"];
    $players[$id] = array($game["player1Id"],$game["player2Id"],$game["player3Id"],$game["player4Id"],);
    $gamesdiff[] = array($game["player1Id"],$game["player2Id"],$game["player3Id"],$game["player4Id"]);
  }
  if  (!array_diff($gamesdiff[0],$gamesdiff[1])){
    $count = 2;
    if (!array_diff($gamesdiff[1],$gamesdiff[2])){
      $count = 3;
    }
  }
  else{
    $count = 1;
  }
  #solutions:
  if ($count > 1){
  #take the last possible combination.
    $boon = $team1[0][0];
    $prefill[0] = $boon;
    $boongroup = array($boon, $team1[0][1]); #the players which already played with boon
    if (in_array($boon,$team1[1])){
     $boongroup = array_merge($boongroup,$team1[1]);
    }
    else{
     $boongroup = array_merge($boongroup,$team2[1]);
    }
    foreach ($players[0] as $player){
     if (!in_array($player,$boongroup)){
      array_splice($prefill, 1, 0, $player);
     }
     elseif($player != $boon){
      $prefill[] = $player;
     }
    }
  }
  if ($count == 1){
  #take a different combination.
    $prefill[0]  = $team1[0][0];
    $prefill[1]  = $team2[0][0];
    $prefill[2]  = $team1[0][1];
    $prefill[3]  = $team2[0][1];
  }
/*
  if ($count == 3){
  #take the last players
    $prefill = $players[0];
  }
  */
  return $prefill;
}

if (count($_POST)>0){
  $sql = 'insert into game ("player1Id","player2Id","player3Id","player4Id","score1","score2") values ('.(int)$_POST["player1Id"].','.(int)$_POST["player2Id"].','.(int)$_POST["player3Id"].','.(int)$_POST["player4Id"].','.(int)$_POST["score1"].','.(int)$_POST["score2"].')';
  $command = $connection->createCommand($sql);
  $sql = $command->queryAll();
  $message =  '<p style="opacity: 1;transition: all 2s ease;" id="added">Added</p>';
}
else{
  $message = '<p style="opacity: 0;transition: all 2s ease;" id="added">Added</p>';
}
  
$sql = 'SELECT * from game';
$command = $connection->createCommand($sql);
$games = $command->queryAll();

$sql = 'SELECT * from player order by name';
$command = $connection->createCommand($sql);
$players = $command->queryAll();
foreach ($players as $player) {
  $compare[]=$player["elofloat"];
}
$min = min($compare);
$diff = max($compare)-$min;
foreach ($players as $player) {
  $cards[$player["id"]] = new PlayerCard($player,$diff,$min);
}
$prefill = prefill($connection);
if (count($prefill)==4){
  foreach ($prefill as $id => $prefillid){
    $precards[$id] = $cards[$prefillid];
  }
}
else{
  foreach ($cards as $card){
    $precards[] = $card;
  }
}
#print_r($_POST);
?>
    <link rel="stylesheet" type="text/css" href="/css/dnd.css?v=1">
    <div class="grid grid-pad">
      <div class="row">
        <?php
        foreach ($cards as $card) {
          ?>
          <div class="col-1-6"><div class="field setable">
              <?php 
              $card->getHtml();
              ?>
            </div></div>
          <?php
        }
        ?>
      </div>
      <form method="post">
      <div class="row">
        <div class="col-1-5"><div id="p1" class="field setable"><?php $precards[0]->getHtml() ?></div></div>
        <div class="col-1-5"><div id="p2" class="field setable"><?php $precards[1]->getHtml() ?></div></div>
        <div class="col-1-5">
	</div>
        <div class="col-1-5"><div id="p3" class="field setable"><?php $precards[2]->getHtml() ?></div></div>
        <div class="col-1-5"><div id="p4" class="field setable"><?php $precards[3]->getHtml() ?></div></div>

      </div>
      <div class="row" style="width:100%; height: 50px;">
        <div class="col-1-2" style="">
	   <table class="scorednd">
	    <tr>
	     <th><label for="radio_0">0</label></th>
	     <th><label for="radio_1">1</label></th>
	     <th><label for="radio_2">2</label></th>
	     <th><label for="radio_3">3</label></th>
	     <th><label for="radio_4">4</label></th>
	     <th><label for="radio_5">5</label></th>
	     <th><label for="radio_6">6</label></th>
	     <th><label for="radio_7">7</label></th>
	     <th><label for="radio_8">8</label></th>
	    </tr>
	    <tr>
	     <td><input id="radio_0" type="radio" name="score1" value="0"/></td>
	     <td><input id="radio_1" type="radio" name="score1" value="1"/></td>
	     <td><input id="radio_2" type="radio" name="score1" value="2"/></td>
	     <td><input id="radio_3" type="radio" name="score1" value="3"/></td>
	     <td><input id="radio_4" type="radio" name="score1" value="4"/></td>
	     <td><input id="radio_5" type="radio" name="score1" value="5"/></td>
	     <td><input id="radio_6" type="radio" name="score1" value="6"/></td>
	     <td><input id="radio_7" type="radio" name="score1" value="7"/></td>
	     <td><input id="radio_8" type="radio" name="score1" value="8"/></td>
	     </tr>
	   </table>
        </div>
        <div class="col-1-2">
	   <table class="scorednd">
	    <tr>
	     <th><label for="radio2_0">0</label></th>
	     <th><label for="radio2_1">1</label></th>
	     <th><label for="radio2_2">2</label></th>
	     <th><label for="radio2_3">3</label></th>
	     <th><label for="radio2_4">4</label></th>
	     <th><label for="radio2_5">5</label></th>
	     <th><label for="radio2_6">6</label></th>
	     <th><label for="radio2_7">7</label></th>
	     <th><label for="radio2_8">8</label></th>
	    </tr>
	    <tr>
	     <td><input id="radio2_0" type="radio" name="score2" value="0"/></td>
	     <td><input id="radio2_1" type="radio" name="score2" value="1"/></td>
	     <td><input id="radio2_2" type="radio" name="score2" value="2"/></td>
	     <td><input id="radio2_3" type="radio" name="score2" value="3"/></td>
	     <td><input id="radio2_4" type="radio" name="score2" value="4"/></td>
	     <td><input id="radio2_5" type="radio" name="score2" value="5"/></td>
	     <td><input id="radio2_6" type="radio" name="score2" value="6"/></td>
	     <td><input id="radio2_7" type="radio" name="score2" value="7"/></td>
	     <td><input id="radio2_8" type="radio" name="score2" value="8"/></td>
	     </tr>
	   </table>
        </div>
      </div>
      <div style="clear: both;"></div>
      <div class="row">
        <input type="submit" id="submit" style="visibility: hidden;transition: all 1s ease;" value="Submit"/>
<?php
echo $message;
?>
      </div>
      <input type="hidden" name="player1Id" id="player1Id"/>
      <input type="hidden" name="player2Id" id="player2Id"/>
      <input type="hidden" name="player3Id" id="player3Id"/>
      <input type="hidden" name="player4Id" id="player4Id"/>
      </form>
    </div>
    <script src="/js/dnd.js?v=2"></script>

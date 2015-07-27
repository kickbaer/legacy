<?php

/**
 * This is the model class for table "game".
 *
 * The followings are the available columns in table 'game':
 * @property integer $id
 * @property integer $player1Id
 * @property integer $player2Id
 * @property integer $player3Id
 * @property integer $player4Id
 * @property integer $score1
 * @property integer $score2
 *
 * The followings are the available model relations:
 * @property Player $player1
 * @property Player $player2
 * @property Player $player3
 * @property Player $player4
 */
class Game extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Game the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'game';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('player1Id, player2Id, player3Id, player4Id, score1, score2', 'required'),
			array('player1Id, player2Id, player3Id, player4Id, score1, score2', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, player1Id, player2Id, player3Id, player4Id, score1, score2', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'player1' => array(self::BELONGS_TO, 'Player', 'player1Id'),
			'player2' => array(self::BELONGS_TO, 'Player', 'player2Id'),
			'player3' => array(self::BELONGS_TO, 'Player', 'player3Id'),
			'player4' => array(self::BELONGS_TO, 'Player', 'player4Id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'player1Id' => 'Player1',
			'player2Id' => 'Player2',
			'player3Id' => 'Player3',
			'player4Id' => 'Player4',
			'score1' => 'Score1',
			'score2' => 'Score2',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('player1Id',$this->player1Id);
		$criteria->compare('player2Id',$this->player2Id);
		$criteria->compare('player3Id',$this->player3Id);
		$criteria->compare('player4Id',$this->player4Id);
		$criteria->compare('score1',$this->score1);
		$criteria->compare('score2',$this->score2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
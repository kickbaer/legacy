<?php

/**
 * This is the model class for table "Player".
 *
 * The followings are the available columns in table 'Player':
 * @property integer $id
 * @property string $name
 * @property string $img
 * @property integer $elo
 *
 * The followings are the available model relations:
 * @property Game[] $games
 * @property Game[] $games1
 * @property Game[] $games2
 * @property Game[] $games3
 */
class Player extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Player the static model class
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
		return 'Player';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('elo', 'numerical', 'integerOnly'=>true),
			array('elofloat', 'numerical', 'integerOnly'=>false),
			array('img', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, img, elo, elofloat', 'safe', 'on'=>'search'),
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
			'games' => array(self::HAS_MANY, 'Game', 'player1Id'),
			'games1' => array(self::HAS_MANY, 'Game', 'player2Id'),
			'games2' => array(self::HAS_MANY, 'Game', 'player3Id'),
			'games3' => array(self::HAS_MANY, 'Game', 'player4Id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Name',
			'img' => 'Img',
			'elo' => 'Elo',
			'elofloat' => 'Elofloat',
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('img',$this->img,true);
		$criteria->compare('elo',$this->elo);
		$criteria->compare('elofloat',$this->elofloat);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}

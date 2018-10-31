<?php

/**
 * This is the model class for table "tbl_city".
 *
 * The followings are the available columns in table 'tbl_city':
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $place_id
 * @property string $longitude
 * @property string $latitude
 */
class City extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tbl_city';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, description, place_id, longitude, latitude', 'required'),
            array('place_id', 'unique'),
            array('place_id', 'checkCity'),
			array('name, description, place_id, longitude, latitude', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, name, description, place_id, longitude, latitude', 'safe', 'on'=>'search'),
		);
	}

    public function checkCity($attribute, $params)
    {
        $container = Yii::app()->DI->container;
        $cities = $container->get('Modules\GoogleApi\Models\PlaceSearch')
                    ->requestCitiesByName($this->description)
                    ->getCities();
        $key = array_search($this->place_id, array_column($cities, 'place_id'));

        if (isset($key)) {
            $city = $cities[$key];
            $this->attributes = NULL;
            $this->name = $city['name'];
            $this->place_id = $city['place_id'];
            $this->description =  $city['description'];
            $this->longitude = $city['longitude'];
            $this->latitude = $city['latitude'];
        } else {
            $this->addError($attribute, 'Попытка записи несуществующего города');
        }
    }
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'name' => 'Название',
			'description' => 'Описание',
			'place_id' => 'PlaceID',
			'longitude' => 'Долгота',
			'latitude' => 'Широта',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('place_id',$this->place_id,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('latitude',$this->latitude,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return City the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

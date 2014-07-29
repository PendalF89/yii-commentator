<?php
namespace pendalf89\yii_commentator\models;
/**
 * This is the model class for table "new_comments".
 *
 * The followings are the available columns in table 'new_comments':
 * @property integer $comment_id
 * @property integer $user_id
 */
class NewComments extends \CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'new_comments';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('comment_id, user_id', 'required'),
			array('comment_id, user_id', 'numerical', 'integerOnly'=>true),
			array('comment_id, user_id', 'safe', 'on'=>'search'),
		);
	}

    /**
     * Условие для поиска комментариев определённого пользователя
     * @param string $url по-умолчанию текущая страница
     * @return $this
     */
    public function user($id)
    {
        if ( empty($id) )
            return $this;

        $criteria = new \CDbCriteria();
        $criteria->addInCondition('user_id', array($id));

        $this->getDbCriteria()->mergeWith($criteria);
        return $this;
    }

//	/**
//	 * @return array relational rules.
//	 */
//	public function relations()
//	{
//        return array(
//            'commentId1' => array(self::BELONGS_TO, 'Comment', 'comment_id1'),
//            'userId1' => array(self::BELONGS_TO, 'User', 'user_id1'),
//        );
//	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'comment_id' => 'Comment',
			'user_id' => 'User',
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
	 * @return \CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new \CDbCriteria;

		$criteria->compare('comment_id',$this->comment_id);
		$criteria->compare('user_id',$this->user_id);

		return new \CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return NewComments the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

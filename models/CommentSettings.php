<?php
namespace pendalf89\yii_commentator\models;
/**
 * This is the model class for table "comment_settings".
 *
 * The followings are the available columns in table 'comment_settings':
 * @property integer $id
 * @property string $date_format
 * @property integer $margin
 * @property integer $levels
 * @property integer $edit_time
 * @property integer $max_length_author
 * @property integer $max_length_content
 * @property integer $likes_control
 * @property integer $manage_page_size
 * @property integer $premoderate
 * @property integer $notify_admin
 * @property string $fromEmail
 * @property string $adminEmail
 */
class CommentSettings extends \CActiveRecord
{
    const YES = 1;
    const NO = 0;

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'comment_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
    public function rules()
    {
        return array(
            array('fromEmail, adminEmail', 'required'),
            array('margin, levels, edit_time, max_length_author, max_length_content, likes_control, manage_page_size, premoderate, notify_admin', 'numerical', 'integerOnly'=>true),
            array('date_format, fromEmail, adminEmail', 'length', 'max'=>128),
            array('id, date_format, margin, levels, edit_time, max_length_author, max_length_content, likes_control, manage_page_size, premoderate, notify_admin, fromEmail, adminEmail', 'safe', 'on'=>'search'),
        );
    }

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date_format' => 'Формат даты',
			'margin' => 'Отступ узла дерева в пикселях',
			'levels' => 'Количество уровней дерева',
			'edit_time' => 'Время в (секундах), в течение которого можно отредактировать комментарий',
			'max_length_author' => 'Максимальная длина поля "автор" (max 128)',
			'max_length_content' => 'Максимальная длина поля "комментарий"',
			'likes_control' => 'Может ли суперпользователь накручивать лайки',
			'manage_page_size' => 'Количество элементов на странице управления комментариями',
			'premoderate' => 'Премодерация комментариев (появляются после провеки модератором)',
			'notify_admin' => 'Уведомлять админа о новых комментария',
            'fromEmail' => 'E-mail, с которго будут приходить письма',
            'adminEmail' => 'E-mail админа',
		);
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CommentSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

    /**
     * @return array массив "да"/"нет"
     */
    public static function booleanArray()
    {
        return array(
            self::YES => \Yii::t('pendalf89\yii_commentator\CommentsModule.main', 'yes'),
            self::NO => \Yii::t('pendalf89\yii_commentator\CommentsModule.main', 'no'),
        );
    }

    /**
     * Загружает настройки
     * @return \CActiveRecord
     */
    public static function load()
    {
        return self::model()->findByPk(1);
    }
}

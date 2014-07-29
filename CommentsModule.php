<?php
namespace pendalf89\comments;
use pendalf89\comments\models\CommentSettings as CommentSettings;

class CommentsModule extends \CWebModule
{
    /**
     * @var string формат даты
     */
    public $dateFormat = 'd.m.Y | H:i:s';

    /**
     * @var int отсутуп для уровней
     */
    public $margin = 70;

    /**
     * @var int максимальное количество уровней
     */
    public $levels = 1;

    /**
     * @var int время в секундах, в течение которого можно удалять/редактировать свой комментарий
     */
    public $editTime = 60;

    /**
     * @var int максимальная длина поля "автор" (максимально допустимое значение - 128)
     */
    public $maxLengthAuthor = 128;

    /**
     * @var int максимальная длина поля "комментарий"
     */
    public $maxLengthContent = 1000;

    /**
     * @var bool может ли суперпользователь контролировать лайки?
     * false - не может, у суперпользователя те же полномочия относительно лайков, что и у обычного
     * true - может, суперпользователь может накручивать лайки
     */
    public $likesControl = false;

    /**
     * @var bool премодерация комментариев.
     * false - комментарии появляются сразу
     * true - комментарии появляются после проверки
     */
    public $premoderate = false;

    /**
     * @var string email, с которого будут отправляться письма
     */
    public $fromEmail = '';

    /**
     * @var string email, куда будут приходить письма администратору
     */
    public $adminEmail = '';

    /**
     * @var mixed уведомлять админа о новых комментария? Сообщения будут приходить на почту, которая указана в $this->email
     */
    public $notifyAdmin = false;

    /**
     * @var mixed выражение, определяющее является ли пользователь админом или нет. Анонимная функция должна возвращать true/false
     */
    public $isSuperuser = false;

    /**
     * @var int количество элементов на странице управления комментариями
     */
    public $managePageSize = 50;

    /**
     * @var bool использовать настройки из БД. Если это опцию выключить, то все настройки надо задавать в кофигурации виджета
     */
    public $useSettingsFromDB = true;

    /**
     * @var mixed выражение или анонимная функция для получения id текущего пользователя
     */
    public $userIDExpr = '\Yii::app()->user->id';

    /**
     * @var string модель пользователей. Если у вас нет модели пользователей, оставьте это значени пустым, модуль
     * будет работать без модели пользователей
     */
    public $userModelClass = 'User';

    /**
     * @var string свойство модели пользователя, в котором хранится email адрес
     */
    public $userEmailField = 'email';

    /**
     * @var string свойство модели пользователя, в котором хранится имя пользователя
     */
    public $usernameField = 'username';

    /**
     * ===== Дальше не трогаем =====
     */

    /**
     * @var string контроллер по-умолчанию
     */
    public $defaultController = 'handler';

    /**
     * @var string пространство имён контроллера
     */
    public $controllerNamespace = 'pendalf89\comments\controllers';

    /**
     * Инициализация модуля
     */
    public function init()
    {
        parent::init();

        // Если включена опция "использовать настройки из БД"
        if ($this->useSettingsFromDB)
            $this->setSettingsFromDB();
    }

    /**
     * Устанавливает настройки из БД
     */
    private function setSettingsFromDB()
    {
        $model = CommentSettings::load();
        $this->dateFormat = $model->date_format;
        $this->margin = $model->margin;
        $this->levels = $model->levels;
        $this->editTime = $model->edit_time;
        $this->maxLengthAuthor = $model->max_length_author;
        $this->maxLengthContent = $model->max_length_content;
        $this->likesControl = $model->likes_control;
        $this->managePageSize = $model->manage_page_size;
        $this->premoderate = $model->premoderate;
        $this->notifyAdmin = $model->notify_admin;
        $this->fromEmail = $model->fromEmail;
        $this->adminEmail = $model->adminEmail;
    }

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}

    /**
     * @return boolean проверка, является ли данный пользователь администратором
     */
    public function isSuperuser()
    {
        return $this->evaluateExpression($this->isSuperuser);
    }

    /**
     * @return int ID текущего пользователя
     */
    public function getUserID()
    {
        return $this->evaluateExpression($this->userIDExpr);
    }

    /**
     * Загружает модель текущего пользователя
     * @return mixed
     */
    public function loadUser()
    {
        if ( empty($this->userModelClass) )
            return false;

        $userModel = new $this->userModelClass();
        return $userModel->findByPk( $this->getUserID() );
    }

    /**
     * @return bool статус премодерации
     */
    public function getPremoderateStatus()
    {
        if ($this->isSuperuser())
            return false;

        return !empty($this->premoderate) ? true : false;
    }

    /**
     * Отправка почты
     * @param $to
     * @param $subject
     * @param $body
     * @return bool
     */
    public function sendMail($to, $subject, $body)
    {
        mb_internal_encoding(\Yii::app()->charset);
        mb_language('uni');
        return mb_send_mail($to, $subject, $body,
            "From: {$this->fromEmail}\r\nContent-Type: text/html; charset=".\Yii::app()->charset."\r\nMIME-Version: 1.0");


//        $email = \Yii::app()->email;
//        $email->to = $to;
//        $email->from = $this->fromEmail;
//        $email->subject = $subject;
//        $email->message = $body;
//        $email->send();
    }
}

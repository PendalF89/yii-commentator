<?php
namespace pendalf89\yii_commentator\controllers;
use Yii;
use pendalf89\yii_commentator\models\Comment as Comment;
use pendalf89\yii_commentator\helpers\CHelper as CHelper;
use pendalf89\yii_commentator\extensions\comments_widget\CommentsWidget as CommentsWidget;
use pendalf89\yii_commentator\extensions\email\Email as Email;

class HandlerController extends \CController
{
    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'ajaxOnly - Unsubscribe',
        );
    }

    /**
     * Создаёт новый комментарий
     * @return bool
     */
    public function actionCreate()
    {
        $model = new Comment('guest');
        if ( $user = \Yii::app()->getModule('comments')->loadUser() )
        {
            $model->setScenario('authorized');
            $model->user_id = $user->{$user->tableSchema->primaryKey};
        }

        $this->performAjaxValidation($model);

        if ( !isset($_POST['pendalf89_yii_commentator_models_Comment']) )
            return false;

        $model->attributes = $_POST['pendalf89_yii_commentator_models_Comment'];
        $model->ip = CHelper::getRealIP();
        $model->setStatus();

        if ( !$model->save() )
            return false;

        \Yii::app()->session["commentHash_{$model->id}"] = $model->getHash();

        $widget = new CommentsWidget();
        $widget->models = Comment::model()->page($model->url)->approved()->findAll();
        $widget->init();

        if (\Yii::app()->getModule('comments')->notifyAdmin)
            $this->sendAdminNotify($model);

        $this->sendUserNotifies($model);

        echo json_encode(array(
            'id' => $model->id,
            'premoderate' => \Yii::app()->getModule('comments')->getPremoderateStatus(),
            'tree' => $widget->getTree(),
            'count' => count($widget->models),
            'modal' => $this->getModal(array(
                    'title' => '<i class="fa fa-comments"></i> Комментарий успешно отправлен!',
                    'content' => '<strong>Спасибо за комментарий!</strong> Он появится после проверки модератором.'
                )),
        ));
    }

    /**
     * Обновление комментария
     * @return bool
     */
    public function actionUpdate()
    {
        $model = Comment::model()->findByPk($_POST['pendalf89_yii_commentator_models_Comment']['id']);

        if ( !$model->canUpdated() )
            return false;

        $model->setScenario('guest');

        if ( $user = \Yii::app()->getModule('comments')->loadUser() )
            $model->setScenario('authorized');

        $model->attributes = $_POST['pendalf89_yii_commentator_models_Comment'];
        $this->performAjaxValidation($model);

        if ( !$model->save() )
            return false;

        $widget = new CommentsWidget();
        $widget->models = Comment::model()->page($model->url)->approved()->findAll();
        $widget->init();

        echo json_encode(array(
            'id' => $model->id,
            'tree' => $widget->getTree(),
        ));
    }

    /**
     * Удаляет комментарий
     * @return bool
     */
    public function actionDelete()
    {
        $model = Comment::model()->findByPk($_POST['id']);
        $url = $model->url;

        if ( !$model->canDeleted() )
            return false;

        if ( $model->delete() )
        {
            \Yii::app()->session["commentHash_{$model->id}"] = null;
            $widget = new CommentsWidget();
            $widget->models = Comment::model()->page($url)->approved()->findAll();
            $widget->init();

            echo json_encode(array(
                'tree' => $widget->getTree(),
                'count' => count($widget->models),
                'modal' => $this->getModal(array(
                        'title' => '<i class="fa fa-comments"></i> Комментарий успешно удалён!',
                        'content' => 'Вместо удалённого комментария вы можете написать новый.'
                    )),
            ));
        }
    }

    /**
     * Создаёт форму ответа на комментарий
     * @return bool
     */
    public function actionReplyForm()
    {
        $model = new Comment('guest');
        $widget = new CommentsWidget();
        $widget->publishPluginsAssets();

        $this->renderPartial('comments.extensions.comments_widget.views.form', array(
            'model' => $model,
            'parent_id' => (int) $_POST['id'],
            'cancelButton' => true,
            'url' => $_POST['url'],
        ), false, true);
    }

    /**
     * Создаёт форму редактирования комментария
     * @return bool
     */
    public function actionUpdateForm()
    {
        $model = Comment::model()->findByPk($_POST['id']);
        $model->setScenario('guest');
        $widget = new CommentsWidget();
        $widget->publishPluginsAssets();

        $this->renderPartial('comments.extensions.comments_widget.views.form', array(
            'model' => $model,
            'cancelButton' => true,
            'url' => $_POST['url'],
        ), false, true);
    }

    /**
     * Выставляет лайки комментариям
     * @return bool
     */
    public function actionLikes()
    {
        $model = Comment::model()->findByPk($_POST['id']);
        $model->setLike($_POST['like']);

        if ( !$model->canLiked() )
            return;

        if ( $model->save() )
        {
            $model->setLikesToSession();

            echo json_encode(array(
                'likes' => $model->getLikes()
            ));
        }
    }

    /**
     * Отписка от рассылки комментариев
     * @param $hash
     * @param string $url
     */
    public function actionUnsubscribe($hash, $url='')
    {
        $subscriber = Comment::findByHashUrl($hash, $url);
        $comments = empty($url)
            ? Comment::model()->findAllByAttributes(array('email' => $subscriber->email))
            : Comment::model()->page($url)->findAllByAttributes(array('email' => $subscriber->email));

        foreach ($comments as $comment)
        {
            $comment->notify = Comment::NOT_NOTIFY;
            $comment->save();
        }

        $this->renderPartial('unsubscribe');
    }

    /**
     * Валидация модели по ajax-запросу
     * @param $model
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']))
        {
            echo \CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    /**
     * Возвращает модальное окно, принимает массив $options
     * с двумя ключами - 'title' и 'content'
     * @param $options array
     * @return string
     */
    private function getModal($options)
    {
        return $this->renderPartial('comments.extensions.comments_widget.views.modal', array(
            'title' => $options['title'],
            'content' => $options['content'],
        ), true);
    }

    /**
     * Отправляет уведомление админу о новых комментариях
     * @param $newComment
     */
    private function sendAdminNotify($newComment)
    {
        $message = $this->renderPartial('comments.extensions.comments_widget.views.email.notifyAdmin', array(
            'newComment' => $newComment
        ), true);

        $this->module->sendMail($this->module->adminEmail, 'Новый комментарий на сайте "' . \Yii::app()->name . '"', $message);
    }

    /**
     * Отправляет пачками письма пользователям о новых комментариях
     * @param $newComment
     */
    private function sendUserNotifies($newComment)
    {
        foreach (Comment::model()->page($newComment->url)->notify()->findAll() as $subscriber)
        {
            // Если email нового комментария (отправителя) совпадает с email подписчика,
            // то выходит что это один и тот же человек, ему уведомление не высылаем, пропускаем итерацию цикла
            if ($newComment->email === $subscriber->email)
                continue;

            $message = $this->renderPartial('comments.extensions.comments_widget.views.email.notifyUser', array(
                'newComment' => $newComment,
                'userName' => $subscriber->getAuthor(),
                'userEmail' => $subscriber->getEmail(),
                'hash' => $subscriber->getHash(),
            ), true);

            $this->module->sendMail($subscriber->getEmail(), 'Новый комментарий на сайте "' . \Yii::app()->name . '"', $message);
        }
    }
}
<?php
namespace pendalf89\yii_commentator\controllers;
use pendalf89\yii_commentator\models\Comment as Comment;
use pendalf89\yii_commentator\models\NewComments as NewComments;
use pendalf89\yii_commentator\models\CommentSettings as CommentSettings;

class AdminController extends \Controller
{
    /**
     * Инициализация контроллера
     */
    public function init()
    {
        parent::init();

        \Yii::app()->clientScript->registerCssFile(
            \Yii::app()->assetManager->publish(
                \Yii::getPathOfAlias('application.modules.comments.assets.css') . '/styles.css', false, -1, true
            )
        );
    }

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
            'accessControl',
			'postOnly + delete',
            'ajaxOnly + AjaxUpdateStatus, AjaxUpdateSetNew, AjaxUpdateSetOld, AjaxDelete',
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
        if ( \Yii::app()->getModule('comments')->isSuperUser() )
            return array(
                array('allow')
            );

		return array(
			array('deny')
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
        $model = $this->loadModel($id);

        $userID = \Yii::app()->getModule('comments')->getUserID();
        NewComments::model()->deleteByPk(array('user_id'=>$userID,'comment_id'=>$id));

		$this->render('application.modules.comments.views.admin.view',array(
			'model' => $model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

        $userID = \Yii::app()->getModule('comments')->getUserID();
        NewComments::model()->deleteByPk(array('user_id'=>$userID,'comment_id'=>$id));

		if ( isset($_POST['modules_comments_models_Comment']) )
		{
			$model->attributes=$_POST['modules_comments_models_Comment'];
			if( $model->save() )
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('application.modules.comments.views.admin.update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('application.modules.comments.views.admin.index'));
	}

	/**
	 * Manages all models.
	 */
	public function actionIndex()
	{
		$model = new Comment('search');
		$model->unsetAttributes();
		if ( isset($_GET['modules_comments_models_Comment']) )
			$model->attributes = $_GET['modules_comments_models_Comment'];

		$this->render('application.modules.comments.views.admin.index', array(
			'model' => $model,
		));
	}

    /**
     * Настройки комментариев
     */
    public function actionSettings()
    {
        $model = CommentSettings::load();

        if ( isset($_POST['modules_comments_models_CommentSettings']) )
        {
            $model->attributes = $_POST['modules_comments_models_CommentSettings'];
            if ( $model->save() )
                \Yii::app()->user->setFlash('settings_saved', \Yii::t('pendalf89\yii_commentator\CommentsModule.main', 'Settings saved successfully'));
        }

        $this->render('application.modules.comments.views.admin.settings', array('model'=>$model));
    }

    /**
     * Обновляет статусы по ajax
     */
    public function actionAjaxUpdateStatus()
    {
        if ( !isset($_POST['status']) || !isset($_POST['checkboxes']) )
            return;

        foreach ($this->loadModels($_POST['checkboxes']) as $model)
        {
            $model->status = $_POST['status'];
            $model->is_new = Comment::OLD_COMMENT;
            $model->save();
        }
    }

    /**
     * Делает комментарий новым
     */
    public function actionAjaxUpdateSetNew()
    {
        if ( !isset($_POST['checkboxes']) )
            return;

        $userID = \Yii::app()->getModule('comments')->getUserID();

        foreach ($_POST['checkboxes'] as $comment_id)
        {
            $model = new NewComments();
            $model->user_id = $userID;
            $model->comment_id = $comment_id;
            $model->save();
        }
    }

    /**
     * Делает комментарий новым
     */
    public function actionAjaxUpdateSetOld()
    {
        if ( !isset($_POST['checkboxes']) )
            return;

        $userID = \Yii::app()->getModule('comments')->getUserID();

        foreach ($_POST['checkboxes'] as $comment_id)
            NewComments::model()->deleteByPk(array('user_id'=>$userID,'comment_id'=>$comment_id));
    }

    /**
     * Удаляет модели по ajax
     */
    public function actionAjaxDelete()
    {
        if ( !isset($_POST['checkboxes']) )
            return;

        foreach ($this->loadModels($_POST['checkboxes']) as $model)
            $model->delete();
    }

    /**
     * Загружает модели по массиву с id'шниками
     * @param $ids
     * @return \CActiveRecord[]
     */
    private function loadModels($ids)
    {
        return Comment::model()->findAllByAttributes( array('id' => $ids) );
    }

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Comment the loaded model
	 * @throws \CHttpException
	 */
	public function loadModel($id)
	{
		$model=Comment::model()->findByPk($id);
		if($model===null)
			throw new \CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param Comment $model the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='comment-form')
		{
			echo \CActiveForm::validate($model);
			\Yii::app()->end();
		}
	}
}

<?php $form = $this->beginWidget('CActiveForm', array(
    'action' => $model->isNewRecord ? \Yii::app()->createUrl('/comments/handler/create') : \Yii::app()->createUrl('/comments/handler/update'),
    'method' => 'post',
    'id' => $model->isNewRecord ? 'comment-form' : 'comment-form-'. $model->id,
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
        'validateOnChange' => false,
        'errorCssClass' => 'has-error',
        'successCssClass' => 'has-success',
    ),
)); ?>

<div class="row">
    <?php if ( !$user = \Yii::app()->getModule('comments')->loadUser() ) : ?>

        <div role="input-container" class="form-group col-md-6">
            <div class="input-group">
                <span class="input-group-addon">Имя:</span>
                <?php echo $form->textField($model, 'author', array('class'=>'form-control', 'placeholder' => 'Введите ваше имя')); ?>
            </div>
            <?php echo $form->error($model, 'author', array('class' => 'text-danger')); ?>
        </div>

        <div role="input-container" class="form-group col-md-6">
            <div class="input-group">
                <span class="input-group-addon">E-mail:</span>
                <?php echo $form->textField($model, 'email', array('class'=>'form-control', 'placeholder' => 'Введите ваш e-mail')); ?>
            </div>
            <?php echo $form->error($model, 'email', array('class' => 'text-danger')); ?>
        </div>

    <?php else : ?>
        <?php $model->setScenario('authorized'); ?>
        <div class="col-md-6">
            <span class="username">
                <i class="fa fa-user"></i> <?php echo $user->{\Yii::app()->getModule('comments')->usernameField}; ?>
            </span>
        </div>
    <?php endif; ?>

    <div role="input-container" class="form-group col-md-12">
        <div class="input-group">
            <span class="input-group-addon">Комментарий:</span>
            <?php echo $form->textArea($model, 'content', array('class'=>'form-control', 'placeholder' => 'Напишите комментарий', 'rows' => 3)); ?>
        </div>
        <?php echo $form->error($model, 'content', array('class' => 'text-danger')); ?>
    </div>

    <div class="form-group col-md-12">
        <div class="btn-group">
            <button role="reply" data-is-new="<?php echo $model->isNewRecord ? 'true' : 'false' ?>" class="btn btn-success"><i class="fa fa-reply"></i> Отправить комментарий</button>
            <?php if ( !empty($cancelButton) ) : ?>
                <button role="cancel" class="btn btn-danger"><i class="fa fa-times"></i> Отмена</button>
            <?php endif; ?>
        </div>
        <label class="checkbox-inline">
            <?php echo $form->checkBox($model, 'notify'); ?> Уведомлять меня о новых комментариях
        </label>
    </div>
</div>

<?php if ( !$model->isNewRecord ) : ?>
    <?php echo $form->hiddenField($model, 'id'); ?>
<?php endif; ?>

<?php if ( !empty($parent_id) ) : ?>
    <?php echo $form->hiddenField($model, 'parent_id', array('value' => $parent_id)); ?>
<?php endif; ?>

<?php echo $form->hiddenField($model, 'url', array('value' => $url)); ?>

<?php $this->endWidget(); ?>
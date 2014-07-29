<?php use modules\comments\helpers\CHelper as CHelper; ?>
<div class="admin-comments">
<h1><i class="fa fa-pencil"></i> Редактирование комментария #<?php echo $model->id; ?></h1>

<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'comment-form',
)); ?>

<p class="note">Поля, помеченные <span class="required">*</span> обязательны для заполнения</p>

    <?php echo $form->errorSummary($model, null, null, array('class'=>'alert alert-danger')); ?>

<div class="row">

    <div class="col-md-6">
        <div class="form-group">
            <?php echo $form->labelEx($model,'url'); ?>
            <?php echo $form->textField($model,'url',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'url',array('class'=>'text-danger')); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'author'); ?>
            <?php echo !empty($model->user->username) ? "<small>{$model->user->username}</small>" : ''?>
            <?php echo $form->textField($model,'author',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'author',array('class'=>'text-danger')); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'email'); ?>
            <?php echo $form->textField($model,'email',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'email',array('class'=>'text-danger')); ?>
        </div>
    </div>
    <div class="col-md-6">

        <div class="form-group">
            <?php echo $form->labelEx($model,'status'); ?>
            <?php echo $form->dropDownList($model, 'status', $model->getStatusArray(), array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'status',array('class'=>'text-danger')); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'notify'); ?>
            <?php echo $form->dropDownList($model, 'notify', $model->getNotifyStatusArray(), array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'notify',array('class'=>'text-danger')); ?>
        </div>

        <div class="form-group">
            <?php echo $form->labelEx($model,'likes'); ?>
            <?php echo $form->textField($model,'likes',array('class'=>'form-control')); ?>
            <?php echo $form->error($model,'likes',array('class'=>'text-danger')); ?>
        </div>
    </div>

    <div class="col-md-12">
        <div class="form-group">
            <?php echo $form->labelEx($model,'content'); ?>
            <?php echo $form->textArea($model,'content',array('class'=>'form-control', 'rows'=>2, 'cols'=>50)); ?>
            <?php echo $form->error($model,'content',array('class'=>'text-danger')); ?>
        </div>

        <p class="pull-left">
            <?php echo CHtml::submitButton('Обновить', array('class' => 'btn btn-success')); ?>
            <?php echo \CHtml::link('<i class="fa fa-list"></i> Менеджер комментариев', array('index')); ?>
            |
            <?php echo \CHtml::link('<i class="fa fa-search"></i> Просмотр комментария', array('view', 'id' => $model->id)); ?>
            |
            Лайки: <span class="label label-primary"><?php echo $model->getLikes(); ?></span>
            Создан: <span class="label label-success"><?php echo CHelper::date($model->created); ?></span>
            <?php if ( !empty($model->updated) ) : ?>
                Обновлён: <span class="label label-warning"><?php echo CHelper::date($model->updated); ?></span>
            <?php endif; ?>
            IP: <span class="label label-default"><?php echo $model->ip; ?></span>
        </p>

    </div>

</div>

<?php $this->endWidget(); ?>
</div>
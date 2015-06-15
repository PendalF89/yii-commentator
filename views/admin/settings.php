<div class="comments admin-comments">
    <h1><i class="fa fa-cogs"></i> Настройки модуля "Комментарии"</h1>

    <?php if ( \Yii::app()->user->hasFlash('settings_saved') ) : ?>
        <div class="alert alert-success" data-role="alert">
            <i class="fa fa-check-circle-o"></i> <?php echo \Yii::app()->user->getFlash('settings_saved'); ?>
        </div>
    <?php endif; ?>

    <?php $form = $this->beginWidget('CActiveForm'); ?>

    <p class="note">Поля, помеченные <span class="required">*</span> обязательны для заполнения</p>

    <?php echo $form->errorSummary($model, null, null, array('class'=>'alert alert-danger')); ?>

    <div class="row">

        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model,'date_format'); ?>
                <?php echo $form->textField($model,'date_format',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'date_format',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'margin'); ?>
                <?php echo $form->textField($model,'margin',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'margin',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'levels'); ?>
                <?php echo $form->textField($model,'levels',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'levels',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'edit_time'); ?>
                <?php echo $form->textField($model,'edit_time',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'edit_time',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'max_length_author'); ?>
                <?php echo $form->textField($model,'max_length_author',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'max_length_author',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'max_length_content'); ?>
                <?php echo $form->textField($model,'max_length_content',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'max_length_content',array('class'=>'text-danger')); ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <?php echo $form->labelEx($model,'likes_control'); ?>
                <?php echo $form->dropDownList($model, 'likes_control', $model->booleanArray(), array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'likes_control',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'manage_page_size'); ?>
                <?php echo $form->textField($model,'manage_page_size',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'manage_page_size',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'premoderate'); ?>
                <?php echo $form->dropDownList($model, 'premoderate', $model->booleanArray(), array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'premoderate',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'notify_admin'); ?>
                <?php echo $form->dropDownList($model, 'notify_admin', $model->booleanArray(), array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'notify_admin',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'fromEmail'); ?>
                <?php echo $form->textField($model,'fromEmail',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'fromEmail',array('class'=>'text-danger')); ?>
            </div>

            <div class="form-group">
                <?php echo $form->labelEx($model,'adminEmail'); ?>
                <?php echo $form->textField($model,'adminEmail',array('class'=>'form-control')); ?>
                <?php echo $form->error($model,'adminEmail',array('class'=>'text-danger')); ?>
            </div>
        </div>

        <div class="col-md-12">
            <p>
                <?php echo CHtml::submitButton('Сохранить', array('class' => 'btn btn-success')); ?>

                <?php echo \CHtml::link('<i class="fa fa-list"></i> Менеджер комментариев', array('index')); ?>
            </p>
        </div>

    </div>

    <?php $this->endWidget(); ?>
</div>
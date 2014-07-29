<?php use pendalf89\comments\helpers\CHelper as CHelper; ?>
<div class="admin-comments">
<h1><i class="fa fa-search"></i> Просмотр комментария #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'parent_id',
		'user_id',
        array(
            'name' => 'url',
            'type' => 'html',
            'value' => \CHtml::link($model->getAbsoluteUrl(), $model->getAbsoluteUrl()),
        ),
		'author',
		'email',
		'content',
		'ip',
        array(
            'name' => 'likes',
            'value' => $model->getLikes(),
        ),
        array(
            'name' => 'status',
            'value' => $model->getStatus(),
        ),
        array(
            'name' => 'notify',
            'value' => $model->getNotifyStatus(),
        ),
        array(
            'name' => 'created',
            'value' => CHelper::date($model->created),
        ),
        array(
            'name' => 'updated',
            'value' => CHelper::date($model->updated),
        ),
	),
)); ?>

<p class="control">
    <?php echo \CHtml::link('<i class="fa fa-list"></i> Менеджер комментариев', array('index')); ?>
    |
    <?php echo \CHtml::link('<i class="fa fa-pencil"></i> Редактировать комментарий', array('update', 'id' => $model->id)); ?>
    |
    <?php echo CHtml::link('<i class="fa fa-cog"></i> Настройки', array('settings')); ?>
</p>
</div>
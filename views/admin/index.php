<?php
use modules\comments\helpers\CHelper as CHelper;
use modules\comments\models\Comment as Comment;

Yii::app()->clientScript->registerScript('search', "
$('.search-form form').submit(function(){
	$('#comment-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('reloadGrid',
'function reloadGrid() {
    $.fn.yiiGridView.update("comment-grid");
}');
?>

<div class="admin-comments">

<h1>Менеджер комментариев</h1>

<p>
    В поисковый запрос можно вводить условные операторы (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> или <b>=</b>).
</p>

<?php $form = $this->beginWidget('CActiveForm', array(
    'enableAjaxValidation'=>true,
)); ?>

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id' => 'comment-grid',
	'dataProvider' => $model->search(),
	'ajaxUrl' => \Yii::app()->createUrl('/comments/admin/index'),
	'filter' => $model,
    'rowCssClassExpression' => function($row, $data) {
        return $row%2? "even{$data->getRowCssClass()}" : "odd{$data->getRowCssClass()}";
    },
	'columns' => array(
        array(
            'class' => 'CCheckBoxColumn',
            'id' => 'checkboxes',
            'selectableRows' => 2,
        ),
		array(
            'name' => 'id',
            'htmlOptions' => array(
                'width' => '50px',
                'style' => 'text-align: center;'
            ),
        ),
        array(
            'name' => 'url',
            'type' => 'html',
            'value' => function($data) {
                return CHtml::link($data->loadPageTitle(), $data->getAbsoluteUrl());
            },
        ),
        array(
            'name' => 'author',
            'value' => function($data) {
                return $data->getAuthor();
            },
        ),
        array(
            'name' => 'email',
            'value' => function($data) {
                return $data->getEmail();
            },
        ),
        array(
            'name' => 'content',
            'value' => function($data) {
                return CHelper::cutStr($data->content);
            },
        ),
        array(
            'name' => 'likes',
            'value' => function($data) {
                return $data->getLikes();
            },
            'htmlOptions' => array(
                'width' => '50px',
                'style' => 'text-align: center;'
            ),
        ),
        array(
            'name' => 'status',
            'filter' => Comment::getStatusArray(),
            'value' => function($data) {
                return $data->getStatus();
            },
            'htmlOptions' => array(
                'width' => '120px',
                'style' => 'text-align: center;'
            ),
        ),
        array(
            'name' => 'created',
            'filter' => false,
            'value' => function($data) {
                    return CHelper::date( $data->created );
                },
            'htmlOptions' => array(
                'width' => '140px',
                'style' => 'text-align: center;'
            ),
        ),
		array(
            'header' => 'Операции',
			'class'=>'CButtonColumn',
            'htmlOptions' => array(
                'width' => '70px',
                'style' => 'text-align: center;'
            ),
		),
	),
)); ?>

<p class="control">
    Статус:
    <?php echo CHtml::dropDownList('status', '',  Comment::getStatusArray(), array('empty' => '--Выберите статус--')); ?>

    <?php echo CHtml::ajaxSubmitButton('Применить', array('ajaxUpdateStatus'), array('success' => 'reloadGrid')); ?>
    |
    <?php echo CHtml::ajaxSubmitButton('Отметить прочитанными', array('ajaxUpdateSetOld'), array('success' => 'reloadGrid')); ?>
    |
    <?php echo CHtml::ajaxSubmitButton('Отметить новыми', array('ajaxUpdateSetNew'), array('success' => 'reloadGrid')); ?>
    |
    <?php echo CHtml::ajaxSubmitButton('Удалить', array('ajaxDelete'), array(
        'beforeSend' => 'function(){
            return confirm("' . Yii::t('modules\comments\CommentsModule.main', 'Are you sure you want to delete selected items?') . '");
        }',
        'success' => 'reloadGrid'
    )); ?>
    |
    <?php echo CHtml::link('<i class="fa fa-cog"></i> Настройки', array('settings')); ?>
</p>

<?php $this->endWidget(); ?>
</div>
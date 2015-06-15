<?php \Yii::app()->clientScript->registerScript(
    'Comments',
    'var commentsReplyFormUrl = "'. \Yii::app()->createUrl('/comments/handler/replyform') .'";
    var commentsUpdateFormUrl = "'. \Yii::app()->createUrl('/comments/handler/updateform') .'";
    var commentsCreateUrl = "'. \Yii::app()->createUrl('/comments/handler/create') .'";
    var commentsUpdateUrl = "'. \Yii::app()->createUrl('/comments/handler/update') .'";
    var commentsDeleteUrl = "'. \Yii::app()->createUrl('/comments/handler/delete') .'";
    var commentsLikesUrl = "'. \Yii::app()->createUrl('/comments/handler/likes') .'";
    var pageUrl = "'. \Yii::app()->request->requestUri .'";',
    \CClientScript::POS_HEAD
); ?>
<?php $enableMicrodata = $this->enableMicrodata; ?>

<div class="comments">
    <?php if ( !empty($count) ) : ?>
        <span class="title"><i class="fa fa-comments"></i> Комментарии (<span<?php echo $enableMicrodata ? ' itemprop="commentCount"' : '' ?> data-role="count"><?php echo $count; ?></span>):</span>
    <?php endif; ?>

    <div data-role="tree"><?php $this->renderTree(); ?></div>

    <span class="title"><i class="fa fa-comment"></i> Добавить комментарий:</span>
    <?php $this->render('form', array('model' => $model, 'url' => \Yii::app()->request->requestUri)); ?>

    <div data-role="modal-container"></div>
</div>
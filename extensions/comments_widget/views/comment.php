<?php use pendalf89\yii_commentator\helpers\CHelper as CHelper; ?>
<?php use pendalf89\yii_commentator\models\NewComments as NewComments; ?>
<?php $enableMicrodata = $this->enableMicrodata; ?>
<a name="comment_<?php echo $comment->id; ?>"></a>

<?php $newCssClass = ''; ?>
<?php if ( $comment->isNew() ) : ?>
    <?php $newCssClass = ' new'; ?>
    <?php if ( $userID = \Yii::app()->getModule('comments')->getUserID() ) : ?>
        <?php NewComments::model()->deleteByPk(array('user_id'=>$userID,'comment_id'=>$comment->id)); ?>
    <?php endif; ?>
<?php endif; ?>

<div<?php echo $enableMicrodata ? ' itemprop="comment" itemscope itemtype="http://schema.org/Comment"' : '' ?> class="comment well well-sm<?php echo $newCssClass; ?>"<?php echo ($margin != 0) ? ' style="margin-left: ' . $margin . 'px"' : '' ?> data-id="<?php echo $comment->id; ?>">
    <span<?php echo $enableMicrodata ? ' itemprop="creator" itemscope itemtype="http://schema.org/Person"' : '' ?> class="author"><i class="fa fa-user"></i>
        <?php echo !empty($comment->user) ? '<i class="fa fa-star star"></i>' : ''; ?>
	    <?php echo $enableMicrodata ? '<span itemprop="name">' . $comment->getAuthor() . '</span>' : $comment->getAuthor() ?>
    </span>

    <div class="pull-right">
        <a href="#comment_like" title="Мне нравится" data-like="1" class="label label-success"><i class="fa fa-thumbs-up"></i></a>
        <span data-role="likes" class="label label-primary"><?php echo $comment->getLikes(); ?></span>
        <a href="#comment_like" title="Мне не нравится" data-like="0" class="label label-danger"><i class="fa fa-thumbs-down"></i></a>

        <time<?php echo $enableMicrodata ? ' itemprop="datePublished"' : '' ?> datetime="<?php echo date('Y-m-d', $comment->created) ?>" class="label label-default"><?php echo CHelper::date( $comment->created ); ?></time>

        <a href="#comment_<?php echo $comment->id; ?>" class="label label-default" title="Ссылка на этот комментарий">#</a>
    </div>

    <div<?php echo $enableMicrodata ? ' itemprop="text"' : '' ?> class="content"><?php echo $comment->content; ?></div>
    <hr>
    <div class="btn-group">
        <a href="#comment_reply" title="Ответить на этот комментарий" data-id="<?php echo $comment->id; ?>" class="btn btn-default btn-xs"><i class="fa fa-reply"></i> Ответить</a>
        <?php if ( $comment->canUpdated() ) : ?>
            <a href="#comment_edit" title="Редактировать" data-id="<?php echo $comment->id; ?>" class="btn btn-warning btn-xs"><i class="fa fa-pencil"></i> Редактировать</a>
        <?php endif; ?>
        <?php if ( $comment->canDeleted() ) : ?>
            <a href="#comment_delete" title="Удалить" data-id="<?php echo $comment->id; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i> Удалить</a>
        <?php endif; ?>
    </div>

    <div data-role="dynamic-form-container"></div>
</div>
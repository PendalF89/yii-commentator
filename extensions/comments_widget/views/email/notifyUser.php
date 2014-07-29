Уважаемый <?php echo $userName; ?>!<br>
Вы получили это письмо потому, что подписаны на уведомления о новых комментариях на странице
<?php echo $commentPage = CHtml::link(
    $newComment->loadPageTitle(),
    $newComment->getAbsolutePageUrl(),
    array('target'=>'_blank')
) ; ?>
<p>
    Пользователь <strong><?php echo $newComment->getAuthor(); ?></strong> оставил комментарий:
</p>
<p>
    <i><?php echo $newComment->content; ?></i>
</p>
Дата комментирования: <?php echo date('d.m.Y | H:i:s', $newComment->getLastModified()); ?><br>
<?php echo CHtml::link(
    'Перейти на страницу для ответа',
    $newComment->getAbsoluteUrl(),
    array('target'=>'_blank')
) ; ?>
<hr/>

<p>
    <small>
    Вы всегда можете отписаться от рассылки комментариев со страницы <?php echo $commentPage; ?>, перейдя по этой <?php echo CHtml::link('ссылке', Yii::app()->createAbsoluteUrl(
        '/comments/handler/unsubscribe', array('hash' => $hash, 'url' => $newComment->url)), array('target'=>'_blank')); ?>.<br>
    Если вы хотите отписаться от рассылки всех комментариев, перейдите по этой <?php echo CHtml::link('ссылке', Yii::app()->createAbsoluteUrl(
        '/comments/handler/unsubscribe', array('hash' => $hash)), array('target'=>'_blank')); ?>
    </small>
</p>
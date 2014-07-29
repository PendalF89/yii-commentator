Пользователь <strong><?php echo $newComment->getAuthor(); ?></strong> оставил комментарий:
<p><i><?php echo $newComment->content; ?></i></p>
<hr/>
Дата комментирования: <?php echo date('d.m.Y | H:i:s', $newComment->getLastModified()); ?><br>
E-mail автора: <?php echo $newComment->getEmail(); ?><br>
Страница: <?php echo CHtml::link(
    $newComment->loadPageTitle(),
    $newComment->getAbsoluteUrl(),
    array('target'=>'_blank')
); ?>
DROP TABLE IF EXISTS `comment_settings`;
CREATE TABLE `comment_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `date_format` varchar(128) NOT NULL DEFAULT 'd.m.Y | H:i:s' COMMENT 'Формат даты',
  `margin` int(11) NOT NULL DEFAULT '50' COMMENT 'Отступ узла дерева в пикселях',
  `levels` int(11) NOT NULL DEFAULT '6' COMMENT 'Количество уровней дерева',
  `edit_time` int(11) NOT NULL DEFAULT '60' COMMENT 'Время в (секундах), в течение которого можно отредактировать комментарий',
  `max_length_author` int(11) NOT NULL DEFAULT '128' COMMENT 'Максимальная длина поля "автор" (max 128)',
  `max_length_content` int(11) NOT NULL DEFAULT '1000' COMMENT 'Максимальная длина поля "комментарий"',
  `likes_control` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Может ли суперпользователь накручивать лайки',
  `manage_page_size` int(11) NOT NULL DEFAULT '50' COMMENT 'Количество элементов на странице управления комментариями',
  `premoderate` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Премодерация комментариев (появляются после провеки модератором)',
  `notify_admin` tinyint(4) NOT NULL DEFAULT '0' COMMENT 'Уведомлять админа о новых комментария',
  `fromEmail` varchar(128) NOT NULL COMMENT 'E-mail, с которго будут приходить письма',
  `adminEmail` varchar(128) NOT NULL COMMENT 'E-mail админа',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `parent_id` int(11) NOT NULL DEFAULT '0' COMMENT 'Родитель',
  `user_id` int(11) DEFAULT NULL COMMENT 'Пользователь',
  `url` text NOT NULL COMMENT 'URL',
  `author` varchar(128) DEFAULT NULL COMMENT 'Автор',
  `email` varchar(128) DEFAULT NULL COMMENT 'Email',
  `content` text NOT NULL COMMENT 'Содержание',
  `ip` varchar(15) NOT NULL COMMENT 'IP',
  `likes` int(11) NOT NULL DEFAULT '0' COMMENT 'Лайки',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT 'Статус',
  `notify` int(11) NOT NULL DEFAULT '0' COMMENT 'Уведомлять автора о новых комментариях?',
  `created` int(11) NOT NULL COMMENT 'Создан',
  `updated` int(11) DEFAULT NULL COMMENT 'Обновлён',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
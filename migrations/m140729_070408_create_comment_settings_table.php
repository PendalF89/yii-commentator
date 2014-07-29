<?php

class m140729_070408_create_comment_settings_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('comment_settings', array(
            'id' => 'pk',
            'date_format' => 'varchar(128) NOT NULL',
            'margin' => 'int(11) NOT NULL',
            'levels' => 'int(11) NOT NULL',
            'edit_time' => 'int(11) NOT NULL',
            'max_length_author' => 'int(11) NOT NULL',
            'max_length_content' => 'int(11) NOT NULL',
            'likes_control' => 'tinyint(4) NOT NULL',
            'manage_page_size' => 'int(11) NOT NULL',
            'premoderate' => 'tinyint(4) NOT NULL',
            'notify_admin' => 'tinyint(4) NOT NULL',
            'fromEmail' => 'varchar(128) NOT NULL',
            'adminEmail' => 'varchar(128) NOT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->insert('comment_settings', array(
            'date_format' => 'd.m.Y | H:i:s',
            'margin' => 50,
            'levels' => 6,
            'edit_time' => 60,
            'max_length_author' => 128,
            'max_length_content' => 1000,
            'likes_control' => 0,
            'manage_page_size' => 50,
            'premoderate' => 0,
            'notify_admin' => 0,
            'fromEmail' => 'noreply@example.com',
            'adminEmail' => 'admin@example.com',
        ));
    }

    public function down()
    {
        $this->dropTable('comment_settings');
    }
}
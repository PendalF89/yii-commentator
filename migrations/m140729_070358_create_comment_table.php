<?php

class m140729_070358_create_comment_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('comment', array(
            'id' => 'pk',
            'parent_id' => 'int(11) NOT NULL DEFAULT 0',
            'user_id' => 'int(11) DEFAULT NULL',
            'url' => 'text NOT NULL',
            'author' => 'varchar(128) DEFAULT NULL',
            'email' => 'varchar(128) DEFAULT NULL',
            'content' => 'text NOT NULL',
            'ip' => 'varchar(15) NOT NULL',
            'likes' => 'int(11) NOT NULL DEFAULT 0',
            'status' => 'int(11) NOT NULL DEFAULT 0',
            'notify' => 'int(11) NOT NULL DEFAULT 0',
            'created' => 'int(11) NOT NULL',
            'updated' => 'int(11) DEFAULT NULL',
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('comment');
    }
}
<?php

class m140729_070433_create_new_comments_table extends CDbMigration
{
    public function up()
    {
        $this->createTable('new_comments', array(
            'comment_id' => 'int(11) NOT NULL',
            'user_id' => 'int(11) NOT NULL',
            'PRIMARY KEY (comment_id, user_id)'
        ), 'ENGINE=InnoDB DEFAULT CHARSET=utf8');
    }

    public function down()
    {
        $this->dropTable('new_comments');
    }
}
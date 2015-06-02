<?php

use yii\db\Schema;
use kfeldmaier\filerepository\migrations\Migration;

class m150602_124833_init extends Migration
{
    public function up()
    {
        // {{{repository
        $this->createTable('{{%repository}}', [
            'repository_id'             => Schema::TYPE_PK,
            'path'                      => Schema::TYPE_TEXT . ' NULL',
            'status'                    => Schema::TYPE_STRING . '(50) NOT NULL',
            'start_at'                  => Schema::TYPE_INTEGER . '(11) NULL', 
            'end_at'                    => Schema::TYPE_INTEGER . '(11) NULL', 
            'update_iv'                 => Schema::TYPE_INTEGER . '(11) NULL', 
            'created_at'                => Schema::TYPE_INTEGER . '(11) NULL', 
            'updated_at'                => Schema::TYPE_INTEGER . '(11) NULL', 
        ], $this->tableOptions);
        // }}} 

        // {{{directory_link
        $this->createTable('{{%directory_link}}', [
            'repository_id'             => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'directory_link_id'         => Schema::TYPE_PK,
            'parent_directory_link_id'  => Schema::TYPE_INTEGER . '(11) NULL',
            'path'                      => Schema::TYPE_TEXT . 'NOT NULL',
            'directory_name'            => Schema::TYPE_STRING . '(11) NULL', 
            'status'                    => Schema::TYPE_STRING . '(50) NOT NULL', 
            'previous_status'           => Schema::TYPE_STRING . '(50) NOT NULL', 
            'job_status'                => Schema::TYPE_SMALLINT . '(6) NULL', 
            'directory_created_at'      => Schema::TYPE_INTEGER . '(11) NULL', 
            'directory_updated_at'      => Schema::TYPE_INTEGER . '(11) NULL', 
            'created_at'                => Schema::TYPE_INTEGER . '(11) NULL', 
            'updated_at'                => Schema::TYPE_INTEGER . '(11) NULL', 
        ], $this->tableOptions);
        $this->addForeignKey('fk_directory_repository', '{{%directory_link}}', 'repository_id', '{{%repository}}', 'repository_id', 'CASCADE', 'CASCADE');
        // }}} 

        // {{{file_link
        $this->createTable('{{%file_link}}', [
            'file_link_id'              => Schema::TYPE_PK,
            'directory_link_id'         => Schema::TYPE_INTEGER . '(11) NOT NULL',
            'file_name'                 => Schema::TYPE_STRING . '(255) NOT NULL',
            'file_extension'            => Schema::TYPE_STRING . '(20) NOT NULL', 
            'status'                    => Schema::TYPE_STRING . '(50) NOT NULL', 
            'previous_status'           => Schema::TYPE_STRING . '(50) NOT NULL', 
            'file_size'                 => Schema::TYPE_INTEGER . '(11) NULL', 
            'file_created_at'           => Schema::TYPE_INTEGER . '(11) NULL', 
            'file_updated_at'           => Schema::TYPE_INTEGER . '(11) NULL', 
            'created_at'                => Schema::TYPE_INTEGER . '(11) NULL', 
            'updated_at'                => Schema::TYPE_INTEGER . '(11) NULL', 
        ], $this->tableOptions);
        $this->addForeignKey('fk_file_directory', '{{%file_link}}', 'directory_link_id', '{{%directory_link}}', 'directory_link_id', 'CASCADE', 'CASCADE');
        // }}} 
    }

    public function down()
    {
        $this->dropTable('{{%repository}}');
        $this->dropTable('{{%directory_link}}');
        $this->dropTable('{{%file_link}}');
    }
}
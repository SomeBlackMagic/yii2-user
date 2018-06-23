<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use dektrium\user\migrations\Migration;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class m140209_132017_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id'                   => $this->primaryKey(),
            'username'             => $this->string(25)->notNull(),
            'email'                => $this->string(255)->notNull(),
            'passwordHash'        => $this->string(60)->notNull(),
            'authKey'             => $this->string(32)->notNull(),
            'confirmationToken'   => $this->string(32)->null(),
            'confirmationSentAt' => $this->integer()->null(),
            'confirmedAt'         => $this->integer()->null(),
            'unconfirmedEmail'    => $this->string(255)->null(),
            'recoveryToken'       => $this->string(32)->null(),
            'recoverySentAt'     => $this->integer()->null(),
            'blockedAt'           => $this->integer()->null(),
            'registeredFrom'      => $this->integer()->null(),
            'loggedInFrom'       => $this->integer()->null(),
            'loggedInAt'         => $this->integer()->null(),
            'createdAt'           => $this->integer()->notNull(),
            'updatedAt'           => $this->integer()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('{{%user_unique_username}}', '{{%user}}', 'username', true);
        $this->createIndex('{{%user_unique_email}}', '{{%user}}', 'email', true);
        $this->createIndex('{{%user_confirmation}}', '{{%user}}', 'id, confirmationToken', true);
        $this->createIndex('{{%user_recovery}}', '{{%user}}', 'id, recoveryToken', true);

        $this->createTable('{{%profile}}', [
            'userId'        => $this->integer()->notNull()->append('PRIMARY KEY'),
            'name'           => $this->string(255)->null(),
            'public_email'   => $this->string(255)->null(),
            'gravatarEmail' => $this->string(255)->null(),
            'gravatarId'    => $this->string(32)->null(),
            'location'       => $this->string(255)->null(),
            'website'        => $this->string(255)->null(),
            'bio'            => $this->text()->null(),
        ], $this->tableOptions);

        $this->addForeignKey('{{%fk_user_profile}}', '{{%profile}}', 'userId', '{{%user}}', 'id', $this->cascade, $this->restrict);
    }

    public function down()
    {
        $this->dropTable('{{%profile}}');
        $this->dropTable('{{%user}}');
    }
}

<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use SomeBlackMagic\Yii2User\migrations\Migration;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class m140504_130429_create_token_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%token}}', [
            'userId'    => $this->integer()->notNull(),
            'code'       => $this->string(32)->notNull(),
            'createdAt' => $this->integer()->notNull(),
            'type'       => $this->smallInteger()->notNull(),
        ], $this->tableOptions);

        $this->createIndex('{{%token_unique}}', '{{%token}}', ['userId', 'code', 'type'], true);
        $this->addForeignKey('{{%fk_user_token}}', '{{%token}}', 'userId', '{{%user}}', 'id', $this->cascade, $this->restrict);
    }

    public function down()
    {
        $this->dropTable('{{%token}}');
    }
}

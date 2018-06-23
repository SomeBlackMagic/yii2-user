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
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class m140504_113157_update_tables extends Migration
{
    public function up()
    {
        // user table
        $this->dropIndex('{{%user_confirmation}}', '{{%user}}');
        $this->dropIndex('{{%user_recovery}}', '{{%user}}');
        $this->dropColumn('{{%user}}', 'confirmationToken');
        $this->dropColumn('{{%user}}', 'confirmationSentAt');
        $this->dropColumn('{{%user}}', 'recoveryToken');
        $this->dropColumn('{{%user}}', 'recoverySentAt');
        $this->dropColumn('{{%user}}', 'loggedInFrom');
        $this->dropColumn('{{%user}}', 'loggedInAt');
        $this->renameColumn('{{%user}}', 'registeredFrom', 'registrationIp');
        $this->addColumn('{{%user}}', 'flags', $this->integer()->notNull()->defaultValue(0));

        // account table
        $this->renameColumn('{{%account}}', 'properties', 'data');
    }

    public function down()
    {
        // account table
        $this->renameColumn('{{%account}}', 'data', 'properties');

        // user table
        if ($this->dbType == 'sqlsrv') {
            // this is needed because we need to drop the default constraint first
            $this->dropColumnConstraints('{{%user}}', 'flags');
        }
        $this->dropColumn('{{%user}}', 'flags');
        $this->renameColumn('{{%user}}', 'registrationIp', 'registeredFrom');
        $this->addColumn('{{%user}}', 'loggedInAt', $this->integer());
        $this->addColumn('{{%user}}', 'loggedInFrom', $this->integer());
        $this->addColumn('{{%user}}', 'recoverySentAt', $this->integer());
        $this->addColumn('{{%user}}', 'recoveryToken', $this->string(32));
        $this->addColumn('{{%user}}', 'confirmationSentAt', $this->integer());
        $this->addColumn('{{%user}}', 'confirmationToken', $this->string(32));
        $this->createIndex('{{%user_confirmation}}', '{{%user}}', 'id, confirmationToken', true);
        $this->createIndex('{{%user_recovery}}', '{{%user}}', 'id, recoveryToken', true);
    }
}

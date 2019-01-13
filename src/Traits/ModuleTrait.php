<?php

namespace SomeBlackMagic\Yii2User\Traits;

use SomeBlackMagic\Yii2User\Module;

/**
 * Trait ModuleTrait
 *
 * @property-read Module $module
 * @package SomeBlackMagic\Yii2User\Traits
 */
trait ModuleTrait
{
    /**
     * @return Module
     */
    public function getModule()
    {
        return \Yii::$app->getModule('user');
    }

    /**
     * @return \yii\db\Connection
     */
    public static function getDb()
    {
        return \Yii::$app->getModule('user')->getDb();
    }
}

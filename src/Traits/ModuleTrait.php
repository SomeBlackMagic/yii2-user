<?php

namespace SomeBlackMagic\Yii2User\Traits;

use SomeBlackMagic\Yii2User\Module;

/**
 * Trait ModuleTrait
 *
 * @property-read Module $module
 * @package dektrium\user\Traits
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
     * @return string
     */
    public static function getDb()
    {
        return \Yii::$app->getModule('user')->getDb();
    }
}

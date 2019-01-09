<?php
/**
 * @author Andru Cherny <acherny@minexsystems.com>
 * @date: 25.07.18 - 11:14
 */


namespace SomeBlackMagic\Yii2User\Interfaces;


use SomeBlackMagic\Yii2User\Module;
use yii\db\ActiveRecordInterface;

interface BaseModelInterface extends ActiveRecordInterface
{
    /**
     * @return Module
     */
    public function getModule();
    
    /**
     * @return string
     */
    public static function getDb();
}
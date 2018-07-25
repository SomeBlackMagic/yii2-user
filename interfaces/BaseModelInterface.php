<?php
/**
 * @author Andru Cherny <acherny@minexsystems.com>
 * @date: 25.07.18 - 11:14
 */


namespace dektrium\user\interfaces;


use dektrium\user\Module;
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
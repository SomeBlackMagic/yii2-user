<?php
/**
 * @author Andru Cherny <acherny@minexsystems.com>
 * @date: 25.07.18 - 11:11
 */


namespace SomeBlackMagic\Yii2User\Interfaces;

/**
 * Interface TokenModelInterface
 * @package SomeBlackMagic\Yii2User\Interfaces
 */
interface TokenModelInterface extends BaseModelInterface
{
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser();
    
    /**
     * @return string
     */
    public function getUrl();
    
    /**
     * @return bool Whether token has expired.
     */
    public function getIsExpired();
}

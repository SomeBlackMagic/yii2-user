<?php
namespace dektrium\user\interfaces;

/**
 * @author Andru Cherny <acherny@minexsystems.com>
 * @date: 25.07.18 - 11:09
 */


interface RecoveryFormInterface
{
    public function sendRecoveryMessage();
    
    public function resetPassword(TokenModelInterface $token);
}
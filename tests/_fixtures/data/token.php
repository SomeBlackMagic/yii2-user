<?php

use \SomeBlackMagic\Yii2User\Models\Token;

$time = time();

return [
    'confirmation' => [
        'userId'    => 2,
        'code'       => 'NO2aCmBIjFQX624xmAc3VBu7Th3NJoa6',
        'type'       => Token::TYPE_CONFIRMATION,
        'createdAt' => $time,
    ],
    'expired_confirmation' => [
        'userId'    => 3,
        'code'       => 'qxYa315rqRgCOjYGk82GFHMEAV3T82AX',
        'type'       => Token::TYPE_CONFIRMATION,
        'createdAt' => $time - 86401,
    ],
    'expired_recovery' => [
        'userId'    => 5,
        'code'       => 'a5839d0e73b9c525942c2f59e88c1aaf',
        'type'       => Token::TYPE_RECOVERY,
        'createdAt' => $time - 21601,
    ],
    'recovery' => [
        'userId'    => 6,
        'code'       => '6f5d0dad53ef73e6ba6f01a441c0e602',
        'type'       => Token::TYPE_RECOVERY,
        'createdAt' => $time,
    ],
];

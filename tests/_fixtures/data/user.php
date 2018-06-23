<?php

$time = time();

return [
    'user' => [
        'username'      => 'user',
        'email'         => 'user@example.com',
        'passwordHash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
        'authKey'      => '39HU0m5lpjWtqstFVGFjj6lFb7UZDeRq',
        'createdAt'    => $time,
        'updatedAt'    => $time,
        'confirmedAt'  => $time,
    ],
    'unconfirmed' => [
        'username'      => 'joe',
        'email'         => 'joe@example.com',
        'passwordHash' => '$2y$13$CIH1LSMPzU9xDCywt3QO8uovAu2axp8hwuXVa72oI.1G/USsGyMBS',
        'authKey'      => 'mhh1A6KfqQLmHP-MiWN0WB0M90Q2u5OE',
        'createdAt'    => $time,
        'updatedAt'    => $time,
    ],
    'unconfirmed_with_expired_token' => [
        'username'      => 'john',
        'email'         => 'john@example.com',
        'passwordHash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
        'authKey'      => 'h6OS9csJbZEOW59ZILmJxU6bCiqVno9A',
        'createdAt'    => $time - 86401,
        'updatedAt'    => $time - 86401,
    ],
    'blocked' => [
        'username'      => 'steven',
        'email'         => 'steven@example.com',
        'passwordHash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
        'authKey'      => 'TnXTrtLdj-YJBlG2A6jFHJreKgbsLYCa',
        'createdAt'    => $time,
        'updatedAt'    => $time,
        'blockedAt'    => $time,
        'confirmedAt'  => $time,
    ],
    'user_with_expired_recoveryToken' => [
        'username'      => 'andrew',
        'email'         => 'andrew@example.com',
        'passwordHash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
        'authKey'      => 'qxYa315rqRgCOjYGk82GFHMEAV3T82AX',
        'createdAt'    => $time - 21601,
        'updatedAt'    => $time - 21601,
        'confirmedAt'  => $time - 21601,
    ],
    'user_with_recoveryToken' => [
        'username'      => 'alex',
        'email'         => 'alex@example.com',
        'passwordHash' => '$2y$13$qY.ImaYBppt66qez6B31QO92jc5DYVRzo5NxM1ivItkW74WsSG6Ui',
        'authKey'      => 'zQh1A65We0AmHPOMiWN0WB0M90Q24ziU',
        'createdAt'    => $time,
        'updatedAt'    => $time,
        'confirmedAt'  => $time,
    ],
];

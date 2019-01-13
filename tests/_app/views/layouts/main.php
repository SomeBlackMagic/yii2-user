<?php

use yii\helpers\Html;

if (Yii::$app->user->getIsGuest()) {
    echo Html::a('Login', ['/user/security/login']);
    echo Html::a('Registration', ['/user/registration/register']);
} else {
    echo Html::a('Logout', ['/user/security/logout']);
}

echo $content;

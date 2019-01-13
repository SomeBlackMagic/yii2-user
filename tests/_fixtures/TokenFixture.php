<?php

namespace tests\_fixtures;

use yii\test\ActiveFixture;

class TokenFixture extends ActiveFixture
{
    public $modelClass = \SomeBlackMagic\Yii2User\Models\Token::class;

    public $depends = [
        \tests\_fixtures\UserFixture::class
    ];
}

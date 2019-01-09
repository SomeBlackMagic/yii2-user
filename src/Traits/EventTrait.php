<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SomeBlackMagic\Yii2User\Traits;

use SomeBlackMagic\Yii2User\Events\AuthEvent;
use SomeBlackMagic\Yii2User\Events\ConnectEvent;
use SomeBlackMagic\Yii2User\Events\FormEvent;
use SomeBlackMagic\Yii2User\Events\ProfileEvent;
use SomeBlackMagic\Yii2User\Events\ResetPasswordEvent;
use SomeBlackMagic\Yii2User\Events\UserEvent;
use SomeBlackMagic\Yii2User\Models\Account;
use SomeBlackMagic\Yii2User\Models\Profile;
use SomeBlackMagic\Yii2User\Models\RecoveryForm;
use SomeBlackMagic\Yii2User\Models\Token;
use SomeBlackMagic\Yii2User\Models\User;
use yii\authclient\ClientInterface;
use yii\base\Model;

/**
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
trait EventTrait
{
    /**
     * @param  Model $form
     * @return FormEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getFormEvent(Model $form): FormEvent
    {
        return \Yii::createObject(['class' => FormEvent::class, 'form' => $form]);
    }

    /**
     * @param  User $user
     * @return UserEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getUserEvent(User $user): UserEvent
    {
        return \Yii::createObject(['class' => UserEvent::class, 'user' => $user]);
    }

    /**
     * @param  Profile $profile
     * @return ProfileEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getProfileEvent(Profile $profile): ProfileEvent
    {
        return \Yii::createObject(['class' => ProfileEvent::class, 'profile' => $profile]);
    }


    /**
     * @param  Account $account
     * @param  User $user
     * @return ConnectEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getConnectEvent(Account $account, User $user): ConnectEvent
    {
        return \Yii::createObject(['class' => ConnectEvent::class, 'account' => $account, 'user' => $user]);
    }

    /**
     * @param  Account $account
     * @param  ClientInterface $client
     * @return AuthEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getAuthEvent(Account $account, ClientInterface $client): AuthEvent
    {
        return \Yii::createObject(['class' => AuthEvent::class, 'account' => $account, 'client' => $client]);
    }

    /**
     * @param  Token $token
     * @param  RecoveryForm $form
     * @return ResetPasswordEvent
     * @throws \yii\base\InvalidConfigException
     */
    protected function getResetPasswordEvent(Token $token = null, RecoveryForm $form = null): ResetPasswordEvent
    {
        return \Yii::createObject(['class' => ResetPasswordEvent::class, 'token' => $token, 'form' => $form]);
    }
}

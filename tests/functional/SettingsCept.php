<?php

/**
 * @var Codeception\Scenario $scenario
 */

use SomeBlackMagic\Yii2User\Models\Token;
use SomeBlackMagic\Yii2User\Models\User;
use tests\_fixtures\ProfileFixture;
use tests\_fixtures\UserFixture;
use tests\_pages\LoginPage;
use tests\_pages\SettingsPage;
use yii\helpers\Html;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure that account settings page work');
$I->haveFixtures(['user' => UserFixture::class, 'profile' => ProfileFixture::class]);

$page = LoginPage::openBy($I);
$user = $I->grabFixture('user', 'user');
$page->login($user->username, 'qwerty');

$page = SettingsPage::openBy($I);

$I->amGoingTo('check that current password is required and must be valid');
$page->update($user->email, $user->username, 'wrong');
$I->see('Current password is not valid');

$I->amGoingTo('check that email is changing properly');
$page->update('new_user@example.com', $user->username, 'qwerty');
$I->seeRecord(User::class, ['email' => $user->email, 'unconfirmedEmail' => 'new_user@example.com']);
$I->see('A confirmation message has been sent to your new email address');
$user  = $I->grabRecord(User::class, ['id' => $user->id]);
$token = $I->grabRecord(Token::class, ['userId' => $user->id, 'type' => Token::TYPE_CONFIRM_NEW_EMAIL]);
/** @var yii\swiftmailer\Message $message */
$message = $I->grabLastSentEmail();
$I->assertArrayHasKey($user->unconfirmedEmail, $message->getTo());
$I->assertContains(Html::encode($token->getUrl()), utf8_encode(quoted_printable_decode($message->getSwiftMessage()->toString())));

Yii::$app->user->logout();

$I->amGoingTo('log in using new email address before clicking the confirmation link');
$page = LoginPage::openBy($I);
$page->login('new_user@example.com', 'qwerty');
$I->see('Invalid login or password');

$I->amGoingTo('log in using new email address after clicking the confirmation link');
$user->attemptEmailChange($token->code);
$page->login('new_user@example.com', 'qwerty');
$I->see('Logout');
$I->seeRecord(User::class, [
    'id' => 1,
    'email' => 'new_user@example.com',
    'unconfirmedEmail' => null,
]);

$I->amGoingTo('reset email changing process');
$page = SettingsPage::openBy($I);
$page->update('user@example.com', $user->username, 'qwerty');
$I->see('A confirmation message has been sent to your new email address');
$I->seeRecord(User::class, [
    'id'    => 1,
    'email' => 'new_user@example.com',
    'unconfirmedEmail' => 'user@example.com',
]);
$page->update('new_user@example.com', $user->username, 'qwerty');
$I->see('Your account details have been updated');
$I->seeRecord(User::class, [
    'id'    => 1,
    'email' => 'new_user@example.com',
    'unconfirmedEmail' => null,
]);
$I->amGoingTo('change username and password');
$page->update('new_user@example.com', 'nickname', 'qwerty', '123654');
$I->see('Your account details have been updated');
$I->seeRecord(User::class, [
    'username' => 'nickname',
    'email'    => 'new_user@example.com',
]);

Yii::$app->user->logout();

$I->amGoingTo('login with new credentials');
$page = LoginPage::openBy($I);
$page->login('nickname', '123654');
$I->see('Logout');

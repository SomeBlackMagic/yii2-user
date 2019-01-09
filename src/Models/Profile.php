<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SomeBlackMagic\Yii2User\Models;

use DateTime;
use DateTimeZone;
use SomeBlackMagic\Yii2User\Traits\ModuleTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property integer $userId
 * @property string  $name
 * @property string  $publicEmail
 * @property string  $gravatarEmail
 * @property string  $gravatarId
 * @property string  $location
 * @property string  $website
 * @property string  $bio
 * @property string  $timezone
 * @property User    $user
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com
 */
class Profile extends ActiveRecord
{
    use ModuleTrait;
    /** @var \dektrium\user\Module */
    protected $module;

    /** @inheritdoc */
    public function init()
    {
        $this->module = \Yii::$app->getModule('user');
    }

    /**
     * Returns avatar url or null if avatar is not set.
     * @param  int $size
     * @return string|null
     */
    public function getAvatarUrl($size = 200)
    {
        return '//gravatar.com/avatar/' . $this->gravatarId . '?s=' . $size;
    }

    /**
     * @return \yii\db\ActiveQueryInterface
     */
    public function getUser()
    {
        return $this->hasOne($this->module->modelMap['User'], ['id' => 'userId']);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            'bioString'            => ['bio', 'string'],
            'timeZoneValidation'   => ['timezone', 'validateTimeZone'],
            'publicEmailPattern'   => ['publicEmail', 'email'],
            'gravatarEmailPattern' => ['gravatarEmail', 'email'],
            'websiteUrl'           => ['website', 'url'],
            'nameLength'           => ['name', 'string', 'max' => 255],
            'publicEmailLength'    => ['publicEmail', 'string', 'max' => 255],
            'gravatarEmailLength'  => ['gravatarEmail', 'string', 'max' => 255],
            'locationLength'       => ['location', 'string', 'max' => 255],
            'websiteLength'        => ['website', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name'           => \Yii::t('user', 'Name'),
            'publicEmail'   => \Yii::t('user', 'Email (public)'),
            'gravatarEmail' => \Yii::t('user', 'Gravatar email'),
            'location'       => \Yii::t('user', 'Location'),
            'website'        => \Yii::t('user', 'Website'),
            'bio'            => \Yii::t('user', 'Bio'),
            'timezone'       => \Yii::t('user', 'Time zone'),
        ];
    }

    /**
     * Validates the timezone attribute.
     * Adds an error when the specified time zone doesn't exist.
     * @param string $attribute the attribute being validated
     * @param array $params values for the placeholders in the error message
     */
    public function validateTimeZone($attribute, $params)
    {
        if (!in_array($this->$attribute, timezone_identifiers_list())) {
            $this->addError($attribute, \Yii::t('user', 'Time zone is not valid'));
        }
    }

    /**
     * Get the user's time zone.
     * Defaults to the application timezone if not specified by the user.
     * @return DateTimeZone
     */
    public function getTimeZone(): DateTimeZone
    {
        try {
            return new DateTimeZone($this->timezone);
        } catch (\Exception $e) {
            // Default to application time zone if the user hasn't set their time zone
            return new DateTimeZone(\Yii::$app->timeZone);
        }
    }

    /**
     * Set the user's time zone.
     * @param DateTimeZone $timeZone
     */
    public function setTimeZone(DateTimeZone $timeZone): void
    {
        $this->setAttribute('timezone', $timeZone->getName());
    }

    /**
     * Converts DateTime to user's local time
     * @param DateTime|null $dateTime
     * @return DateTime
     * @throws \Exception
     */
    public function toLocalTime(DateTime $dateTime = null)
    {
        if ($dateTime === null) {
            $dateTime = new DateTime();
        }

        return $dateTime->setTimezone($this->getTimeZone());
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('gravatarEmail')) {
            $this->setAttribute('gravatarId', md5(strtolower(trim($this->getAttribute('gravatarEmail')))));
        }

        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }
}

<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SomeBlackMagic\Yii2User;

use Yii;
use yii\authclient\Collection;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\console\Application as ConsoleApplication;
use yii\i18n\PhpMessageSource;

/**
 * Bootstrap class registers module and user application component. It also creates some url rules which will be applied
 * when UrlManager.enablePrettyUrl is enabled.
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class Bootstrap implements BootstrapInterface
{
    /** @var array Model's map */
    private $_modelMap = [
        'User'             => Models\User::class,
        'Account'          => Models\Account::class,
        'Profile'          => Models\Profile::class,
        'Token'            => Models\Token::class,
        'RegistrationForm' => Models\RegistrationForm::class,
        'ResendForm'       => Models\ResendForm::class,
        'LoginForm'        => Models\LoginForm::class,
        'SettingsForm'     => Models\SettingsForm::class,
        'RecoveryForm'     => Models\RecoveryForm::class,
        'UserSearch'       => Models\UserSearch::class,
    ];


    /**
     * @param Application $app
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\di\NotInstantiableException
     */
    public function bootstrap($app)
    {
        /** @var Module $module */
        /** @var \yii\db\ActiveRecord $modelName */
        $module = $app->getModule('user');
        if ($app->hasModule('user') && $module instanceof Module) {
            $this->_modelMap = array_merge($this->_modelMap, $module->modelMap);
            $this->processModelMap($module);

            Yii::$container->setSingleton(Finder::class, [
                'userQuery'    => Yii::$container->get('UserQuery'),
                'profileQuery' => Yii::$container->get('ProfileQuery'),
                'tokenQuery'   => Yii::$container->get('TokenQuery'),
                'accountQuery' => Yii::$container->get('AccountQuery'),
            ]);

            if ($app instanceof ConsoleApplication) {
                $module->controllerNamespace = 'SomeBlackMagic\Yii2User\Commands';
            } else {
                $module->controllerNamespace = 'SomeBlackMagic\Yii2User\Controllers';
                Yii::$container->set(yii\web\User::class, [
                    'enableAutoLogin' => true,
                    'loginUrl'        => ['/user/security/login'],
                    'identityClass'   => $module->modelMap['User'],
                ]);

                $configUrlRule = [
                    'prefix' => $module->urlPrefix,
                    'rules'  => $module->urlRules,
                ];

                if ($module->urlPrefix != 'user') {
                    $configUrlRule['routePrefix'] = 'user';
                }

                $configUrlRule['class'] = yii\web\GroupUrlRule::class;
                $rule = Yii::createObject($configUrlRule);

                $app->urlManager->addRules([$rule], false);

                if (!$app->has('authClientCollection')) {
                    $app->set('authClientCollection', [
                        'class' => Collection::class,
                    ]);
                }
            }

            Yii::$container->set(Mailer::class, $module->mailer);

            $module->debug = Yii::$app->getModule('user')->debug;
        }
    }


    /**
     *
     */
    protected function setTranslation(Application $app)
    {
        if (!isset($app->get('i18n')->translations['user*'])) {
            $app->get('i18n')->translations['user*'] = [
                'class'          => PhpMessageSource::class,
                'basePath'       => __DIR__ . '/Messages',
                'sourceLanguage' => 'en-US'
            ];
        }
    }

    /**
     * @param Module $module
     */
    protected function processModelMap(Module $module)
    {
        foreach ($this->_modelMap as $name => $definition) {
            $class = "SomeBlackMagic\\Yii2User\\Models\\" . $name;
            Yii::$container->set($class, $definition);
            $modelName = is_array($definition) ? $definition['class'] : $definition;
            $module->modelMap[$name] = $modelName;
            if (in_array($name, ['User', 'Profile', 'Token', 'Account'])) {
                Yii::$container->set($name . 'Query', function () use ($modelName) {
                    return $modelName::find();
                });
            }
        }
    }


}

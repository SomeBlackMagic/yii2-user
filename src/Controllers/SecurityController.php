<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SomeBlackMagic\Yii2User\Controllers;

use SomeBlackMagic\Yii2User\Finder;
use SomeBlackMagic\Yii2User\Models\Account;
use SomeBlackMagic\Yii2User\Models\LoginForm;
use SomeBlackMagic\Yii2User\Models\User;
use SomeBlackMagic\Yii2User\Module;
use SomeBlackMagic\Yii2User\Traits\AjaxValidationTrait;
use SomeBlackMagic\Yii2User\Traits\EventTrait;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;

/**
 * Controller that manages user authentication process.
 *
 * @property Module $module
 *
 * @author Dmitry Erofeev <dmeroff@gmail.com>
 */
class SecurityController extends Controller
{
    use AjaxValidationTrait;
    use EventTrait;

    /**
     * Event is triggered before logging user in.
     * Triggered with \SomeBlackMagic\Yii2User\Events\FormEvent.
     */
    const EVENT_BEFORE_LOGIN = 'beforeLogin';

    /**
     * Event is triggered after logging user in.
     * Triggered with \SomeBlackMagic\Yii2User\Events\FormEvent.
     */
    const EVENT_AFTER_LOGIN = 'afterLogin';

    /**
     * Event is triggered before logging user out.
     * Triggered with \SomeBlackMagic\Yii2User\Events\UserEvent.
     */
    const EVENT_BEFORE_LOGOUT = 'beforeLogout';

    /**
     * Event is triggered after logging user out.
     * Triggered with \SomeBlackMagic\Yii2User\Events\UserEvent.
     */
    const EVENT_AFTER_LOGOUT = 'afterLogout';

    /**
     * Event is triggered before authenticating user via social network.
     * Triggered with \SomeBlackMagic\Yii2User\Events\AuthEvent.
     */
    const EVENT_BEFORE_AUTHENTICATE = 'beforeAuthenticate';

    /**
     * Event is triggered after authenticating user via social network.
     * Triggered with \SomeBlackMagic\Yii2User\Events\AuthEvent.
     */
    const EVENT_AFTER_AUTHENTICATE = 'afterAuthenticate';

    /**
     * Event is triggered before connecting social network account to user.
     * Triggered with \SomeBlackMagic\Yii2User\Events\AuthEvent.
     */
    const EVENT_BEFORE_CONNECT = 'beforeConnect';

    /**
     * Event is triggered before connecting social network account to user.
     * Triggered with \SomeBlackMagic\Yii2User\Events\AuthEvent.
     */
    const EVENT_AFTER_CONNECT = 'afterConnect';


    /** @var Finder */
    protected $finder;

    /**
     * @param string $id
     * @param Module $module
     * @param Finder $finder
     * @param array  $config
     */
    public function __construct($id, $module, Finder $finder, $config = [])
    {
        $this->finder = $finder;
        parent::__construct($id, $module, $config);
    }

    /** @inheritdoc */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    ['allow' => true, 'actions' => ['login', 'auth'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['login', 'auth', 'logout'], 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /** @inheritdoc */
    public function actions()
    {
        return [
            'auth' => [
                'class' => AuthAction::class,
                // if user is not logged in, will try to log him in, otherwise
                // will try to connect social account to user.
                'successCallback' => \Yii::$app->user->isGuest
                    ? [$this, 'authenticate']
                    : [$this, 'connect'],
            ],
        ];
    }

    /**
     * Displays the login page.
     *
     * @return string|Response
     * @throws \yii\base\InvalidConfigException
     */
    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            $this->goHome();
        }

        /** @var LoginForm $model */
        $model = \Yii::$container->get(LoginForm::class);
        $event = $this->getFormEvent($model);

        $this->performAjaxValidation($model);

        $this->trigger(self::EVENT_BEFORE_LOGIN, $event);

        if ($model->load(\Yii::$app->getRequest()->post()) && $model->login()) {
            $this->trigger(self::EVENT_AFTER_LOGIN, $event);
            return $this->goBack();
        }

        return $this->render('login', [
            'model'  => $model,
            'module' => $this->module,
        ]);
    }

    /**
     * Logs the user out and then redirects to the homepage.
     *
     * @return Response
     */
    public function actionLogout()
    {
        $event = $this->getUserEvent(\Yii::$app->user->identity);

        $this->trigger(self::EVENT_BEFORE_LOGOUT, $event);

        \Yii::$app->getUser()->logout();

        $this->trigger(self::EVENT_AFTER_LOGOUT, $event);

        return $this->goHome();
    }

    /**
     * Tries to authenticate user via social network. If user has already used
     * this network's account, he will be logged in. Otherwise, it will try
     * to create new user account.
     *
     * @param ClientInterface $client
     * @throws \yii\base\InvalidConfigException
     */
    public function authenticate(ClientInterface $client)
    {
        $account = $this->finder->findAccount()->byClient($client)->one();

        if (!$this->module->enableRegistration && ($account === null || $account->user === null)) {
            \Yii::$app->session->setFlash('danger', \Yii::t('user', 'Registration on this website is disabled'));
            $this->action->successUrl = Url::to(['/user/security/login']);
            return;
        }

        if ($account === null) {
            /** @var Account $account */
            $accountObj = \Yii::createObject(Account::class);
            $account = $accountObj::create($client);
        }

        $event = $this->getAuthEvent($account, $client);

        $this->trigger(self::EVENT_BEFORE_AUTHENTICATE, $event);

        if ($account->user instanceof User) {
            if ($account->user->isBlocked) {
                \Yii::$app->session->setFlash('danger', \Yii::t('user', 'Your account has been blocked.'));
                $this->action->successUrl = Url::to(['/user/security/login']);
            } else {
                $account->user->updateAttributes(['lastLoginAt' => time()]);
                \Yii::$app->user->login($account->user, $this->module->rememberFor);
                $this->action->successUrl = \Yii::$app->getUser()->getReturnUrl();
            }
        } else {
            $this->action->successUrl = $account->getConnectUrl();
        }

        $this->trigger(self::EVENT_AFTER_AUTHENTICATE, $event);
    }

    /**
     * Tries to connect social account to user.
     *
     * @param ClientInterface $client
     * @throws \yii\base\InvalidConfigException
     */
    public function connect(ClientInterface $client)
    {
        /** @var Account $account */
        $account = \Yii::createObject(Account::class);
        $event   = $this->getAuthEvent($account, $client);

        $this->trigger(self::EVENT_BEFORE_CONNECT, $event);

        $account->connectWithUser($client);

        $this->trigger(self::EVENT_AFTER_CONNECT, $event);

        $this->action->successUrl = Url::to(['/user/settings/networks']);
    }
}
<?php

namespace mdm\admin\controllers;

use Yii;
use yii\web\Controller;
use mdm\admin\models\LoginForm;
use mdm\admin\models\SignupForm;
use mdm\admin\models\ChangePasswordForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * Site controller
 */
class UserController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'change-password'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $parent = $this->module;
        $modules = [];
        foreach ($parent->modules as $id => $module) {
            $module = $parent->getModule($id);
            $class = new \ReflectionClass($module);
            $comment = strtr(trim(preg_replace('/^\s*\**( |\t)?/m', '', trim($class->getDocComment(), '/'))), "\r", '');
            if (preg_match('/^\s*@\w+/m', $comment, $matches, PREG_OFFSET_CAPTURE)) {
                $comment = trim(substr($comment, 0, $matches[0][1]));
            }
            $modules[$module->uniqueId] = [
                'name' => \yii\helpers\Inflector::camel2words($module->id),
                'comment' => $comment
            ];
        }

        return $this->render('index', ['modules' => $modules]);
    }

    /**
     * Login
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            //return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                    'model' => $model,
            ]);
        }
    }

    /**
     * Logout
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signup
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
                'model' => $model,
        ]);
    }

    /**
     * Change password
     * @return type
     */
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm();
        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            
        }

        return $this->render('change-password',[
            'model'=>$model,
        ]);
    }
}
<?php
namespace quarsintex\yii2\quartronic\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use common\models\LoginForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'update'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function beforeAction($action)
    {
        if ($action->id == 'index') $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($url = '')
    {
      $route = !Yii::$app->urlManager->enablePrettyUrl && isset($_GET['r']) ? $_GET['r'] : $url;
      if (trim($route,'/') == 'update') {
          $this->actionUpdate();
      }
      Yii::$app->quartronic;
      $user = new \quarsintex\quartronic\qmodels\QUser();
      if (!empty(Yii::$app->user->identity)) {
          $user->username = Yii::$app->user->identity->username;
          Yii::$app->quartronic->defineUser($user);
      }
      return Yii::$app->quartronic->run([
          'route'=>$route,
          'returnRender'=>true,
          'webPath'=>Yii::$app->quartronic->webPath,
          'webDir'=>Yii::$app->quartronic->webDir,
          'requireAuth'=>false,
      ]);
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionUpdate() {
        \quarsintex\quartronic\qcore\QUpdater::run();
    }
}

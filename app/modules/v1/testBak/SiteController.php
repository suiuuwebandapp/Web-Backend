<?php
namespace app\modules\v1\controllers;

use backend\components\Page;
use frontend\services\UserMessageRemindService;
use Yii;
use yii\rest\ActiveController;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\data\ActiveDataProvider;

class SiteController extends ActiveController
{

    public $modelClass = 'app\modules\v1\models\guide';

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items'
    ];


    public function actions()
    {
        $actions = parent::actions();
        // 注销系统自带的实现方法
        unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view']);
        return $actions;
    }

    public function actionIndex()
    {

        $modelClass = $this->modelClass;
        $query = $modelClass::findBySql("select * from user_base a LEFT join user_photo b ON a.userSign=b.userId ");
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    public function actionCreate()
    {
        $model = new $this->modelClass();
        // $model->load(Yii::$app->getRequest()
        // ->getBodyParams(), '');
        $model->attributes = Yii::$app->request->post();
        if (! $model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->attributes = Yii::$app->request->post();
        if (! $model->save()) {
            return array_values($model->getFirstErrors())[0];
        }
        return $model;
    }

    public function actionDelete($id)
    {
        return $this->findModel($id)->delete();
    }

    public function actionView($id)
    {
        return $this->findModel($id);
    }

    protected function findModel($id)
    {
        $modelClass = $this->modelClass;
        if (($model = $modelClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        // 检查用户能否访问 $action 和 $model
        // 访问被拒绝应抛出ForbiddenHttpException
         var_dump($action);exit;
    }
}
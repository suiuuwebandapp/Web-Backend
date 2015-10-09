<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/30
 * Time : 14:57
 * Email: zhangxinmailvip@foxmail.com
 */

namespace console\controllers;


use backend\services\UserBaseService;
use common\components\Code;
use yii\console\Controller;

class UserController extends Controller{


    public function actionUpdateSysUser()
    {
        $userBaseService=new UserBaseService();
        $allUserSign=[];
        $list=$userBaseService->getSysUserList();
        if(!empty($list)){
            foreach($list as $temp){
                $allUserSign[]=$temp;
            }
        }

        \Yii::$app->redis->set(Code::SYS_ALL_USER_SIGN_CHAT,json_encode($allUserSign));
        echo ('成功设置小号用户:'.count($allUserSign));exit;
    }
}
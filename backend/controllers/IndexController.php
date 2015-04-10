<?php
namespace backend\controllers;

use backend\entity\SysUser;
use backend\services\SysUserService;
use Yii;

class IndexController extends CController
{

    private $sysUserService;

    public $layout='main';//只有Index 默认main使用layout

    public function __construct($id, $module = null)
    {
        $this->sysUserService = new SysUserService();
        parent::__construct($id, $module);
    }

    public function actionIndex()
    {
        return $this->render('index', ['userObj' => $this->userObj]);
    }

    public function actionInfo()
    {
        return $this->renderPartial('info');
    }



    public function actionDashboard()
    {
        return $this->renderPartial('dashboard');
    }


    public function actionAdd()
    {
        $sysUser = new SysUser();
        $sysUser->username = "admin";
        $sysUser->password = "password";
        $sysUser->phone = "17701085674";
        $sysUser->email = "xin.zhang@suiuu.com";
        $sysUser->nickname = "张鑫";
        $sysUser->lastLoginIp = "127.0.0.1";
        $sysUser->registerIp = "127.0.0.1";
        $sysUser->sex = 1;
        $sysUser->isEnabled = true;
        $sysUser->isAdmin = true;

        $this->sysUserService->addSysUser($sysUser);

    }

}

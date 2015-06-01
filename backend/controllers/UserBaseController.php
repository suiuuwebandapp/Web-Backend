<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/29
 * Time : 下午6:03
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\UserBaseService;
use common\components\Code;
use common\entity\UserBase;
use common\entity\UserPublisher;
use yii\base\Exception;

class UserBaseController extends CController{


    private $userBaserService;

    public function __construct($id,$module)
    {
        $this->userBaserService=new UserBaseService();
        parent::__construct($id, $module);

    }

    /**
     * 添加系统随游
     * @return string
     */
    public function actionAddSysPublisher()
    {
        $nickname=trim(\Yii::$app->request->post("nickname",""));
        $phone=trim(\Yii::$app->request->post("phone",null));
        $email=trim(\Yii::$app->request->post("email",null));

        if(empty($nickname)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"随友昵称不能为空"));
        }
        if(empty($phone)&&empty($email)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"手机邮箱不能同时为空"));
        }
        if(empty($phone)){
            $phone=null;
        }
        if(empty($email)){
            $email=null;
        }

        $userBase=new UserBase();
        $userBase->nickname=$nickname;
        $userBase->phone=$phone;
        $userBase->email=$email;
        $userBase->password="suiuu";

        $userPublisher=new UserPublisher();


        $userBaseService=new \frontend\services\UserBaseService();
        try{
            $userBaseService->addUser($userBase,null,$userPublisher);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }
    }



    public function actionUserList()
    {
        $search=\Yii::$app->request->get("searchText","");

        $page=new Page(\Yii::$app->request);

        $page=$this->userBaserService->getUserBaseListByPage($page,$search);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }


    public function actionToUserList()
    {
        return $this->render("userList");
    }


    public function actionToAddPublisher()
    {
        return $this->render("addPublisher");
    }
}
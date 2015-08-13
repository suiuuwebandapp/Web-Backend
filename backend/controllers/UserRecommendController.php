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
use common\components\Common;
use common\entity\UserBase;
use common\entity\UserPublisher;
use common\entity\UserRecommend;
use common\entity\WeChat;
use common\entity\WeChatUserInfo;
use yii\base\Exception;

class UserRecommendController extends CController{


    private $userBaserService;

    public function __construct($id,$module)
    {
        $this->userBaserService=new UserBaseService();
        parent::__construct($id, $module);

    }



    /**
     * 用户列表(AJAX)
     * @throws Exception
     * @throws \Exception
     */
    public function actionUserList()
    {
        $search=\Yii::$app->request->get("searchText","");

        $page=new Page(\Yii::$app->request);

        $page=$this->userBaserService->getUserRecommendListByPage($page,$search);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }


    /**
     * 跳转到用户列表
     * @return string
     */
    public function actionToList()
    {
        return $this->render("userList");
    }

    public function actionAddUserRecommend()
    {
        $userId=\Yii::$app->request->post("id");
        if(empty($userId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无效的用户'));
        }
        $userRecommend=new UserRecommend();
        $userRecommend->userId=$userId;
        $this->userBaserService->addUserRecommend($userRecommend);

        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }

    public function actionDeleteUserRecommend()
    {
        $userRecommendId=\Yii::$app->request->post("id");
        if(empty($userRecommendId)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无效的用户'));
        }
        $this->userBaserService->deleteUserRecommend($userRecommendId);

        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }
}
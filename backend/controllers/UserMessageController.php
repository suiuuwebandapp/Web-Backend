<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/28
 * Time : 16:20
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;



use backend\components\Page;
use backend\services\UserMessageService;
use common\components\Code;

class UserMessageController extends CController{


    public $userMessageService;

    public function actionToList()
    {
        return $this->render('messageList');
    }


    public function actionMessageSessionList()
    {
        $keywords=\Yii::$app->request->post('keywords','');

        $page=new Page();
        $page->currentPage=1;
        $page->pageSize=1000;

        $this->userMessageService=new UserMessageService();
        $page=$this->userMessageService->getSysMessageSessionList($page,$keywords);

        return json_encode(Code::statusDataReturn(Code::SUCCESS,$page->getList()));

    }



    public function actionMessageList()
    {
        $sessionKey=\Yii::$app->request->post('sessionKey','');
        $userId=\Yii::$app->request->post('userId','');
        $read=\Yii::$app->request->post('read',1);

        $this->userMessageService=new UserMessageService();
        $list=$this->userMessageService->getUserMessageList($userId,$sessionKey,$read);

        return json_encode(Code::statusDataReturn(Code::SUCCESS,$list));

    }
}
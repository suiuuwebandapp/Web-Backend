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
use backend\services\WechatService;
use common\components\Code;
use common\components\Common;
use common\entity\UserBase;
use common\entity\UserPublisher;
use common\entity\WeChat;
use common\entity\WeChatUserInfo;
use yii\base\Exception;

class UserBaseController extends CController{


    private $userBaserService;
    private $wechatService;

    public function __construct($id,$module)
    {
        $this->userBaserService=new UserBaseService();
        $this->wechatService=new WechatService();
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


    /**
     * 用户列表(AJAX)
     * @throws Exception
     * @throws \Exception
     */
    public function actionUserList()
    {
        $search=\Yii::$app->request->get("searchText","");

        $page=new Page(\Yii::$app->request);

        $page=$this->userBaserService->getUserBaseListByPage($page,$search);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }


    /**
     * 跳转到用户列表
     * @return string
     */
    public function actionToUserList()
    {
        return $this->render("userList");
    }

    /**
     * 跳转到微信用户列表
     * @return string
     */
    public function actionToWechatUserList()
    {
        return $this->render("wechatUserList");
    }
    /**
     * 微信用户列表(AJAX)
     * @throws Exception
     * @throws \Exception
     */
    public function actionWechatUserList()
    {
        $search=\Yii::$app->request->get("searchText","");

        $page=new Page(\Yii::$app->request);

        $page= $this->wechatService->getWechatUserBaseList($page,$search);

        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());

        echo json_encode($tableResult);
    }

    public function actionUpdateWechatUser()
    {
        $openId= \Yii::$app->request->post('openId');
        $access_token = \Yii::$app->redis->get(WeChat::TOKEN_FILE_NAME);
        $access_token = 'uexkDUMSNLuDRtH7tOXB75lCjR2YuE3_tyM9h9WdCW_PUITLFHerc4us1OkNTtcYH-Pzth8Pf7ZLuJMRQOvCK23Psg7m_LDa3QB3oYoloUk';
        if(empty($access_token))
        {
            return json_encode(Code::statusDataReturn(Code::FAIL,'token 已经过期'));
        }
        $url = WeChat::GET_USER_INFO . $access_token . "&openid=" . $openId . "&lang=zh_CN";

        $rst =  Common::CurlHandel($url);
        if ($rst['status'] == Code::SUCCESS) {
            $rstJson = json_decode($rst['data'],true);
            if (isset($rstJson['nickname'])) {
                $weChatUserInfo=new WeChatUserInfo();
                $weChatUserInfo->openId=$openId;
                $WeChatRst = $this->wechatService->getUserInfo($weChatUserInfo);
                $this->wechatService->upDateWeChatInfo($rstJson,$WeChatRst['userSign']);
                return json_encode(Code::statusDataReturn(Code::SUCCESS,'更新成功'));
            } else {
                return json_encode(Code::statusDataReturn(Code::FAIL,'未知的用户信息'));
            }
        }
        return Code::statusDataReturn(Code::FAIL);
    }
    /**
     * 用户详情
     * @return string
     */
    public function actionToUserInfo()
    {
        $userSign=\Yii::$app->request->get("id");
        $userInfo=$this->userBaserService->findUserInfoByUserSign($userSign);
        return $this->render("userInfo",[
            'userInfo'=>$userInfo
        ]);
    }


    public function actionToAddPublisher()
    {
        return $this->render("addPublisher");
    }
}
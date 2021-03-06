<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/9
 * Time : 下午2:13
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\interfaces;

use common\components\Code;
use yii\base\Exception;

require_once('../../vendor/access/API/qqConnectAPI.php');

class TencentInterface {

    private $QC;

    public function __construct()
    {
        $this->QC=new \QC();
    }


    public function getAccessToken()
    {

    }

    /**
     * * 调用此方法前 确定有可用的调用过有效的Token
     * 获取腾讯的OpenID
     *
     * @param $tokenId
     * @return array
     */
    public function getOpenId($tokenId)
    {
        try{
            $openId=$this->QC->get_openid($tokenId);
            return Code::statusDataReturn(Code::SUCCESS,$openId);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }

    /**
     * 获取用户基本信息
     * @param $tokenId
     * @param $openId
     * @return array
     */
    public function getUserInfo($tokenId,$openId)
    {
        try{
            $this->QC->setToken($tokenId);
            $this->QC->setOpenId($openId);
            $userInfo=$this->QC->get_user_info();
            return Code::statusDataReturn(Code::SUCCESS,$userInfo);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }

    /**
     * 跳转到QQ接入页面
     */
    public function toConnectQQ(){
        $this->QC->qq_login();
    }


    /**
     * 回调函数获取Token
     * @param $csrf
     * @param $code
     * @return array
     */
    public function callBackGetToken($csrf,$code){

        try{
            $token=$this->QC->qq_callback($csrf,$code);
            return Code::statusDataReturn(Code::SUCCESS,$token);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e->getMessage());
        }
    }



}
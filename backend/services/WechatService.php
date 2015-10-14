<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/6/16
 * Time: 上午11:22
 */

namespace backend\services;


use backend\components\Page;
use common\components\Code;
use common\entity\WeChatUserInfo;
use common\models\BaseDb;
use common\models\WeChatDb;
use yii\base\Exception;

class WechatService extends BaseDb{


    /**
     * @param Page $page
     * @param $search
     * @param $school
     * @return Page|null
     * @throws Exception
     * @throws \Exception
     */
    public function getWechatUserBaseList(Page $page,$search,$school=null)
    {
        try{
            $conn=$this->getConnection();
            $wechatDb=new WeChatDb($conn);
            $page=$wechatDb->getWeChatOrderList($page,$search,$school);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
        return $page;
    }

    public function getUserInfo(WeChatUserInfo $weChatUserInfo)
    {
        try {
            $conn = $this->getConnection();
            $wechatDb=new WeChatDb($conn);
            return $wechatDb->findWeChatUserInfo($weChatUserInfo);
        } catch (Exception $e) {
            throw new Exception('得到用户信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 更新用户数据
     * @param $arr
     * @param $userSign
     * @return int
     * @throws Exception
     */
    public function upDateWeChatInfo($arr,$userSign)
    {
        try {
            $weChatUserInfoOld =new WeChatUserInfo();
            $weChatUserInfoOld->userSign=$userSign;
            $weChatUserInfo=$this->arr2WeChatUserInfo($arr,$weChatUserInfoOld);
            if($weChatUserInfo->openId=='')
            {
                throw new Exception('更新用户信息异常', Code::FAIL);
            }
            $conn = $this->getConnection();
            $wechatDb=new WeChatDb($conn);
            return $wechatDb->updateWeChatUserInfo($weChatUserInfo);
        } catch (Exception $e) {
            throw new Exception('更新用户信息异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    private function arr2WeChatUserInfo($arr,WeChatUserInfo $weChatUserInfoOld=null)
    {

        if(empty($weChatUserInfoOld))
        {
            $weChatUserInfo =new WeChatUserInfo();
        }else
        {
            $weChatUserInfo =$weChatUserInfoOld;
        }
        $weChatUserInfo->openId=isset($arr['openid'])?$arr['openid']:'';
        $weChatUserInfo->v_nickname=isset($arr['nickname'])?($arr['nickname']):'';
        $weChatUserInfo->v_sex=isset($arr['sex'])?$arr['sex']:0;
        $weChatUserInfo->v_language=isset($arr['language'])?$arr['language']:'';
        $weChatUserInfo->v_city=isset($arr['city'])?$arr['city']:'';
        $weChatUserInfo->v_province=isset($arr['province'])?$arr['province']:'';
        $weChatUserInfo->v_country=isset($arr['country'])?$arr['country']:'';
        $weChatUserInfo->v_headimgurl=isset($arr['headimgurl'])?$arr['headimgurl']:'';
        $weChatUserInfo->v_subscribe_time=isset($arr['subscribe_time'])?$arr['subscribe_time']:0;
        $weChatUserInfo->unionID=isset($arr['unionid'])?$arr['unionid']:'';
        $weChatUserInfo->v_remark=isset($arr['remark'])?$arr['remark']:'';
        $weChatUserInfo->v_groupid=isset($arr['groupid'])?$arr['groupid']:0;
        return $weChatUserInfo;
    }

    /*private function emojiDelete($str)
    {

        $tmpStr = json_encode($str); //暴露出unicode  \ue052
        $tmpStr = preg_replace("/\ue[0-9a-f]{3})/","",$tmpStr); //将emoji的unicode留下，其他不动
        $text = json_decode($tmpStr);
       return $text;
    }*/
}
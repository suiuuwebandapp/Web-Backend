<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/13
 * Time: 下午4:15
 */

namespace common\models;


use common\entity\WeChatUserInfo;
use yii\db\mssql\PDO;

class WeChatDb extends ProxyDb{

    public function addWeChatUserInfo(WeChatUserInfo $weChatUserInfo)
    {
        $sql = sprintf("
            INSERT INTO wechat_user_info
            (
             openId,userSign,unionID,v_nickname,v_sex,v_city,v_country,v_province,v_language,v_headimgurl,v_subscribe_time,v_remark,v_groupid,v_createTime
            )
            VALUES
            (
               :openId,:userSign,:unionID,:v_nickname,:v_sex,:v_city,:v_country,:v_province,:v_language,:v_headimgurl,:v_subscribe_time,:v_remark,:v_groupid,now()
            )
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":openId", $weChatUserInfo->openId, PDO::PARAM_STR);
        $command->bindParam(":userSign", $weChatUserInfo->userSign, PDO::PARAM_STR);
        $command->bindParam(":unionID", $weChatUserInfo->unionID, PDO::PARAM_STR);
        $command->bindParam(":v_nickname", $weChatUserInfo->v_nickname, PDO::PARAM_STR);
        $command->bindParam(":v_sex", $weChatUserInfo->v_sex, PDO::PARAM_INT);
        $command->bindParam(":v_city", $weChatUserInfo->v_city, PDO::PARAM_STR);
        $command->bindParam(":v_country", $weChatUserInfo->v_country, PDO::PARAM_STR);
        $command->bindParam(":v_province", $weChatUserInfo->v_province, PDO::PARAM_STR);
        $command->bindParam(":v_language", $weChatUserInfo->v_language, PDO::PARAM_STR);
        $command->bindParam(":v_headimgurl", $weChatUserInfo->v_headimgurl, PDO::PARAM_STR);
        $command->bindParam(":v_subscribe_time", $weChatUserInfo->v_subscribe_time, PDO::PARAM_INT);
        $command->bindParam(":v_remark", $weChatUserInfo->v_remark, PDO::PARAM_STR);
        $command->bindParam(":v_groupid", $weChatUserInfo->v_groupid, PDO::PARAM_INT);
        return $command->execute();
    }

    public function findWeChatUserInfo(WeChatUserInfo $weChatUserInfo){
        $sql = sprintf("
           SELECT a.openId,a.unionID,a.v_nickname,a.v_sex,a.v_headimgurl,b.nickname,b.phone,b.email,b.headImg,b.userSign,b.isPublisher,b.status FROM wechat_user_info a
           LEFT JOIN user_base b ON a.userSign=b.userSign
            WHERE 1=1
        ");
        if(!empty($weChatUserInfo->userSign))
        {
            $sql.=' AND a.userSign=:userSign';
        }
        if(!empty($weChatUserInfo->id))
        {
            $sql.=' AND id=:id';
        }
        if(!empty($weChatUserInfo->openId))
        {
            $sql.=' AND openId=:openId';
        }
        if(!empty($weChatUserInfo->unionID))
        {
            $sql.=' AND unionID=:unionID';
        }
        $command=$this->getConnection()->createCommand($sql);
        if(!empty($weChatUserInfo->userSign))
        {
            $command->bindParam(":userSign", $weChatUserInfo->userSign, PDO::PARAM_STR);
        }
        if(!empty($weChatUserInfo->id))
        {
            $command->bindParam(":id", $weChatUserInfo->id, PDO::PARAM_INT);
        }
        if(!empty($weChatUserInfo->openId))
        {
            $command->bindParam(":openId", $weChatUserInfo->openId, PDO::PARAM_STR);
        }
        if(!empty($weChatUserInfo->unionID))
        {
            $command->bindParam(":unionID", $weChatUserInfo->unionID, PDO::PARAM_STR);
        }
        return $command->queryOne();
    }


    public function updateWeChatUserInfo(WeChatUserInfo $weChatUserInfo){
        $sql = sprintf("
            UPDATE wechat_user_info SET
            v_nickname=:v_nickname,v_sex=:v_sex,v_city=:v_city,v_country=:v_country,
            v_province=:v_province,v_language=:v_language,v_headimgurl=:v_headimgurl,v_subscribe_time=:v_subscribe_time,v_remark=:v_remark,v_groupid=:v_groupid
            WHERE openId=:openId
        ");
        //userSign=:userSign,unionID=:unionID,
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":openId", $weChatUserInfo->openId, PDO::PARAM_STR);
        //$command->bindParam(":userSign", $weChatUserInfo->userSign, PDO::PARAM_STR);
        //$command->bindParam(":unionID", $weChatUserInfo->unionID, PDO::PARAM_STR);
        $command->bindParam(":v_nickname", $weChatUserInfo->v_nickname, PDO::PARAM_STR);
        $command->bindParam(":v_sex", $weChatUserInfo->v_sex, PDO::PARAM_INT);
        $command->bindParam(":v_city", $weChatUserInfo->v_city, PDO::PARAM_STR);
        $command->bindParam(":v_country", $weChatUserInfo->v_country, PDO::PARAM_STR);
        $command->bindParam(":v_province", $weChatUserInfo->v_province, PDO::PARAM_STR);
        $command->bindParam(":v_language", $weChatUserInfo->v_language, PDO::PARAM_STR);
        $command->bindParam(":v_headimgurl", $weChatUserInfo->v_headimgurl, PDO::PARAM_STR);
        $command->bindParam(":v_subscribe_time", $weChatUserInfo->v_subscribe_time, PDO::PARAM_INT);
        $command->bindParam(":v_remark", $weChatUserInfo->v_remark, PDO::PARAM_STR);
        $command->bindParam(":v_groupid", $weChatUserInfo->v_groupid, PDO::PARAM_INT);
        return $command->execute();
    }

    public function bindingWeChatByUnionID(WeChatUserInfo $weChatUserInfo)
    {
        $sql = sprintf("
            UPDATE wechat_user_info SET
           userSign=:userSign
            WHERE unionID=:unionID
        ");
        $command=$this->getConnection()->createCommand($sql);
        $command->bindParam(":userSign", $weChatUserInfo->userSign, PDO::PARAM_STR);
        $command->bindParam(":unionID", $weChatUserInfo->unionID, PDO::PARAM_STR);
        return $command->execute();
    }

    public function getWeChatOrderList($page,$searchText)
    {
        $sql=sprintf("
        FROM wechat_user_info a
        LEFT JOIN user_base b ON a.userSign=b.userSign
         WHERE 1=1
        ");
        if(!empty($searchText)){
            $sql.=" AND (b.nickname like :search OR a.v_nickname like :search OR a.openId like :search ) ";
            $this->setParam("search","%".$searchText."%");
        }
        $this->setSelectInfo('a.*,b.nickname');
        $this->setSql($sql);
        return $this->find($page);
    }


}
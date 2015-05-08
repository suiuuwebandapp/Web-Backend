<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/30
 * Time : 下午9:09
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\services;


use backend\components\Page;
use common\entity\DestinationInfo;
use common\models\BaseDb;
use common\models\DestinationDb;
use yii\base\Exception;

class DestinationService extends BaseDb{


    private $destinationDb;


    public function getList(Page $page,$search,$countryId,$cityId)
    {

        try {
            $conn = $this->getConnection();
            $this->destinationDb=new DestinationDb($conn);
            $page=$this->destinationDb->getDesList($page,$search,DestinationInfo::DES_STATUS_ONLINE,$countryId,$cityId);
            return $page;
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }

    public function findInfoById($desId)
    {
        $desInfo=array();
        $desPage=new Page();
        $desPage->showAll=true;
        $desPage->sortName="beginTime";
        try {
            $conn = $this->getConnection();
            $this->destinationDb=new DestinationDb($conn);
            $info=$this->destinationDb->findDestinationById($desId);
            if($info['status']!=DestinationInfo::DES_STATUS_ONLINE){
                throw new Exception("无效的Id");
            }
            $desInfo['scenicList']=$this->destinationDb->getScenicList($desPage,$desId,null)->getList();
            $desInfo['info']=$info;

        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $desInfo;
    }


    /**
     * 获取目前存在的国家和目的地Id
     * @return mixed
     * @throws Exception
     * @throws \Exception
     */
    public function getDesCountryAndCity()
    {
        try {
            $conn = $this->getConnection();
            $this->destinationDb=new DestinationDb($conn);
            $cityList=$this->destinationDb->getDesCityIds();
            $countryList=$this->destinationDb->getDesCountryIds();
            $result['cityList']=$cityList;
            $result['countryList']=$countryList;
            return $result;
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


}
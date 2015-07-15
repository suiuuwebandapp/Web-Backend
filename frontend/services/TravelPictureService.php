<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/15
 * Time: 上午10:43
 */

namespace frontend\services;


use common\entity\TravelPicture;
use common\entity\TravelPictureComment;
use common\models\BaseDb;
use common\models\TravelPictureCommentDb;
use common\models\TravelPictureDb;
use yii\base\Exception;

class TravelPictureService  extends BaseDb {

    private $tpDb;
    private $tpcDb;
    public function addTravelPicture(TravelPicture $travelPicture)
    {
        $conn=$this->getConnection();
        try{
            $this->tpDb=new TravelPictureDb($conn);
            $tagSer = new TagListService();
            $qId = $this->tpDb->addTravelPicture($travelPicture);
            $tagSer->updateTpTagValList($travelPicture->tags,$qId);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }

    public function addTravelPictureComment(TravelPictureComment $travelPictureComment)
    {
        $conn=$this->getConnection();
        try{
            $this->tpcDb=new TravelPictureCommentDb($conn);
            $this->tpcDb->addTravelPictureComment($travelPictureComment);
        }catch (Exception $e){
            throw $e;
        }finally{
            $this->closeLink();
        }
    }
}
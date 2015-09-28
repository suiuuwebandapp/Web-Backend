<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/8/18
 * Time: 上午9:32
 */

namespace app\modules\v1\services;


use app\components\Page;
use app\modules\v1\entity\UserAttention;
use app\modules\v1\entity\UserPublisher;
use app\modules\v1\models\QaCommunityDb;
use app\modules\v1\models\TravelPictureDb;
use app\modules\v1\models\TravelTripCommentDb;
use app\modules\v1\models\TravelTripDb;
use app\modules\v1\models\UserAttentionDb;
use app\modules\v1\models\UserBaseDb;
use app\modules\v1\models\UserPublisherDb;
use common\components\Code;
use common\models\BaseDb;
use yii\base\Exception;

class UserInfoService  extends BaseDb
{

    private $userAttentionDb;
    private $userBaseDb;
    private $tripDb;
    public function __construct()
    {

    }
    public function getUserInfo($userSign)
    {
        try {
            $data = array();
            $conn = $this->getConnection();
            $this->userAttentionDb =new UserAttentionDb($conn);
            $this->userBaseDb =new UserBaseDb($conn);
            $userArr  = $this->userBaseDb->findByUserSign($userSign);
            if(empty($userArr)||$userArr==false)
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法得到未知用户主页'));
                exit;
            }
            $data['userInfo']=$userArr;

            $joinList=array();
            $tripList=array();
            if($userArr['isPublisher']){
                $this->tripDb = new TravelTripDb($conn);
                $userPublisherDb = new UserPublisherDb($conn);
                $result = $userPublisherDb->findUserPublisherByUserId($userSign);
                $UserPublisher = $this->arrayCastObject($result, UserPublisher::class);
                $publisherId = $UserPublisher->userPublisherId;
                $tripList = $this->tripDb->getMyTripList($publisherId);
                $joinList =  $this->tripDb->getMyJoinTripList($publisherId);
            }
            $data['tripList']=$tripList;
            $data['joinList']=$joinList;
            $commentPage = new Page();
            $TravelTripCommentDb = new TravelTripCommentDb($conn);
            $TripList=$TravelTripCommentDb->getCommentByUser($commentPage,$userSign);
            $data['commentNumb']=$TripList->totalCount;
            $data['commentInfo']=$TripList->getList();

            $userBaseService=new UserBaseService();
            //获取用户证件信息
            $userCard=$userBaseService->findUserCardByUserId($userSign);
            //获取用户资历信息
            $userAptitude=$userBaseService->findUserAptitudeByUserId($userSign);

            $data['userCard']=$userCard;
            $data['userAptitude']=$userAptitude;

            $tpPage=new Page();
            $tpDb = new TravelPictureDb($conn);
            $tpPage = $tpDb->getUserTp($tpPage,$userSign);
            $data['tpCount']=$tpPage->totalCount;

            $QaPage=new Page();
            $QaDb = new QaCommunityDb($conn);
            $QaPage = $QaDb->getUserQa($QaPage,$userSign);
            $data['QaCount']=$QaPage->totalCount;
            $attentionNumb = $this->userAttentionDb->getCount($userSign);
            $data['collectCount']="";
            if(isset($attentionNumb["numb"]))
            {
                $data['collectCount']=$attentionNumb["numb"];
            }
            return $data;
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/3
 * Time: 下午2:39
 */
namespace frontend\controllers;

use common\components\LogUtils;
use common\entity\TravelTripComment;
use common\entity\UserBase;
use frontend\components\Page;
use common\components\Code;
use common\components\TagUtil;
use frontend\services\CountryService;
use frontend\services\PublisherService;
use frontend\services\TravelTripCommentService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use yii\base\Exception;
use Yii;
class AppTravelController extends AController
{
    private $travelSer;
    private $tripCommentSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->travelSer=new TripService();
        $this->tripCommentSer=new TravelTripCommentService();
    }

    //得到随游列表 根据筛选条件
    public function actionGetTravelList()
    {
        $this->loginValid();
        try{
            $page=new Page(Yii::$app->request);
            //$page->showAll=true;
            $title=Yii::$app->request->post('title');
            $countryId=Yii::$app->request->post('countryId');
            $cityId=Yii::$app->request->post('cityId');
            $cc=Yii::$app->request->post('cc');
            if(!empty($cc))
            {
                $countrySer = new CountryService();
                $arr=$countrySer->getCC($cc);
                $countryId=$arr[0];
                $cityId=$arr[1];
            }
            $peopleCount=Yii::$app->request->post('peopleCount');
            $startPrice=Yii::$app->request->post('startPrice');
            $endPrice=Yii::$app->request->post('endPrice');
            $tag=Yii::$app->request->post('tag');
            $data=$this->travelSer->getList($page,$title,$countryId,$cityId,$peopleCount,$startPrice,$endPrice,$tag);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$data->getList(),$data));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    //得到国家城市
    public function actionGetCountry()
    {
        $this->loginValid();
        try{
            $countryService = new CountryService();
            $countryList = $countryService->getCountryList();
            //$tagList = TagUtil::getInstance()->getTagList();
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$countryList));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }
    //得到国家城市
    public function actionGetCity()
    {
        $this->loginValid();
        try{
            $countryId=Yii::$app->request->post('countryId');
            $cityName=Yii::$app->request->post('cityName');
            $countryService = new CountryService();
            $cityList = $countryService->getCityList($countryId,$cityName);
            //$tagList = TagUtil::getInstance()->getTagList();
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$cityList));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }
    //得到类型
    public function actionGetTagList()
    {
        $this->loginValid();
        try{
            $tagList = TagUtil::getInstance()->getTagList();
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$tagList));
        }catch (Exception $e){
            LogUtils::log($e);
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }
    //得到随游详情
    public function actionGetTravelInfo()
    {
        $this->loginValid();
        try{
            $userSign=$this->userObj->userSign;
            $trId=Yii::$app->request->post('trId');
            //$trId=70;
            $data=$this->travelSer->getTravelTripInfoById($trId,$userSign);
            $tripInfo=$data['info'];
            $userService=new UserBaseService();
            $publisherService=new PublisherService();
            $tripPublisherId=$tripInfo['createPublisherId'];
            $createPublisherId=$publisherService->findById($tripPublisherId);
            if(empty($createPublisherId)){
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法得到未知的随友'));
            }
            $createUserInfo=$userService->findUserByUserSign($createPublisherId->userId);
            $data['userInfo']=$createUserInfo;
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$this->unifyReturn($data)));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    public function actionGetCommentList()
    {
        $this->loginValid(false);
        try{
            $cPage=\Yii::$app->request->post('cPage');
            $tripId=\Yii::$app->request->post('tripId');
            $numb=\Yii::$app->request->post('numb');
            if(empty($cPage)||$cPage<1)
            {
                $cPage=1;
            }
            $page=new Page();
            $page->currentPage=$cPage;
            $page->pageSize=$numb;
            $page->startRow = (($page->currentPage - 1) * $page->pageSize);
            if(empty($this->userObj))
            {
                $userSign='';
            }else
            {
                $userSign=$this->userObj->userSign;
            }

            $rst= $this->tripCommentSer->getTravelComment($tripId,$page,$userSign);

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取评论列表失败"));
        }
    }

    public function actionAddComment()
    {
        $this->loginValid();
        try{
            $userSign=$this->userObj->userSign;
            $tripId = \Yii::$app->request->post('tripId');
            $content = \Yii::$app->request->post('content');
            $rId= \Yii::$app->request->post('rId');
            $rTitle= \Yii::$app->request->post('rTitle');
            $rSign= \Yii::$app->request->post('rSign');
            if(empty($tripId)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法评论未知随游'));}
            if(empty($content)){return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'无法发布空评论'));}
            $this->tripCommentSer->addComment($userSign,$content,$rId,$tripId,$rTitle,$rSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"发布评论失败"));
        }
    }
    public function actionAddSupport()
    {
        $this->loginValid();
        try {
            $userSign =$this->userObj->userSign;
            $commentId= \Yii::$app->request->post('rId');//评论id
            $this->tripCommentSer->addCommentSupport($commentId,$userSign,TravelTripComment::TYPE_SUPPORT);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'success'));
        }catch (Exception $e){
            LogUtils::log($e);
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    private function ob2ar($obj) {
        $obj=$this->unifyReturn($obj);
        if(is_object($obj)) {
            $obj = (array)$obj;
            $obj = $this->ob2ar($obj);
        } elseif(is_array($obj)) {
            foreach($obj as $key => $value) {
                $obj[$key] = $this->ob2ar($value);
            }
        }
        return $obj;
    }
    private function unifyReturn($data)
    {
        if($data==false)
        {
            $data=array();
        }
        return $data;
    }


}
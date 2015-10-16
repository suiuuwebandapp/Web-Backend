<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/10/13
 * Time : 13:52
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\controllers;


use backend\components\Page;
use backend\components\TableResult;
use backend\services\CountryService;
use backend\services\VolunteerService;
use common\components\Code;
use common\entity\VolunteerTrip;
use yii\base\Exception;

class VolunteerController  extends CController{


    private $volunteerService;


    public function __construct($id, $module = null)
    {
        $this->volunteerService=new VolunteerService();
        parent::__construct($id, $module);
    }
    public function actionList()
    {
        return $this->render('list');
    }

    public function actionGetList()
    {
        $page=new Page(\Yii::$app->request);
        $page->sortName="volunteerId";
        $page->sortType="desc";
        $search=\Yii::$app->request->get("searchText","");
        $status=\Yii::$app->request->get('status');
        $page = $this->volunteerService->getVolunteerList($page,$search,null,null,$status);
        $tableResult=new TableResult($page->draw,count($page->getList()),$page->totalCount,$page->getList());
        echo json_encode($tableResult);


    }

    public function actionToAdd()
    {
        $countryService=new CountryService();
        $page=new Page();
        $page->showAll=true;
        $page=$countryService->getCountryList($page,null);
        $countryList=$page->getList();
        return $this->render("add",[
            'countryList'=>$countryList
        ]);
    }


    public function actionEdit()
    {
        $id=\Yii::$app->request->get('volunteerId');
        $countryService=new CountryService();
        $page=new Page();
        $page->showAll=true;
        $page=$countryService->getCountryList($page,null);
        $countryList=$page->getList();

        $volunteerInfo=$this->volunteerService->findObjectById(VolunteerTrip::class,$id);
        return $this->render("edit",[
            'volunteerInfo'=>$volunteerInfo,
            'countryList'=>$countryList
        ]);
    }

    public function actionView()
    {
        return $this->render('view');
    }


    public function actionChangeStatus()
    {
        $volunteerId=\Yii::$app->request->post('volunteerId','');
        $status=\Yii::$app->request->post('status','');

        $volunteer=$this->volunteerService->findObjectById(VolunteerTrip::class,$volunteerId);
        $volunteer=$this->volunteerService->arrayCastObject($volunteer,VolunteerTrip::class);
        $volunteer->status=$status;

        try{
            $this->volunteerService->updateObject($volunteer);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }

    }
    /**
     * 保存志愿产品
     * @return string
     */
    public function actionSave()
    {
        $title=\Yii::$app->request->post('title','');
        $titleImg=\Yii::$app->request->post('titleImg','');
        $ageInfo=\Yii::$app->request->post('ageInfo','');
        $teamCount=\Yii::$app->request->post('teamCount','');
        $beginSite=\Yii::$app->request->post('beginSite','');

        $countryId=\Yii::$app->request->post('countryIds','');
        $cityId=\Yii::$app->request->post('cityIds','');

        $kind = \Yii::$app->request->post('kind', '');
        $eat=\Yii::$app->request->post('eat','');
        $hotel=\Yii::$app->request->post('hotel','');
        $info=\Yii::$app->request->post('info','');


        $orgName=\Yii::$app->request->post('orgName','');
        $orgInfo=\Yii::$app->request->post('orgInfo','');
        $orgImg=\Yii::$app->request->post('orgImg','');


        $endDate=\Yii::$app->request->post('endDate','');
        $prepare=\Yii::$app->request->post('prepare','');
        $note=\Yii::$app->request->post('note','');
        $recommendInfo=\Yii::$app->request->post('recommendInfo','');
        $scheduleIntro=\Yii::$app->request->post('scheduleIntro','');

        $includeList=\Yii::$app->request->post('includeList','');
        $unIncludeList=\Yii::$app->request->post('unIncludeList','');
        $dateList=\Yii::$app->request->post('dateList','');
        $scheduleList=\Yii::$app->request->post('scheduleList','');
        $priceList=\Yii::$app->request->post('priceList','');
        $picList=\Yii::$app->request->post('picList','');


        try{

            $priceArray=[];
            foreach($priceList as $priceInfo){
                $temp=[];
                $temp['price']=$priceInfo[0];
                $temp['day']=$priceInfo[1];
                $priceArray[]=$temp;
            }
            $volunteer=new VolunteerTrip();
            $volunteer->sysUserId=$this->userObj->userId;
            $volunteer->title=$title;
            $volunteer->titleImg=$titleImg;
            $volunteer->ageInfo=$ageInfo;
            $volunteer->teamCount=$teamCount;
            $volunteer->countryId=$countryId;
            $volunteer->cityId=$cityId;
            $volunteer->kind=$kind;
            $volunteer->eat=$eat;
            $volunteer->hotel=$hotel;
            $volunteer->info=$info;
            $volunteer->beginSite=$beginSite;
            $volunteer->endDate=$endDate;
            $volunteer->prepare=$prepare;
            $volunteer->note=$note;
            $volunteer->orgName=$orgName;
            $volunteer->orgImg=$orgImg;
            $volunteer->orgInfo=$orgInfo;
            $volunteer->recommendInfo=$recommendInfo;
            $volunteer->scheduleIntro=$scheduleIntro;
            $volunteer->scheduleList=json_encode($scheduleList);
            $volunteer->includeList=json_encode($includeList);
            $volunteer->unIncludeList=json_encode($unIncludeList);
            $volunteer->dateList=str_replace(' ','',$dateList);
            $volunteer->priceList=json_encode($priceArray);
            $volunteer->picList=json_encode($picList);

            $volunteer->status=VolunteerTrip::VOLUNTEER_STATUS_OUTLINE;

            $this->volunteerService->saveObject($volunteer);
            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL,$e->getMessage()));
        }


    }




    public function actionUpdate()
    {
        $volunteerId = \Yii::$app->request->post('volunteerId', '');
        $title = \Yii::$app->request->post('title', '');
        $titleImg = \Yii::$app->request->post('titleImg', '');
        $ageInfo = \Yii::$app->request->post('ageInfo', '');
        $teamCount = \Yii::$app->request->post('teamCount', '');
        $beginSite = \Yii::$app->request->post('beginSite', '');

        $countryId = \Yii::$app->request->post('countryIds', '');
        $cityId = \Yii::$app->request->post('cityIds', '');

        $kind = \Yii::$app->request->post('kind', '');
        $eat = \Yii::$app->request->post('eat', '');
        $hotel = \Yii::$app->request->post('hotel', '');
        $info = \Yii::$app->request->post('info', '');


        $orgName = \Yii::$app->request->post('orgName', '');
        $orgInfo = \Yii::$app->request->post('orgInfo', '');
        $orgImg = \Yii::$app->request->post('orgImg', '');


        $endDate = \Yii::$app->request->post('endDate', '');
        $prepare = \Yii::$app->request->post('prepare', '');
        $note = \Yii::$app->request->post('note', '');
        $recommendInfo = \Yii::$app->request->post('recommendInfo', '');
        $scheduleIntro = \Yii::$app->request->post('scheduleIntro', '');

        $includeList = \Yii::$app->request->post('includeList', '');
        $unIncludeList = \Yii::$app->request->post('unIncludeList', '');
        $dateList = \Yii::$app->request->post('dateList', '');
        $scheduleList = \Yii::$app->request->post('scheduleList', '');
        $priceList = \Yii::$app->request->post('priceList', '');
        $picList = \Yii::$app->request->post('picList', '');


        try {

            $volunteer=$this->volunteerService->findObjectById(VolunteerTrip::class,$volunteerId);
            $volunteer=$this->volunteerService->arrayCastObject($volunteer,VolunteerTrip::class);

            $priceArray = [];
            foreach ($priceList as $priceInfo) {
                $temp = [];
                $temp['price'] = $priceInfo[0];
                $temp['day'] = $priceInfo[1];
                $priceArray[] = $temp;
            }

            $volunteer->title = $title;
            $volunteer->titleImg = $titleImg;
            $volunteer->ageInfo = $ageInfo;
            $volunteer->teamCount = $teamCount;
            $volunteer->countryId = $countryId;
            $volunteer->cityId = $cityId;
            $volunteer->kind = $kind;
            $volunteer->eat = $eat;
            $volunteer->hotel = $hotel;
            $volunteer->info = $info;
            $volunteer->beginSite = $beginSite;
            $volunteer->endDate = $endDate;
            $volunteer->prepare = $prepare;
            $volunteer->note = $note;
            $volunteer->orgName = $orgName;
            $volunteer->orgImg = $orgImg;
            $volunteer->orgInfo = $orgInfo;
            $volunteer->recommendInfo = $recommendInfo;
            $volunteer->scheduleIntro = $scheduleIntro;
            $volunteer->scheduleList = json_encode($scheduleList);
            $volunteer->includeList = json_encode($includeList);
            $volunteer->unIncludeList = json_encode($unIncludeList);
            $volunteer->dateList = str_replace(' ', '', $dateList);
            $volunteer->priceList = json_encode($priceArray);
            $volunteer->picList = json_encode($picList);


            $this->volunteerService->updateObject($volunteer);

            return json_encode(Code::statusDataReturn(Code::SUCCESS));
        } catch (Exception $e) {
            return json_encode(Code::statusDataReturn(Code::FAIL, $e->getMessage()));
        }
    }






}
<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/15
 * Time: 上午10:38
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use common\entity\TagList;
use common\entity\TravelPicture;
use common\entity\TravelPictureComment;
use frontend\components\Page;
use frontend\services\TagListService;
use frontend\services\TravelPictureService;
use yii;
use yii\base\Exception;

class TravelPictureController  extends AController {

    private $tpSer;
    private $tagSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->tpSer=new TravelPictureService();
        $this->tagSer =new TagListService();
    }

    public function actionAddTravelPicture()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $title = Yii::$app->request->post('title');
            $country = Yii::$app->request->post('country');
            $city = Yii::$app->request->post('city');
            $lon = Yii::$app->request->post('lon');
            $lat = Yii::$app->request->post('lat');
            $tags = Yii::$app->request->post('tags');
            $contents= Yii::$app->request->post('contents');
            $picList= Yii::$app->request->post('picList');
            if(empty($title)){return json_encode(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($country)){return json_encode(Code::statusDataReturn(Code::FAIL, "国家不能为空"));}
            if(empty($city)){return json_encode(Code::statusDataReturn(Code::FAIL, "城市不能为空"));}
            if(empty($lon)){return json_encode(Code::statusDataReturn(Code::FAIL, "经度不能为空"));}
            if(empty($lat)){return json_encode(Code::statusDataReturn(Code::FAIL, "纬度不能为空"));}
            if(empty($tags)){return json_encode(Code::statusDataReturn(Code::FAIL, "标签不能为空"));}
            if(empty($picList)){return json_encode(Code::statusDataReturn(Code::FAIL, "图片不能为空"));}
            $tpEntity = new TravelPicture();
            $tpEntity->title=$title;
            $tpEntity->country=$country;
            $tpEntity->city=$city;
            $tpEntity->lon=$lon;
            $tpEntity->lat=$lat;
            $tpEntity->tags=$tags;
            $tpEntity->contents=$contents;
            $tpEntity->picList=$picList;
            $tpEntity->userSign=$userSign;
            $this->tpSer->addTravelPicture($tpEntity);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"添加旅图异常"));
        }
    }
    public function actionAddTravelPictureComment()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $tpId = Yii::$app->request->post('tpId');
            $comment = Yii::$app->request->post('comment');
            if(empty($tpId)){return json_encode(Code::statusDataReturn(Code::FAIL, "未知的旅途"));}
            if(empty($comment)){return json_encode(Code::statusDataReturn(Code::FAIL, "评论不能为空"));}
            $tpcEntity = new TravelPictureComment();
            $tpcEntity->tpId=$tpId;
            $tpcEntity->comment=$comment;
            $tpcEntity->userSign=$userSign;
            $this->tpSer->addTravelPictureComment($tpcEntity);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"添加评论异常"));
        }
    }

    /**用户添加标签
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionAddTag()
    {
        $this->loginValid();
        $name=Yii::$app->request->post('name');
        if(!empty($name))
        {
            $id = $this->tagSer->addTagList($name,TagList::TYPE_TRIP_PIC_USER);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$id));
        }
        return json_encode(Code::statusDataReturn(Code::FAIL,"标签名称不能为空"));
    }

    /**得到系统标签
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetTag()
    {
        $tags =$this->tagSer->getTpSysTag();
        return json_encode(Code::statusDataReturn(Code::SUCCESS,$tags));
    }

    /**
     * @return string
     */
    public function actionGetInfo()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $id=Yii::$app->request->post("id");
            if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL, "id不能为空"));}

            $page=new Page(Yii::$app->request);
            $page->sortName='id';
            $page->sortType="DESC";
            $rst =$this->tpSer->getInfoById($page,$id,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"回答问题异常"));
        }
    }

    public function actionGetList()
    {
        $this->loginValid();
        try {
            $tags = Yii::$app->request->post('tags');
            $sortName=Yii::$app->request->post('sortName');
            $search = Yii::$app->request->post('search');
            $page = new Page();
            if($sortName==1){
                $page->sortName='id';
            }else
            {
                $page->sortName='id';
            }
            $page->sortType="DESC";
            $rst = $this->tpSer->getList($page,$tags,$search);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取异常"));
        }
    }


}
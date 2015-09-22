<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/15
 * Time: 上午10:38
 */

namespace app\modules\v1\controllers;


use common\components\Code;
use common\components\LogUtils;
use app\modules\v1\entity\TagList;
use app\modules\v1\entity\TravelPicture;
use app\modules\v1\entity\TravelPictureComment;
use app\components\Page;
use app\modules\v1\services\TagListService;
use app\modules\v1\services\TravelPictureService;
use yii;
use yii\base\Exception;

class AppTravelPictureController  extends AController {

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
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $title = Yii::$app->request->post('title');
            $country = Yii::$app->request->post('country');
            $city = Yii::$app->request->post('city');
            $lon = Yii::$app->request->post('lon');
            $lat = Yii::$app->request->post('lat');
            $tags = Yii::$app->request->post('tags');
            $contents= Yii::$app->request->post('contents');
            $picList= Yii::$app->request->post('picList');
            $titleImg= Yii::$app->request->post('titleImg');
            $address= Yii::$app->request->post('address');
            if(empty($title)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($country)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "国家不能为空"));}
            if(empty($city)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "城市不能为空"));}
            if(empty($lon)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "经度不能为空"));}
            if(empty($lat)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "纬度不能为空"));}
            if(empty($tags)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "标签不能为空"));}
            if(empty($picList)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "图片不能为空"));}
            if(empty($titleImg)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "封面不能为空"));}
            if(empty($address)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "详细地址不能为空"));}
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
            $tpEntity->titleImg=$titleImg;
            $tpEntity->address=$address;
            $this->tpSer->addTravelPicture($tpEntity);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"添加旅图异常"));
        }
    }
    public function actionAddTravelPictureComment()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $tpId = Yii::$app->request->post('tpId');
            $comment = Yii::$app->request->post('comment');
            if(empty($tpId)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "未知的旅途"));}
            if(empty($comment)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "评论不能为空"));}
            $tpcEntity = new TravelPictureComment();
            $tpcEntity->tpId=$tpId;
            $tpcEntity->comment=$comment;
            $tpcEntity->userSign=$userSign;
            $this->tpSer->addTravelPictureComment($tpcEntity);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"添加评论异常"));
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
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$id));
        }
        return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"标签名称不能为空"));
    }

    /**得到系统标签
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetTag()
    {
        $this->loginValid();
        $tags =$this->tagSer->getTpSysTag();
        return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$tags));
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
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $id=Yii::$app->request->get("id");
            if(empty($id)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "id不能为空"));}

            $page=new Page(Yii::$app->request);
            $page->sortName='id';
            $page->sortType="DESC";
            $rst =$this->tpSer->getInfoById($page,$id,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"旅途详情异常"));
        }
    }

    public function actionGetList()
    {
        $this->loginValid();
        try {
            $tags = Yii::$app->request->get('tags');
            $sortName=Yii::$app->request->get('sortName');
            $search = Yii::$app->request->get('search');
            $page = new Page(Yii::$app->request);
            if($sortName==1){
                $page->sortName='id';
            }else
            {
                $page->sortName='id';
            }
            $page->sortType="DESC";
            $rst = $this->tpSer->getList($page,$tags,$search);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取异常"));
        }
    }

    //得到类似
    public function actionGetLike()
    {
        $this->loginValid();
        try {
            $tpId = Yii::$app->request->get('tpId');
            $page = new Page(Yii::$app->request);
            $page->sortName='id';
            $page->sortType="DESC";
            $rst = $this->tpSer->getLike($page,$tpId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取异常"));
        }
    }

    public function actionGetUserTp()
    {
        $this->loginValid();
        try {
            $userSign = Yii::$app->request->get('userSign');
            $page = new Page(Yii::$app->request);
            $rst = $this->tpSer->getUserTp($page,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取用户旅图异常"));
        }
    }

}
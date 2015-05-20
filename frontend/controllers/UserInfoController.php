<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午7:17
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Common;
use common\components\DateUtils;
use common\entity\UserBase;
use frontend\components\Page;
use frontend\services\CountryService;
use common\components\Code;
use common\components\OssUpload;
use frontend\services\TravelTripCommentService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use yii\base\Exception;

class UserInfoController extends CController{

    public function __construct($id, $module = null)
    {
        $this->userBaseService = new UserBaseService();
        parent::__construct($id, $module);
    }


    public function actionIndex()
    {
        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();

        return $this->render("info",[
            'countryList'=>$countryList
        ]);
    }

    public function actionFindUserInfo()
    {
        $userSign=\Yii::$app->request->post("userSign");
        if(empty($userSign)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"无效的用户"));
            return;
        }
        try{
            $userInfo=$this->userBaseService->findBaseInfoBySign($userSign);
            if($userInfo->sex==UserBase::USER_SEX_MALE){
                $userInfo->sex='男';
            }else if($userInfo->sex==UserBase::USER_SEX_FEMALE){
                $userInfo->sex='女';
            }else{
                $userInfo->sex='保密';
            }
            $userInfo->birthday=DateUtils::convertBirthdayToAge($userInfo->birthday);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,$userInfo));
        }catch (Exception $e){
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    public function actionUpdateUserInfo()
    {
        $userId = $this->userObj->userId;
        $sex = trim(\Yii::$app->request->post('sex',UserBase::USER_SEX_SECRET));
        $nickname = trim(\Yii::$app->request->post('nickname'));
        $birthday = trim(\Yii::$app->request->post('birthday'));
        $intro = trim(\Yii::$app->request->post('intro'));
        $info = trim(\Yii::$app->request->post('info'));
        $countryId = \Yii::$app->request->post('countryId');
        $cityId = \Yii::$app->request->post('cityId');
        $lon = \Yii::$app->request->post('lon');
        $lat = \Yii::$app->request->post('lat');
        $profession = trim(\Yii::$app->request->post('profession'));

        if(empty($nickname)||strlen($nickname)>30){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"昵称格式不正确"));
            return;
        }
        if(empty($countryId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地国家"));
            return;
        }
        if(empty($cityId)){
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,"请选择居住地城市"));
            return;
        }
        try{
            $userInfo=$this->userBaseService->findUserById($userId);
            $userInfo->sex=$sex;
            $userInfo->nickname=$nickname;
            $userInfo->birthday=$birthday;
            $userInfo->intro=$intro;
            $userInfo->info=$info;
            $userInfo->countryId=$countryId;
            $userInfo->cityId=$cityId;
            $userInfo->lon=$lon;
            $userInfo->lat=$lat;
            $userInfo->profession=$profession;

            $this->userBaseService->updateUserBase($userInfo);
            $this->refreshUserInfo();

            echo json_encode(Code::statusDataReturn(Code::SUCCESS));

        }catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    public function actionChangeUserHeadImg()
    {
        $userId = $this->userObj->userId;
        $selectorX = \Yii::$app->request->post('x');
        $selectorY = \Yii::$app->request->post('y');
        $viewPortW = \Yii::$app->request->post('w');
        $viewPortH = \Yii::$app->request->post('h');
        $rotate = \Yii::$app->request->post('rotate');
        $source = \Yii::$app->request->post('src');
        $extArr=explode(".", $source);
        $ext = end($extArr);
        $pWidth = \Yii::$app->request->post('pWidth');
        $pHeight = \Yii::$app->request->post('pHeight');
        $rotate = 360 - $rotate;
        try {
            if ($ext == "png") {
                $img = imagecreatefrompng($source);
            }
            if ($ext == "jpg") {
                $img = imagecreatefromjpeg($source);
            }

            list($width, $height) = getimagesize($source);
            $newImg = imagecreatetruecolor($pWidth, $pHeight);
            //把图片扩充到300*300
            imagecopyresampled($newImg, $img, 0, 0, 0, 0, $pWidth, $pHeight, $width, $height);

            $resultImg = imagecreatetruecolor($viewPortW, $viewPortH);

            //裁剪
            imagecopy($resultImg, $newImg, 0, 0, $selectorX, $selectorY, $viewPortW, $viewPortH);

            if ($ext == "png") {
                imagesavealpha($resultImg, true);
            }

            if ($ext == "png") {
                $white = imagecolorallocatealpha($resultImg, 0, 0, 0, 127);
                imagealphablending($resultImg, false);
                imagefill($resultImg, 0, 0, $white);
                imagefill($resultImg, $viewPortW, 0, $white);
                imagefill($resultImg, 0, $viewPortH, $white);
                imagefill($resultImg, $viewPortW, $viewPortH, $white);
            }

            //旋转
            if ($rotate != 0 && $rotate != 360) {
                $resultImg = imagerotate($resultImg, $rotate, 0);
            }

            //获得文件扩展名
            $fileFolder = UploadController::LOCAL_IMAGE_DIR; //图片目录路径

            $fileFolder .= date("Ymd");
            if (!file_exists($fileFolder)) { // 判断存放文件目录是否存在
                mkdir($fileFolder, 0777, true);
            }
            $new_file_name = date("YmdHis") . '_' . rand(10000, 99999) . '.' . $ext;
            $picName = $fileFolder . "/" . $new_file_name;
            if ($ext == "png") {
                header('Content-type: image/png');
                imagepng($resultImg, $picName);
            }
            if ($ext == "jpg") {
                header('Content-type: image/jpg');
                imagejpeg($resultImg, $picName);
            }

            imagedestroy($resultImg);

            $ossUpload=new OssUpload();
            $rst=$ossUpload->putObject($picName,OssUpload::OSS_SUIUU_HEAD_DIR,$new_file_name);

            if($rst['status']==Code::SUCCESS){
                unlink($picName);
                $this->userBaseService->updateUserHeadImg($userId, $rst['data']);
                $this->refreshUserInfo();
                echo json_encode(Code::statusDataReturn(Code::SUCCESS, $rst['data']));
            }else{
                echo json_encode(Code::statusDataReturn(Code::FAIL));
            }

        } catch (Exception $e) {
            echo json_encode(Code::statusDataReturn(Code::FAIL, $e));
        }
    }

    //得到收藏随游
    public function  actionGetCollectionTravel()
    {

        try {
            if(empty($this->userObj))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'请登陆后再收藏'));
                return;
            }
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $AttentionService =new UserAttentionService();
            $data = $AttentionService->getUserCollectionTravel($userSign, $page);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }
    }


    //发言
    public function actionGetComment()
    {
        try {
            if(empty($this->userObj))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'登陆后才有发言'));
                return;
            }
            $cPage=\Yii::$app->request->post('cPage');
            if(empty($cPage)||$cPage<1)
            {
                $cPage=1;
            }
            $numb=5;
            $page=new Page();
            $page->currentPage=$cPage;
            $page->pageSize=$numb;
            $page->startRow = (($page->currentPage - 1) * $page->pageSize);
            $userSign = $this->userObj->userSign;
            $travelSer =new TravelTripCommentService();
            $rst = $travelSer->getCommentTripList($page,$userSign);
            $str='';
            $totalCount=$rst['msg']->totalCount;
            if(intval($totalCount)!=0)
            {
                $count=intval($totalCount);
                $str=Common::pageHtml($cPage,$numb,$count);
            }
            echo json_encode(Code::statusDataReturn(Code::SUCCESS, $rst,$str));
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }
    }

    public function actionUpdatePassword()
    {
        try {
            if(empty($this->userObj))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'登陆后才可以修改密码'));
                return;
            }
            $userSign = $this->userObj->userSign;
            $password=\Yii::$app->request->post('password');
            $qPassword=\Yii::$app->request->post('qPassword');
            $oPassword=\Yii::$app->request->post('oPassword');
            if(empty($oPassword)){ return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'旧密码不能为空'));}
            if(empty($password)){ return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'新密码不能为空'));}
            if($password!=$qPassword)
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'两次密码不统一'));
                return;
            }
            $rst=$this->userBaseService->findPasswordByUserSign($userSign);
            if(empty($rst)||$rst==false)
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'未找到用户'));
                return;
            }
            if(!$this->userBaseService->validatePassword($oPassword,$rst->password))
            {
                echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR,'旧密码不正确'));
                return;
            }
            $r=$this->userBaseService->updatePassword($userSign,$password);
            echo json_encode(Code::statusDataReturn(Code::SUCCESS,'修改成功'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            echo json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, $error));
        }

    }

}
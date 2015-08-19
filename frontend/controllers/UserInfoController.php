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
use common\components\LogUtils;
use common\entity\UserAccount;
use common\entity\UserBase;
use common\entity\UserPhoto;
use common\entity\UserPublisher;
use common\models\BaseDb;
use frontend\components\Page;
use frontend\interfaces\WechatInterface;
use frontend\services\CountryService;
use common\components\Code;
use common\components\OssUpload;
use frontend\services\TravelTripCommentService;
use frontend\services\UserAccountService;
use frontend\services\UserAttentionService;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\debug\models\search\Log;
use yii\web\Cookie;

class UserInfoController extends CController
{

    public function __construct($id, $module = null)
    {
        $this->userBaseService = new UserBaseService();
        parent::__construct($id, $module);
    }


    /**
     * 跳转到个人中心页面
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionIndex()
    {
        $tab = \Yii::$app->request->get("tab", $_SERVER['QUERY_STRING']);
        $tabInfo = \Yii::$app->request->get("tabInfo", "");
        $bindWechat = \Yii::$app->request->get("bindWechat", 0);

        $countryService = new CountryService();
        $countryList = $countryService->getCountryList();
        $userPublisher = $this->userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
        $cityInfo = null;
        $userAccountList = null;
        $openId = null;//微信绑定回调 用户微信OpenId
        $nickname = null;//微信绑定回调 用户微信昵称
        $bindAlipayAccount = false;
        $bindWechatAccount = false;
        //获取用户城市详情
        if (!empty($this->userObj->cityId)) {
            $cityInfo = $countryService->findCityById($this->userObj->cityId);
        }
        //获取用户账户收款信息
        if ($this->userObj->isPublisher) {
            $userAccountService = new UserAccountService();
            $userAccountList = $userAccountService->getUserAccountList($this->userObj->userSign);
        }
        if (!empty($userAccountList)) {
            foreach ($userAccountList as $userAccount) {
                if ($userAccount['type'] == UserAccount::USER_ACCOUNT_TYPE_WECHAT) {
                    $bindWechatAccount = true;
                }
                if ($userAccount['type'] == UserAccount::USER_ACCOUNT_TYPE_ALIPAY) {
                    $bindAlipayAccount = true;
                }
            }
        }
        //获取我的相册列表
        $photoList = $this->userBaseService->getUserPhotoList($this->userObj->userSign);
        //获取用户证件信息
        $userCard=$this->userBaseService->findUserCardByUserId($this->userObj->userSign);
        //获取用户资历信息
        $userAptitude=$this->userBaseService->findUserAptitudeByUserId($this->userObj->userSign);

        $wechatAccount = \Yii::$app->getSession()->get(Code::USER_WECHAT_ACCOUNT);
        return $this->render("info", [
            'countryList' => $countryList,
            'userPublisher' => $userPublisher,
            'cityInfo' => $cityInfo,
            'userAccountList' => $userAccountList,
            'tab' => $tab,
            'tabInfo' => $tabInfo,
            'wechatAccount' => $wechatAccount,
            'bindWechat' => $bindWechat,
            'bindWechatAccount' => $bindWechatAccount,
            'bindAlipayAccount' => $bindAlipayAccount,
            'photoList' => $photoList,
            'userCard'=>$userCard,
            'userAptitude'=>$userAptitude
        ]);
    }


    /**
     * 获取用户信息
     * @return string
     */
    public function actionFindUserInfo()
    {
        $userSign = \Yii::$app->request->post("userSign");
        if (empty($userSign)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "无效的用户"));
        }
        try {
            $userInfo = $this->userBaseService->findBaseInfoBySign($userSign);
            if ($userInfo->sex == UserBase::USER_SEX_MALE) {
                $userInfo->sex = '男';
            } else if ($userInfo->sex == UserBase::USER_SEX_FEMALE) {
                $userInfo->sex = '女';
            } else {
                $userInfo->sex = '保密';
            }
            $userInfo->birthday = DateUtils::convertBirthdayToAge($userInfo->birthday);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $userInfo));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 更新用户详情
     * @return string
     */
    public function actionUpdateUserInfo()
    {
        $userId = $this->userObj->userId;
        $surname = trim(\Yii::$app->request->post('surname'));
        $name = trim(\Yii::$app->request->post('name'));
        $qq = trim(\Yii::$app->request->post('qq'));
        $wechat = trim(\Yii::$app->request->post('wechat'));
        $sex = trim(\Yii::$app->request->post('sex', UserBase::USER_SEX_SECRET));
        $nickname = trim(\Yii::$app->request->post('nickname'));
        $birthday = trim(\Yii::$app->request->post('birthday'));
        $intro = trim(\Yii::$app->request->post('intro'));
        $info = trim(\Yii::$app->request->post('info'));
        $countryId = \Yii::$app->request->post('countryId');
        $cityId = \Yii::$app->request->post('cityId');
        $lon = \Yii::$app->request->post('lon');
        $lat = \Yii::$app->request->post('lat');
        $profession = trim(\Yii::$app->request->post('profession'));

        if (empty($nickname) || strlen($nickname) > 30) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "昵称格式不正确"));
        }
        if (empty($countryId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请选择居住地国家"));
        }
        if (empty($cityId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "请选择居住地城市"));
        }
        try {
            $userInfo = $this->userBaseService->findUserById($userId);
            $userInfo->surname = $surname;
            $userInfo->name = $name;
            $userInfo->qq = $qq;
            $userInfo->wechat = $wechat;
            $userInfo->sex = $sex;
            $userInfo->nickname = $nickname;
            $userInfo->birthday = $birthday;
            $userInfo->intro = $intro;
            $userInfo->info = $info;
            $userInfo->countryId = $countryId;
            $userInfo->cityId = $cityId;
            $userInfo->lon = $lon;
            $userInfo->lat = $lat;
            $userInfo->profession = $profession;

            $this->userBaseService->updateUserBase($userInfo);
            $this->refreshUserInfo();

            return json_encode(Code::statusDataReturn(Code::SUCCESS));

        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }


    /**
     * 截取用户头像并更新
     * @return string
     */
    public function actionChangeUserHeadImg()
    {
        $userId = $this->userObj->userId;
        $selectorX = \Yii::$app->request->post('x');
        $selectorY = \Yii::$app->request->post('y');
        $viewPortW = \Yii::$app->request->post('w');
        $viewPortH = \Yii::$app->request->post('h');
        $rotate = \Yii::$app->request->post('rotate');
        $source = \Yii::$app->request->post('src');
        $extArr = explode(".", $source);
        $ext = end($extArr);
        $pWidth = \Yii::$app->request->post('pWidth');
        $pHeight = \Yii::$app->request->post('pHeight');
        $rotate = 360 - $rotate;
        try {

            $rst = $this->uploadUserHeadImg($selectorX, $selectorY, $viewPortW, $viewPortH, $rotate, $source, $ext, $pWidth, $pHeight, $rotate);
            if ($rst['status'] == Code::SUCCESS) {
                $this->userBaseService->updateUserHeadImg($userId, $rst['data']);
                $this->refreshUserInfo();
                return json_encode(Code::statusDataReturn(Code::SUCCESS, $rst['data']));
            } else {
                return json_encode(Code::statusDataReturn(Code::FAIL));
            }

        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL, $e));
        }
    }

    /**
     * 得到收藏随游
     * @return string
     */
    public function  actionGetCollectionTravel()
    {

        try {
            if (empty($this->userObj)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '请登陆后再收藏'));
            }
            $page = new Page(\Yii::$app->request);
            $userSign = $this->userObj->userSign;
            $AttentionService = new UserAttentionService();
            $data = $AttentionService->getUserCollectionTravel($userSign, $page);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $data));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 获取用户发言
     * @return string
     */
    public function actionGetComment()
    {
        try {
            if (empty($this->userObj)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '登陆后才有发言'));
            }
            $cPage = \Yii::$app->request->post('cPage');
            if (empty($cPage) || $cPage < 1) {
                $cPage = 1;
            }
            $numb = 5;
            $page = new Page();
            $page->currentPage = $cPage;
            $page->pageSize = $numb;
            $page->startRow = (($page->currentPage - 1) * $page->pageSize);
            $userSign = $this->userObj->userSign;
            $travelSer = new TravelTripCommentService();
            $rst = $travelSer->getCommentTripList($page, $userSign);
            $str = '';
            $totalCount = $rst['msg']->totalCount;
            if (intval($totalCount) != 0) {
                $count = intval($totalCount);
                $str = Common::pageHtml($cPage, $numb, $count);
            }
            return json_encode(Code::statusDataReturn(Code::SUCCESS, $rst, $str));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 修改密码
     * @return string
     */
    public function actionUpdatePassword()
    {
        try {
            if (empty($this->userObj)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '登陆后才可以修改密码'));
            }
            $userSign = $this->userObj->userSign;
            $password = \Yii::$app->request->post('password');
            $qPassword = \Yii::$app->request->post('qPassword');
            $oPassword = \Yii::$app->request->post('oPassword');
            if (empty($oPassword)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '旧密码不能为空'));
            }
            if (empty($password)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '新密码不能为空'));
            }
            if ($password != $qPassword) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '两次密码不统一'));
            }
            $rst = $this->userBaseService->findPasswordByUserSign($userSign);
            if (empty($rst) || $rst == false) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '未找到用户'));
            }
            if (!$this->userBaseService->validatePassword($oPassword, $rst->password)) {
                return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, '旧密码不正确'));
            }
            $r = $this->userBaseService->updatePassword($userSign, $password);
            return json_encode(Code::statusDataReturn(Code::SUCCESS, '修改成功'));
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

    /**
     * 创建随游线路
     * @throws \Exception
     */
    public function actionCreateTravel()
    {
        //判断用户是否是随友，不是的话，跳转到随游注册页面
        if ($this->userObj->isPublisher) {
            return $this->redirect("/trip/new-trip");
        } else {

            $email = "";
            $phone = "";
            $areaCode = "";
            $nickname = "";
            $countryService = new CountryService();
            $countryList = $countryService->getCountryList();
            $userPublisher = null;

            if (isset($this->userObj)) {
                $email = $this->userObj->email;
                $phone = $this->userObj->phone;
                $nickname = $this->userObj->nickname;
                $userPublisher = $this->userBaseService->findUserPublisherByUserSign($this->userObj->userSign);
            }
            if ($areaCode == "") {
                $areaCode = "+86";
            }
            return $this->render("registerPublisher", [
                'email' => $email,
                'phone' => $phone,
                'areaCode' => $areaCode,
                'nickname' => $nickname,
                'countryList' => $countryList,
                'userPublisher' => $userPublisher
            ]);
        }
    }

    public function actionTest()
    {
        $this->refreshUserInfo();
    }

    /**
     * 注册随友
     */
    public function actionRegisterPublisher()
    {
        $nickname = trim(\Yii::$app->request->post("nickname", ""));
        $surname = trim(\Yii::$app->request->post("surname", ""));
        $name = trim(\Yii::$app->request->post("name", ""));
        $countryId = trim(\Yii::$app->request->post("countryId", ""));
        $cityId = trim(\Yii::$app->request->post("cityId", ""));
        $areaCode = trim(\Yii::$app->request->post("areaCode", ""));
        $phone = trim(\Yii::$app->request->post("phone", ""));
        $code = trim(\Yii::$app->request->post("code", ""));
        $qq = trim(\Yii::$app->request->post("qq", ""));
        $wechat = trim(\Yii::$app->request->post("wechat", ""));


        if (empty($nickname)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "昵称不能为空"));
        }
        if (empty($surname)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "姓氏不能为空"));
        }
        if (empty($name)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "名字不能为空"));
        }
        if (empty($countryId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "国家不能为空"));
        }
        if (empty($cityId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "城市不能为空"));
        }
        if (empty($areaCode)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "手机区号不能为空"));
        }
        if (empty($phone)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "手机号码不能为空"));
        }
        if(empty($qq)&&empty($wechat)){
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "QQ和微信至少填写一个"));
        }

        $userBase = null;
        if ($this->userObj == null) {
            $userBase = new UserBase();
            $userPublisher = new UserPublisher();
        } else {
            $userBase = clone $this->userObj;
            $userPublisher = $this->userBaseService->findUserPublisherByUserSign($userBase->userSign);
            if ($userPublisher == null) {
                $userPublisher = new UserPublisher();
            }
        }
        $userBase->nickname = $nickname;
        $userBase->surname = $surname;
        $userBase->name = $name;
        $userBase->countryId = $countryId;
        $userBase->cityId = $cityId;
        $userBase->profession = '';
        $userBase->qq=$qq;
        $userBase->wechat=$wechat;


        try {
            if (empty($userBase->phone)) {
                $userBase->phone = $phone;
                $userBase->areaCode = $areaCode;
                //判断验证码是否正确
                if (!$this->validatePhoneCode($phone, $code)) {
                    return json_encode(Code::statusDataReturn(Code::FAIL, "手机验证码输入有误"));
                }
                //验证手机是否存在
                if ($this->userBaseService->validatePhoneExist($phone, $userBase->userId)) {
                    return json_encode(Code::statusDataReturn(Code::FAIL, "手机号码已经注册"));
                }
            }

            $this->userBaseService->updateUserBase($userBase);
            $this->refreshUserInfo();//刷新缓存
            return json_encode(Code::statusDataReturn(Code::SUCCESS, '/user-info/register-publisher-next'));

        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    public function actionRegisterPublisherNext()
    {
        return $this->render("registerPublisherNext");
    }

    public function actionRegisterPublisherFinish()
    {
        $userId = $this->userObj->userSign;
        $selectorX = \Yii::$app->request->post('x');
        $selectorY = \Yii::$app->request->post('y');
        $viewPortW = \Yii::$app->request->post('w');
        $viewPortH = \Yii::$app->request->post('h');
        $rotate = \Yii::$app->request->post('rotate');
        $source = \Yii::$app->request->post('src', '');
        $extArr = explode(".", $source);
        $ext = end($extArr);
        $pWidth = \Yii::$app->request->post('pWidth');
        $pHeight = \Yii::$app->request->post('pHeight');
        $rotate = 360 - $rotate;
        try {

            $rst = $this->uploadUserHeadImg($selectorX, $selectorY, $viewPortW, $viewPortH, $rotate, $source, $ext, $pWidth, $pHeight, $rotate);
            if ($rst['status'] == Code::SUCCESS) {
                $userBase = $this->userBaseService->findUserByUserSign($userId);
                $userBase->headImg = $rst['data'];

                $userPublisher = new UserPublisher();
                $userPublisher->countryId = $userBase->countryId;
                $userPublisher->cityId = $userBase->cityId;
                $userPublisher->kind = UserPublisher::USER_PUBLISHER_CARD_KIND_NO;

                $this->userBaseService->updateUserBaseAndAddUserPublisher($userBase, $userPublisher);
                $this->refreshUserInfo();
                return json_encode(Code::statusDataReturn(Code::SUCCESS));
            } else {
                return json_encode(Code::statusDataReturn(Code::FAIL));
            }

        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    /**
     * 上传用户头像
     * @param $selectorX
     * @param $selectorY
     * @param $viewPortW
     * @param $viewPortH
     * @param $rotate
     * @param $source
     * @param $ext
     * @param $pWidth
     * @param $pHeight
     * @param $rotate
     * @return array
     */
    private function uploadUserHeadImg($selectorX, $selectorY, $viewPortW, $viewPortH, $rotate, $source, $ext, $pWidth, $pHeight, $rotate)
    {
        try {
            $source = "." . $source;
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

            $ossUpload = new OssUpload();
            $rst = $ossUpload->putObject($picName, OssUpload::OSS_SUIUU_HEAD_DIR, $new_file_name);
            unlink($picName);
            return $rst;
        } catch (Exception $e) {
            LogUtils::log($e);
            return Code::statusDataReturn(Code::FAIL);
        }
    }


    /**
     * 验证手机验证码
     * @param $phone
     * @param $code
     * @return bool
     */
    private function validatePhoneCode($phone, $code)
    {
        $vCode = \Yii::$app->redis->get(Code::USER_PHONE_VALIDATE_CODE_AND_PHONE . $phone);
        return $vCode == $code ? true : false;
    }


    /**
     * 删除用户照片
     * @return string
     */
    public function actionRemoveUserPhoto()
    {
        $userId = $this->userObj->userSign;
        $photoId = \Yii::$app->request->post("photoId");

        if (empty($photoId)) {
            return json_encode(Code::statusDataReturn(Code::PARAMS_ERROR, "无效的图片"));
        }
        try {
            $this->userBaseService->deleteUserPhoto($photoId, $userId);
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }


    /**
     * 添加用户资质认证申请
     * @return string
     */
    public function actionApplyUserAptitude()
    {
        $userId = $this->userObj->userSign;

        try {
            $this->userBaseService->addUserAptitude( $userId);
        } catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
        return json_encode(Code::statusDataReturn(Code::SUCCESS));
    }



}
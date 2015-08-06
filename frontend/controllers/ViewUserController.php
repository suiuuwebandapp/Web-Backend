<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/7/28
 * Time : 下午2:01
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use common\components\Code;
use common\components\LogUtils;
use frontend\components\Page;
use frontend\services\TravelTripCommentService;
use frontend\services\TripService;
use frontend\services\UserBaseService;
use yii\base\Exception;

class ViewUserController extends UnCController{



    public function actionInfo()
    {
        $userSign=\Yii::$app->request->get('u','');
        $userInfo=null;
        $tripList=null;
        $recommendList=null;
        try{
            $userBaseService=new UserBaseService();
            $userInfo=$userBaseService->findUserByUserSignArray($userSign);
            if(empty($userInfo)){
                return $this->redirect(['/result', 'result' => '无效的用户信息']);
            }
            if($userInfo['isPublisher']){
                $userPublisher=$userBaseService->findUserPublisherByUserSign($userSign);
                $tripService=new TripService();
                $tripList=$tripService->getMyTripList($userPublisher->userPublisherId);


            }

            $page=new Page();
            $page->initPage(1,2);
            $travelSer =new TravelTripCommentService();
            $commentList = $travelSer->getCommentTripList($page,$userSign);


            //获取用户证件信息
            $userCard=$userBaseService->findUserCardByUserId($userSign);
            //获取用户资历信息
            $userAptitude=$userBaseService->findUserAptitudeByUserId($userSign);


        }catch (Exception $e){
            LogUtils::log($e);
            return $this->redirect(['/result', 'result' => '获取用户信息失败']);
        }
        return $this->render("info",[
            'userInfo'=>$userInfo,
            'tripList'=>$tripList,
            'commentList'=>$commentList,
            'userCard'=>$userCard,
            'userAptitude'=>$userAptitude
        ]);
    }

    /**
     * 获取用户评论详情
     * @return string
     * @throws Exception
     */
    public function actionGetUserCommentList()
    {
        $currentPage=\Yii::$app->request->post('p',1);
        $userSign=\Yii::$app->request->post('u');
        $page=new Page();
        $page->initPage($currentPage,2);
        $travelSer =new TravelTripCommentService();
        try{
            $commentList = $travelSer->getCommentTripList($page,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$commentList));
        }catch (Exception $e){
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }

    }

}
<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/23
 * Time : 11:23
 * Email: zhangxinmailvip@foxmail.com
 */

namespace console\controllers;

use backend\components\Page;
use backend\services\TravelPictureService;
use common\models\TravelPictureDb;
use frontend\services\UserBaseService;
use common\components\UrlUtil;
use common\entity\TravelPicture;
use common\entity\TravelPictureComment;
use common\entity\UserBase;
use yii\base\Exception;
use yii\console\Controller;

require_once (dirname(dirname(dirname(__FILE__)))."/common/components/simple_html_dom.php");


class QyerWeiController extends Controller{



    public function actionTest()
    {

        $pService=new TravelPictureService();
        $page=new Page();
        $page->showAll=true;
        $page=$pService->getTpList($page,null,null,null);
        $allTag='';
        foreach($page->getList() as $travelPicture)
        {
            if($travelPicture['tags']==''){
                continue;
            }
            $allTag.=$travelPicture['tags'].",";
        }
        $list=explode(',',$allTag);
        $newArray=[];
        foreach($list as $name)
        {
            if(!array_key_exists($name,$newArray)){
                $newArray[$name]=1;
            }else{
                $newArray[$name]= $newArray[$name]+1;
            }
        }
        asort($newArray);

        var_dump($newArray);
    }

    public function cmp($a, $b){
        return $a['order'] - $b['order'];
    }

    public function actionRun()
    {
        self::actionAodili();
        self::actionBilishi();
        self::actionDeguo();
        self::actionFaguo();
        self::actionHelan();
        self::actionHanguo();
        self::actionMalaixiya();
        self::actionXianggang();
        self::actionRiben();
        self::actionRuishi();
        self::actionTaiguo();
        self::actionXinjiapo();
        self::actionTaiwan();
        self::actionYidali();
        self::actionYingguo();
        self::actionPutaoya();
        self::actionXibanya();

    }
    public function actionXianggang()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=9;
        $type='city';
        $pid='50';
        $filePath=dirname(__FILE__)."/".'QY-WEI-XiangGang.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'中国','香港');
    }

    public function actionHanguo()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=12;
        $type='country';
        $pid='233';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Hanguo.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'韩国','');
    }

    public function actionRiben()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=23;
        $type='country';
        $pid='14';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Riben.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'日本','');
    }


    public function actionTaiguo()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=11;
        $type='country';
        $pid='215';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Taiguo.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'泰国','');
    }



    public function actionMalaixiya()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=4;
        $type='country';
        $pid='213';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Malaixiya.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'马来西亚','');
    }


    public function actionXinjiapo()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=8;
        $type='city';
        $pid='62';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Xinjiapo.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'新加坡','');
    }

    public function actionTaiwan()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=13;
        $type='city';
        $pid='11186';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Taiwan.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'中国','台湾');
    }

    public function actionFaguo()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=13;
        $type='country';
        $pid='186';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Faguo.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'法国','');
    }

    public function actionDeguo()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=9;
        $type='country';
        $pid='15';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Deguo.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'德国','');
    }
    public function actionYingguo()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=15;
        $type='country';
        $pid='13';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Yinguo.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'英国','');
    }
    public function actionHelan()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=3;
        $type='country';
        $pid='200';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Helan.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'荷兰','');
    }
    public function actionRuishi()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=4;
        $type='country';
        $pid='524';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Ruishi.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'瑞士','');
    }
    public function actionYidali()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=13;
        $type='country';
        $pid='189';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Yidali.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'意大利','');
    }
    public function actionXibanya()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=9;
        $type='country';
        $pid='182';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Xibanya.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'西班牙','');
    }
    public function actionPutaoya()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=3;
        $type='country';
        $pid='523';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Putaoya.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'葡萄牙','');
    }
    public function actionAodili()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=3;
        $type='country';
        $pid='198';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Aodili.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'奥地利','');
    }
    public function actionBilishi()
    {
        $baseUrl='http://place.qyer.com/mguide.php';
        $pageCount=2;
        $type='country';
        $pid='424';
        $filePath=dirname(__FILE__)."/".'QY-WEI-Bilishi.txt';

        self::saveUrl($filePath,$baseUrl,$pageCount,$type,$pid);
        self::getUrlInfo($filePath,'比利时','');
    }


    private function getUrlInfo($filePath,$country,$city)
    {
        $allUrl=file_get_contents($filePath);
        $urlUtil=new UrlUtil();
        echo '开始循环存储详情页URL '."\n";
        $allUrlArray=explode(",",$allUrl);
        foreach($allUrlArray as $url)
        {
            if(empty($url)){
                continue;
            }
            $travelPictureList=[];
            echo '开始存储Url： '.$url."\n";
            $newUserList=[];
            try{
                $response=$urlUtil->get($url,[]);
            }catch (Exception $e){
                echo "获取URL：".$url."异常"."\n";
                $errorList[]=$url;
                continue;
            }

            if(empty($response)){
                continue;
            }

            $html = new \simple_html_dom();
            $html->load($response);

            //$titleImg=$html->find('.mguide-img')[0]->getAttribute('src');

            if(count($html->find('.mguide-avatar'))==0){
                continue;
            }
            $userId=$html->find('.mguide-avatar')[0]->find('.avatar')[0]->getAttribute('href');
            $userId='q-'.str_replace('http://www.qyer.com/u/','',$userId);
            $headImg=$html->find('.mguide-avatar')[0]->find('.avatar img')[0]->getAttribute('src');
            $nickname=$html->find('.mguide-avatar')[0]->find('.title')[0]->plaintext;
            $nickname=trim($nickname);
            $sex=$html->find('.mguide-author i')[0]->getAttribute('class');
            if($sex=='icon-male'){
                $sex=UserBase::USER_SEX_MALE;
            }else if($sex=='icon-female'){
                $sex=UserBase::USER_SEX_FEMALE;
            }else{
                $sex=UserBase::USER_SEX_SECRET;
            }

            $tags=[];
            foreach($html->find('.detail-tags span') as $t){
                $tags[]=trim($t->plaintext);
                if(count($tags)==3){
                    break;
                }
            }
            $createUserInfo=self::createUser($userId,$nickname,$headImg,$sex);
            $newUserList[$userId]=$createUserInfo;

            foreach($html->find('#poiList')[0]->find('.mguide-list') as $element)
            {
                $contentList=[];$picList=[];$commentList=[];
                $title=$element->find('.title-detail a')[0]->plaintext;
                $address=trim($element->find('.title-tag .title')[0]->plaintext);
                $content=trim($element->find('.detail')[0]->plaintext);
                $like=trim($element->find('.praise')[0]->plaintext);
                $like=trim(str_replace('有用','',$like));
                if(mb_strlen($content,'UTF-8')>200){
                    continue;
                }
                if(empty($like)){
                    $like=0;
                }
                $titleImg='';
                foreach($element->find('.listsWrap li') as $li)
                {
                    $imgSrc=$li->find('img')[0]->getAttribute('src');
                    //$baseImgUrl=substr($imgSrc,0,strlen($imgSrc)-3);
                    $baseImgUrl=str_replace('/index/80','/index/710x360',$imgSrc);

                    $picList[]=$baseImgUrl;
                    $contentList[]='';
                    if(empty($titleImg)){
                        $titleImg=$baseImgUrl;
                    }
                }
                array_pop($contentList);
                $contentList[]=$content;
                $travelPicture=new TravelPicture();
                $travelPicture->userSign=$userId;
                $travelPicture->address=$address;
                $travelPicture->title=$title;
                $travelPicture->titleImg=$titleImg;
                $travelPicture->contents=json_encode($contentList);
                $travelPicture->picList=json_encode($picList);
                $travelPicture->attentionCount=$like;
                $travelPicture->country=$country;
                $travelPicture->city=$city;
                $travelPicture->lon='';
                $travelPicture->lat='';
                $travelPicture->tags=implode(',',$tags);

                $commentList=[];
                if(count($element->find('.stars a'))>0){
                    $commentId=$element->find('.stars a')[0]->getAttribute('data-bn-ipg');
                    $commentId=str_replace('place-mguide-view-moreCommentsLink-[','',$commentId);
                    $commentId=str_replace(']','',$commentId);

                    $rst=self::getCommentList($commentId);
                    $commentUserList=$rst['userList'];
                    $commentList=$rst['commentList'];
                    if(!empty($commentUserList)){
                        $newUserList=array_merge($newUserList,$commentUserList);
                    }
                    if(!empty($rst['firstCommentTime'])){
                        $travelPicture->createTime=$rst['firstCommentTime'];
                    }
                }
                $travelPicture->commentCount=count($commentList);
                if(empty($travelPicture->createTime)){
                    $t='-'.rand(1,365).' days';
                    $travelPicture->createTime=date('Y-m-d',strtotime($t));
                }
                $tempTravelPicture['info']=$travelPicture;
                $tempTravelPicture['comment']=$commentList;

                $travelPictureList[]=$tempTravelPicture;
            }
            self::saveTravelPicture($travelPictureList,$newUserList);

            echo '结束存储Url： '.$url."\n";
        }
        echo ' 结束循环存储详情页URL '."\n";
    }

    private function saveTravelPicture($travelPictureList,$userList)
    {
        $userBaseService=new UserBaseService();
        foreach($travelPictureList as $travelPictureInfo)
        {
            $info=$travelPictureInfo['info'];
            $commentList=$travelPictureInfo['comment'];

            $userBaseService->saveObject($info);
            $tpId=$userBaseService->getLastInsertId();
            echo '存储旅途成功'."\n";

            foreach($commentList as $comment)
            {
                $comment->tpId=$tpId;
                $userBaseService->saveObject($comment);
            }
            echo '存储评论'.count($commentList)."\n";

        }
        $userCount=0;
        foreach($userList as $key=> $user)
        {
            $rst=$userBaseService->findObjectByType(UserBase::class,'school',$key);
            if(empty($rst)&&count($rst)==0){
                $userBaseService->addUser($user);
                $userCount++;
            }
        }
        echo '存储用户'.count($userCount)."\n";
    }


    private function createUser($id,$nickname,$headImg,$sex=UserBase::USER_SEX_SECRET)
    {
        $user=new UserBase();
        $user->nickname=$nickname;
        $user->headImg=$headImg;
        $user->school= $id;
        $user->sex=$sex;
        $user->password='qwe123';
        $user->registerIp='120.52.65.242';
        $user->lastLoginIp='120.52.65.242';

        return $user;
    }

    private function getCommentList($id)
    {

        $commentList=[];
        $userList=[];
        $firstCommentTime='';
        for($i=1;$i<=5;$i++)
        {
            $url='http://place.qyer.com/poi.php?action=comment';
            $array=[
                'page'=>$i,
                'order'=>2,
                'poiid'=>$id,
                'startLevel'=>'all'
            ];
            $urlUtil=new UrlUtil();
            $response=$urlUtil->post($url,$array);
            $response=json_decode($response,true);
            if($response['error_code']!=0){
                break;
            }
            if(empty($response['data'])){
                break;
            }
            if(!array_key_exists('lists',$response['data'])||count($response['data']['lists'])==0){
                break;
            }
            foreach($response['data']['lists'] as $temp)
            {

                $nickname=$temp['userinfo']['name'];
                $headImg=$temp['userinfo']['avatar'];
                $userId=$temp['userinfo']['link'];
                $userId='q-'.str_replace('http://www.qyer.com/u/','',$userId);

                $commentTime=$temp['date'];
                $commentContent=$temp['content'];
                $commentUseful=$temp['useful'];
                if(strlen($commentContent)>100){
                    continue;
                }
                $firstCommentTime=$commentTime;
                $travelPictureComment=new TravelPictureComment();
                $travelPictureComment->comment=$commentContent;
                $travelPictureComment->createTime=$commentTime;
                $travelPictureComment->userSign=$userId;
                $travelPictureComment->supportCount=$commentUseful;

                $commentList[]=$travelPictureComment;

                $user=self::createUser($userId,$nickname,$headImg);

                if(!array_key_exists($userId,$userList)){
                    $userList[$userId]=$user;
                }
            }

        }
        $result=[];
        $result['commentList']=$commentList;
        $result['userList']=$userList;
        $result['firstCommentTime']=$firstCommentTime;
        return $result;

    }



    /**
     * 存储列表页的所有详情地址
     * @param $filePath
     * @param $url
     * @param $pageCount
     * @param $type
     * @param $pid
     */
    private function saveUrl($filePath,$url,$pageCount,$type,$pid)
    {


        for($i=1;$i<=$pageCount;$i++)
        {
            echo 'Begin Save Page Url'.$i."\n";
            $params=[
                'action'=>'list_json',
                'page'=>$i,
                'type'=>$type,
                'pid'=>$pid,
                'sort'=>0
            ];
            $urlUtil=new UrlUtil();
            $response=$urlUtil->get($url,$params);
            $response=json_decode($response,true);

            if($response['error_code']!=0){
                return;
            }
            if($response['data']==null||!array_key_exists('list',$response['data'])){
                break;
            }
            foreach($response['data']['list'] as $temp)
            {
                $href=$temp['url']."/";
                file_put_contents($filePath,$href.",",FILE_APPEND);
            }
            echo 'End Save Page Url'.$i."\n";

        }

    }

}
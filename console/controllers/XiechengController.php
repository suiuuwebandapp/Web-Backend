<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/9/8
 * Time : 10:03
 * Email: zhangxinmailvip@foxmail.com
 */

namespace console\controllers;


use common\components\UrlUtil;
use common\entity\AnswerCommunity;
use common\entity\QuestionCommunity;
use common\entity\UserBase;
use frontend\services\UserBaseService;
use yii\base\Exception;
use yii\console\Controller;

require (dirname(dirname(dirname(__FILE__)))."/common/components/simple_html_dom.php");


class XiechengController extends Controller{


    public $basePath='http://you.ctrip.com/';

    public $countryId='225';
    public $cityId='4126';

    public $addr='中国,香港';

    /**
     * 香港[countryId=>225,cityId=>4126,addr=>'中国,香港']
     * 新加坡[countryId=>3759,cityId=>4150,addr=>'新加坡,新加坡市']
     */
    public function actionXianggang()
    {
        $baseUrl='http://you.ctrip.com/asks/hongkong38-k1';

        $filePath=dirname(__FILE__)."/".'XiangGang.txt';
        //self::getXCQuestionList($baseUrl,1000,$filePath);
        self::getXCQuestionInfo($filePath,225,4126,'中国,香港');
    }


    public function actionXinjiapo()
    {
        $baseUrl='http://you.ctrip.com/asks/singapore53-k1';
        $filePath=dirname(__FILE__)."/".'XinjiaPo.txt';
        //self::getXCQuestionList($baseUrl,1000,$filePath);
        self::getXCQuestionInfo($filePath,'3759','4150','新加坡,新加坡市');
    }

    public function actionYidali()
    {
        $baseUrl='http://you.ctrip.com/asks/italy100026-k1';
        $filePath=dirname(__FILE__)."/".'yidali.txt';
        self::getXCQuestionList($baseUrl,70,$filePath);
        self::getXCQuestionInfo($filePath,'3884',null,'意大利');
    }
    public function actionFaguo()
    {
        $baseUrl='http://you.ctrip.com/asks/france100024-k1';
        $filePath=dirname(__FILE__)."/".'faguo.txt';
        self::getXCQuestionList($baseUrl,10,$filePath);
        self::getXCQuestionInfo($filePath,'1631',null,'法国');
    }
    public function actionDeguo()
    {
        $baseUrl='http://you.ctrip.com/asks/germany100025-k1';
        $filePath=dirname(__FILE__)."/".'deguo.txt';
        self::getXCQuestionList($baseUrl,20,$filePath);
        self::getXCQuestionInfo($filePath,'1456',null,'德国');
    }
    public function actionYingguo()
    {
        $baseUrl='http://you.ctrip.com/asks/unitedkingdom20354-k1';
        $filePath=dirname(__FILE__)."/".'yingguo.txt';
        self::getXCQuestionList($baseUrl,8,$filePath);
        self::getXCQuestionInfo($filePath,'4011',null,'英国');
    }
    public function actionHelan()
    {
        $baseUrl='http://you.ctrip.com/asks/holland100028-k1';
        $filePath=dirname(__FILE__)."/".'helan.txt';
        self::getXCQuestionList($baseUrl,2,$filePath);
        self::getXCQuestionInfo($filePath,'2008',null,'荷兰');
    }
    public function actionRuishi()
    {
        $baseUrl='http://you.ctrip.com/asks/switzerland100050-k1';
        $filePath=dirname(__FILE__)."/".'ruishi.txt';
        self::getXCQuestionList($baseUrl,10,$filePath);
        self::getXCQuestionInfo($filePath,'3075',null,'瑞士');
    }
    public function actionAodaliya()
    {
        $baseUrl='http://you.ctrip.com/asks/australia100048-k1';
        $filePath=dirname(__FILE__)."/".'aodaliya.txt';
        self::getXCQuestionList($baseUrl,10,$filePath);
        self::getXCQuestionInfo($filePath,'1083',null,'澳大利亚');
    }
    public function actionRiben()
    {
        $baseUrl='http://you.ctrip.com/asks/japan100041-k1';
        $filePath=dirname(__FILE__)."/".'riben.txt';
        //self::getXCQuestionList($baseUrl,70,$filePath);
        self::getXCQuestionInfo($filePath,'3005',null,'日本');
    }
    public function actionHanguo()
    {
        $baseUrl='http://you.ctrip.com/asks/southkorea100042-k1';
        $filePath=dirname(__FILE__)."/".'hanguo.txt';
        self::getXCQuestionList($baseUrl,50,$filePath);
        self::getXCQuestionInfo($filePath,'1832',null,'韩国');
    }
    public function actionTaiguo()
    {
        $baseUrl='http://you.ctrip.com/asks/thailand100021-k1';
        $filePath=dirname(__FILE__)."/".'taiguo.txt';
        //self::getXCQuestionList($baseUrl,60,$filePath);
        self::getXCQuestionInfo($filePath,'3292',null,'泰国');
    }
    public function actionTaiwan()
    {
        $baseUrl='http://you.ctrip.com/asks/taiwan100076-k1';
        $filePath=dirname(__FILE__)."/".'taiwan.txt';
        //self::getXCQuestionList($baseUrl,60,$filePath);
        self::getXCQuestionInfo($filePath,'336','826','中国台湾');
    }
    public function actionMalaixiya()
    {
        $baseUrl='http://you.ctrip.com/asks/malaysia100022-k1';
        $filePath=dirname(__FILE__)."/".'malaixiya.txt';
        self::getXCQuestionList($baseUrl,20,$filePath);
        self::getXCQuestionInfo($filePath,'2512',null,'马来西亚');
    }


    /**
     * 获取携程问题列表页的URL
     * @param $basUrl
     * @param $maxPage
     * @param $filePath
     */
    private function getXCQuestionList($basUrl,$maxPage,$filePath)
    {

        echo '开始存储详情页URL '."\n";
        for($i=1;$i<=$maxPage;$i++)
        {
            $response=UrlUtil::get($basUrl."/p".$i.".html",[]);

            $html = new \simple_html_dom();
            $html->load($response);

            $html->find("ul[class=asklist]");
            foreach($html->find("li[class=cf]") as $element){
                $href=$element->getAttribute('href');
                if(empty($href)){
                    continue;
                }
                $href=$this->basePath.$href;
                file_put_contents($filePath,$href.",",FILE_APPEND);
            }
            $html->clear();
            echo '第'.$i.'页存储成功'."\n";
        }
        echo '存储详情页URL完毕 '."\n";
    }

    private function getXCQuestionInfo($filePath,$countryId,$cityId,$addr)
    {

        $errorList=[];

        $allUrl=file_get_contents($filePath);
        echo '开始循环存储详情页URL '."\n";
        $allUrlArray=explode(",",$allUrl);
        //echo count($allUrlArray);
        //echo count(array_unique($allUrlArray));exit;
        foreach($allUrlArray as $url)
        {
            $askList=[];
            if(empty($url)){
                continue;
            }
            echo '开始存储Url： '.$url."\n";
            try{
                $response=UrlUtil::get($url,[]);
            }catch (Exception $e){
                echo "获取URL：".$url."异常"."\n";
                $errorList[]=$url;
                continue;
            }
            //file_put_contents('temp.txt',$response);exit;

            $html = new \simple_html_dom();
            $html->load($response);


            //获取提问用户昵称和个人主页URL
            $userInfoUrl=$html->find('.ask_username')[0]->href;
            //获取提问标题
            $askTitle=trim($html->find('.ask_title')[0]->plaintext);
            //获取提问时间
            $askTime=trim($html->find('.ask_time')[0]->plaintext);
            $askTime=trim(str_replace('发表于','',$askTime));
            //获取提问内容
            $askContent=trim($html->find('#host_asktext')[0]->plaintext);
            //问答Id
            $askId=trim($html->find('#AskId')[0]->getAttribute('value'));
            //获取提问标签
            $askTags=[];
            foreach($html->find('.asktag_item') as $element){
                $tag=$element->getAttribute('title');
                if(empty($tag)){
                    continue;
                }
                if(!in_array($tag,$askTags)){
                    $askTags[]=$tag;
                }
            }

            $tempDate=self::parseTime($askTime);

            //只抓取2015年的数据
            if(date('Y',strtotime($tempDate))<2015){
                break;
            }

            $askUserInfo=self::getAskUserInfo($userInfoUrl);

            $rst=self::getAskFavourite($askId);

            $askInfo=[];$answerList=[];
            $askInfo['title']=$askTitle;
            $askInfo['createTime']=$askTime;
            $askInfo['content']=$askContent;
            $askInfo['title']=$askTitle;
            $askInfo['username']=$askUserInfo['name'];
            $askInfo['userImg']=$askUserInfo['img'];
            $askInfo['userId']=$askUserInfo['uid'];
            $askInfo['collectCount']=$rst;
            $askInfo['collectCount']=$rst;
            $askInfo['tags']=implode(',',$askTags);



            //获取最佳答案（不确定）
            if(count($html->find('.bestanswer_con'))>0){
                $userId=$html->find('.bestanswer_con')[0]->find('.answer_box')[0]->getAttribute('data-answeruserid');
                $username=$html->find('.bestanswer_con')[0]->find('.answer_id')[0]->plaintext;
                $userImgUrl=$html->find('.bestanswer_con')[0]->find('.answer_img')[0]->find('img')[0]->getAttribute('src');
                $answerTime=$html->find('.bestanswer_con')[0]->find('.answer_time')[0]->plaintext;
                $answerTime=trim(str_replace('发表于','',$answerTime));
                $answerContent=$html->find('.bestanswer_con')[0]->find('.answer_text')[0]->plaintext;
                $answerZan=$html->find('.bestanswer_con')[0]->find('.btn_answer_zan')[0]->find('span')[0]->plaintext;
                $answerZan=trim($answerZan);

                $temp=[];
                $temp['answerUserId']=$userId;
                $temp['answerUserName']=$username;
                $temp['answerUserImg']=$userImgUrl;
                $temp['answerTime']=$answerTime;
                $temp['answerContent']=$answerContent;
                $temp['answerZan']=$answerZan;
                $temp['answerType']=1;
                $answerList[]=$temp;
            }
            //提问者采纳（不确定）
            if(count($html->find('.youyouanswer_con'))>0){
                $userId=$html->find('.youyouanswer_con')[0]->find('.answer_box')[0]->getAttribute('data-answeruserid');
                $username=$html->find('.youyouanswer_con')[0]->find('.answer_id')[0]->plaintext;
                $userImgUrl=$html->find('.youyouanswer_con')[0]->find('.answer_img')[0]->find('img')[0]->getAttribute('src');
                $answerTime=$html->find('.youyouanswer_con')[0]->find('.answer_time')[0]->plaintext;
                $answerTime=trim(str_replace('发表于','',$answerTime));
                $answerContent=$html->find('.youyouanswer_con')[0]->find('.answer_text')[0]->plaintext;
                $answerZan=$html->find('.youyouanswer_con')[0]->find('.btn_answer_zan')[0]->find('span')[0]->plaintext;
                $answerZan=trim($answerZan);

                $temp=[];
                $temp['answerUserId']=$userId;
                $temp['answerUserName']=$username;
                $temp['answerUserImg']=$userImgUrl;
                $temp['answerTime']=$answerTime;
                $temp['answerContent']=$answerContent;
                $temp['answerZan']=$answerZan;
                $temp['answerType']=2;
                $answerList[]=$temp;
            }
            //其他回答
            if(count($html->find(".otheranswer_con"))>0){
                foreach($html->find(".otheranswer_con")[0]->find('li') as $element)
                {
                    if(count($element->find('.answer_box'))==0){
                        continue;
                    }
                    $userId=$element->find('.answer_box')[0]->getAttribute('data-answeruserid');
                    $username=$element->find('.answer_id')[0]->plaintext;
                    $userImgUrl=$element->find('.answer_img')[0]->find('img')[0]->getAttribute('src');
                    $answerTime=$element->find('.answer_time')[0]->plaintext;
                    $answerTime=trim(str_replace('发表于','',$answerTime));
                    $answerContent=$element->find('.answer_text')[0]->plaintext;
                    $answerZan=$element->find('.btn_answer_zan')[0]->find('span')[0]->plaintext;
                    $answerZan=trim($answerZan);

                    $temp=[];
                    $temp['answerUserId']=$userId;
                    $temp['answerUserName']=$username;
                    $temp['answerUserImg']=$userImgUrl;
                    $temp['answerTime']=$answerTime;
                    $temp['answerContent']=$answerContent;
                    $temp['answerZan']=$answerZan;
                    $temp['answerType']=3;
                    $answerList[]=$temp;
                }

            }
            $html->clear();

            $askInfo['answerList']=$answerList;
            $askList[]=$askInfo;

            self::saveAskList($askList,$countryId,$cityId,$addr);
        }
        echo '读取详情页URL完毕 '."\n";
        file_put_contents("error.txt",implode($errorList,","),FILE_APPEND);
    }


    private function saveAskList($askList,$countryId,$cityId,$addr)
    {
        $questionList=[];
        $answerList=[];
        $userList=[];
        $userBaseService=new UserBaseService();


        echo '开始循环保存问答内容 '."\n";

        foreach($askList as $askInfo)
        {
            $question=new QuestionCommunity();
            $question->qTitle=$askInfo['title'];
            $question->qCreateTime=self::parseTime($askInfo['createTime']);
            $question->qContent=$askInfo['content'];
            $question->attentionNumber=$askInfo['collectCount'];
            $question->qUserSign=$askInfo['userId'];
            $questionList[]=$question;
            $question->qCityId=$cityId;
            $question->qCountryId=$countryId;
            $question->qAddr=$addr;
            $question->qTag=$askInfo['tags'];
            $question->aNumber=0;

            $userBaseService->saveObject($question);
            $qId=$userBaseService->getLastInsertId();
            $question->qId=$qId;

            echo '存储问题成功('.$askInfo['title'].') '."\n";

            $key=$askInfo['userId'];
            if(!array_key_exists($key,$userList)){
                $user=new UserBase();
                $user->nickname=$askInfo['username'];
                $user->headImg=$askInfo['userImg'];
                $user->school= $askInfo['userId'];
                $user->password='qwe123';

                $userList[$key]=$user;
            }

            if(!empty($askInfo['answerList'])){
                foreach($askInfo['answerList'] as $temp)
                {
                    $answer=new AnswerCommunity();
                    $answer->aContent=$temp['answerContent'];
                    $answer->aCreateTime=self::parseTime($temp['answerTime']);
                    $answer->aUserSign=$temp['answerUserId'];
                    $answer->type=$temp['answerType'];
                    $answer->zan=$temp['answerZan'];
                    $answer->qId=$question->qId;
                    $answerList[]=$answer;
                    if(empty($answer->zan)){
                        $answer->zan=0;
                    }
                    $key=$temp['answerUserId'];

                    $userBaseService->saveObject($answer);

                    if(!array_key_exists($key,$userList)){
                        $user=new UserBase();
                        $user->nickname=$temp['answerUserName'];
                        $user->headImg=$temp['answerUserImg'];
                        $user->school= $temp['answerUserId'];
                        $user->password='qwe123';

                        $userList[$key]=$user;
                    }

                }
            }
            echo '存储答案成功（'.count($askInfo['answerList']).'） '."\n";


            $userCount=0;
            foreach($userList as $key=> $user)
            {
                $rst=$userBaseService->findObjectByType(UserBase::class,'school',$key);
                if(empty($rst)){
                    $userBaseService->addUser($user);
                    $userCount++;
                }
            }
            echo '存储用户成功（'.$userCount.'） '."\n";

        }
    }


    /**
     * 获取提问者详情
     * @param $url
     * @return array
     */
    private function getAskUserInfo($url)
    {
        $response=UrlUtil::get($this->basePath.urlencode($url),[]);
        $html = new \simple_html_dom();
        $html->load($response);
        $userName=$html->find('.info-name')[0]->plaintext;
        $userImg=$html->find('#infoAvatar')[0]->find('img')[0]->getAttribute('src');
        $uid=$html->find('#hdn_userid')[0]->getAttribute('value');
        $html->clear();
        $rst=[
            'name'=>$userName,
            'img'=>$userImg,
            'uid'=>$uid
        ];
        return $rst;
    }


    private function getAskFavourite($askId)
    {
        $url='http://you.ctrip.com/AskSite/Ajax/GetUserAndLikeAndShareEtcInfo';
        $response=UrlUtil::get($url,['AskId'=>$askId]);
        $response=substr($response,1,strlen($response)-2);
        $rst=json_decode($response,true);
        $count=0;
        if($rst['RetCode']==0){
            $count=$rst['FavouriteCount'];
        }
        return $count;
    }


    private function parseTime($time)
    {
        $time=trim($time);
        $date=$time;
        $time=str_replace("发表于",'',$time);

        if(strpos($time,'小时')){
            $time=str_replace("小时前",'',$time);
            $time=time()-(60*60*$time);
            $date=date('Y-m-d H:i:s',$time);
        }else if(strpos($time,'分钟')){
            $time=str_replace("分钟前",'',$time);
            $time=time()-(60*$time);
            $date=date('Y-m-d H:i:s',$time);
        }
        return $date;
    }









}
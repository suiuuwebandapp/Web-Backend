<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午6:18
 */

namespace frontend\controllers;



use common\components\Code;
use common\components\LogUtils;
use common\entity\AnswerCommunity;
use common\entity\QuestionCommunity;
use frontend\components\Page;
use frontend\services\QaCommunityService;
use frontend\services\TagListService;
use frontend\services\UserAttentionService;
use yii;
use yii\base\Exception;

class AppQaController extends AController {

    private $qaSer;//问答
    private $tagSer;
    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
        $this->qaSer=new QaCommunityService();
        $this->tagSer =new TagListService();
    }

    public function actionAddQuestion()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $title = Yii::$app->request->post('title');
            $content = Yii::$app->request->post('content');
            $addr = Yii::$app->request->post('addr');
            $countryId = Yii::$app->request->post('countryId');
            $cityId = Yii::$app->request->post('cityId');
            $tagList = Yii::$app->request->post('tags');
            $userList = Yii::$app->request->post('userList');
           /* $title ='测试004';
            $content = '测试004';
            $addr ='测试004';
            $countryId = 5;
            $cityId =221;
            $tagList = '1,3,4';
            $userList = '测试004';*/
            if(empty($title)){return json_encode(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($content)){return json_encode(Code::statusDataReturn(Code::FAIL, "内容不能为空"));}
            if(empty($addr)){return json_encode(Code::statusDataReturn(Code::FAIL, "地点不能为空"));}
            if(empty($countryId)){return json_encode(Code::statusDataReturn(Code::FAIL, "国家不能为空"));}
            if(empty($cityId)){return json_encode(Code::statusDataReturn(Code::FAIL, "城市不能为空"));}
            if(empty($tagList)){return json_encode(Code::statusDataReturn(Code::FAIL, "标签不能为空"));}
            if(empty($userList)){return json_encode(Code::statusDataReturn(Code::FAIL, "邀请回答人不能空"));}
            $question = new QuestionCommunity();
            $question->qTitle = $title;
            $question->qContent = $content;
            $question->qAddr = $addr;
            $question->qCountryId = $countryId;
            $question->qCityId = $cityId;
            $question->qTag = $tagList;
            $question->qInviteAskUser = $userList;
            $question->qUserSign = $userSign;

            $this->qaSer->addQuestion($question);

            return json_encode(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }

    public function actionAddAnswer()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $qId = Yii::$app->request->post('qId');
            $content = Yii::$app->request->post('content');
            if(empty($qId)){return json_encode(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($content)){return json_encode(Code::statusDataReturn(Code::FAIL, "内容不能为空"));}
            $answer = new AnswerCommunity();
            $answer->qId = $qId;
            $answer->aContent = $content;
            $answer->aUserSign = $userSign;
            $this->qaSer->addAnswer($answer);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"回答问题异常"));
        }

    }

    public function actionGetQaInfo()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return json_encode(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $id=Yii::$app->request->post("id");
            if(empty($id)){return json_encode(Code::statusDataReturn(Code::FAIL, "id不能为空"));}

            $rst =$this->qaSer->getQaInfoById($id,$userSign);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"回答问题异常"));
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
            $id = $this->tagSer->addTagList($name);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$id));
        }
        return json_encode(Code::statusDataReturn(Code::FAIL,"标签名称不能为空"));
    }

    /**得到问答社区系统标签
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetTag()
    {
        $tags =$this->tagSer->getQaSysTag();
        return json_encode(Code::statusDataReturn(Code::SUCCESS,$tags));
    }


    /**得到推荐回答用户
     * @return string
     */
    public function actionGetInviteUser()
    {
        $this->loginValid();
        try {
            $countryId = Yii::$app->request->post('countryId');
            $cityId = Yii::$app->request->post('cityId');
            if(empty($countryId)){return json_encode(Code::statusDataReturn(Code::FAIL, "国家不能为空"));}
            if(empty($cityId)){return json_encode(Code::statusDataReturn(Code::FAIL, "城市不能为空"));}
            $rst = $this->qaSer->getInviteUser($countryId,$cityId);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }

    public function actionGetQaList()
    {
        $this->loginValid();
        try {
            $countryId = Yii::$app->request->post('countryId');
            $cityId = Yii::$app->request->post('cityId');
            $tags = Yii::$app->request->post('tags');
            $sortName=Yii::$app->request->post('sortName');
            $search = Yii::$app->request->post('search');
            $page = new Page();
            if($sortName==1){
                $page->sortName='qCreateTime';
            }else
            {
                $page->sortName='pvNumber';
            }
            $page->sortType="DESC";
            $rst = $this->qaSer->getQaList($page,$countryId,$cityId,$tags,$search);
            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return json_encode(Code::statusDataReturn(Code::FAIL,"获取异常"));
        }
    }

}
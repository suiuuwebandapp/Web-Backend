<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/7/10
 * Time: 下午6:18
 */

namespace app\modules\v1\controllers;



use app\components\Page;
use app\modules\v1\services\QaCommunityService;
use app\modules\v1\services\TagListService;
use common\components\Code;
use common\components\LogUtils;
use app\modules\v1\entity\AnswerCommunity;
use app\modules\v1\entity\QuestionCommunity;
use common\entity\TagList;
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
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $title = Yii::$app->request->post('title');
            $content = Yii::$app->request->post('content');
            $addr = Yii::$app->request->post('addr');
            $countryId = Yii::$app->request->post('countryId');
            $cityId = Yii::$app->request->post('cityId');
            $tagList = Yii::$app->request->post('tags');
            $userList = Yii::$app->request->post('userList');
            if(empty($title)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($content)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "内容不能为空"));}
            if(empty($addr)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "地点不能为空"));}
            if(empty($countryId)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "国家不能为空"));}
            if(empty($cityId)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "城市不能为空"));}
            if(empty($tagList)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "标签不能为空"));}
            if(empty($userList)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "邀请回答人不能空"));}
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

            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }

    public function actionAddAnswer()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $qId = Yii::$app->request->post('qId');
            $content = Yii::$app->request->post('content');
            if(empty($qId)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "标题不能为空"));}
            if(empty($content)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "内容不能为空"));}
            $answer = new AnswerCommunity();
            $answer->qId = $qId;
            $answer->aContent = $content;
            $answer->aUserSign = $userSign;
            $this->qaSer->addAnswer($answer);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,""));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"回答问题异常"));
        }

    }

    public function actionGetQaInfo()
    {
        $this->loginValid();
        try {
            $userSign = $this->userObj->userSign;
            if (empty($userSign)) {
                return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "未知用户"));
            }
            $id=Yii::$app->request->get("id");
            if(empty($id)){return $this->apiReturn(Code::statusDataReturn(Code::FAIL, "id不能为空"));}

            $rst =$this->qaSer->getQaInfoById($id,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取问题详情异常"));
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
            $id = $this->tagSer->addTagList($name,TagList::TYPE_Q_A_USER);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$id));
        }
        return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"标签名称不能为空"));
    }

    /**得到问答社区系统标签
     * @return string
     * @throws Exception
     * @throws \Exception
     */
    public function actionGetTag()
    {
        $this->loginValid();
        $tags =$this->tagSer->getQaSysTag();
        return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$tags));
    }

    public function actionGetQaCountry()
    {
        $this->loginValid();
        try {
            $rst = $this->qaSer->getQaCountry();
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }
    public function actionGetQaCity()
    {
        $this->loginValid();
        try {
            $id = Yii::$app->request->get('countryId');
            $rst = $this->qaSer->getQaCity($id);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }
    /**得到推荐回答用户
     * @return string
     */
    public function actionGetInviteUser()
    {
        $this->loginValid();
        try {
            $countryId = Yii::$app->request->get('countryId');
            $cityId = Yii::$app->request->get('cityId');
            if(empty($countryId)){return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "国家不能为空"));}
            if(empty($cityId)){return $this->apiReturn(Code::statusDataReturn(Code::PARAMS_ERROR, "城市不能为空"));}
            $rst = $this->qaSer->getInviteUser($countryId,$cityId);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"提交问题异常"));
        }
    }

    public function actionGetQaList()
    {
        $this->loginValid();
        try {
            $countryId = Yii::$app->request->get('countryId');
            $cityId = Yii::$app->request->get('cityId');
            $tags = Yii::$app->request->get('tags');
            $sortName=Yii::$app->request->get('sortName');
            $search = Yii::$app->request->get('search');
            $page = new Page(Yii::$app->request);
            if($sortName==1){
                $page->sortName='qCreateTime';
            }else
            {
                $page->sortName='pvNumber';
            }
            $page->sortType="DESC";
            $rst = $this->qaSer->getQaList($page,$countryId,$cityId,$tags,$search);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取异常"));
        }
    }

    public function actionGetQaTitle()
    {
        $this->loginValid();
        try {
            $rst = $this->qaSer->getQuestionTitle();
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取用户旅图异常"));
        }
    }
    public function actionGetUserQa()
    {
        $this->loginValid();
        try {
            $userSign = Yii::$app->request->get('userSign');
            $page = new Page(Yii::$app->request);
            $rst = $this->qaSer->getUserQa($page,$userSign);
            return $this->apiReturn(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e) {
            LogUtils::log($e);
            return $this->apiReturn(Code::statusDataReturn(Code::FAIL,"获取用户旅图异常"));
        }
    }
}
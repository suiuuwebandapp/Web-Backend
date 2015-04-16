<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/15
 * Time : 下午2:42
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\components\Page;
use backend\entity\ArticleInfo;
use common\components\Code;
use common\models\ArticleInfoDb;
use common\models\BaseDb;
use yii\base\Exception;

class ArticleService extends BaseDb{

    private $articleDb;

    public function __construct()
    {
    }


    /**
     *
     * @param Page $page
     * @param $search
     * @param $status
     * @return array|Page
     * @throws Exception
     * @throws \Exception
     */
    public function getList(Page $page,$search,$status)
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn,"article_info");
            $page=$this->articleDb->getList($page,$search,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $page;
    }

    /**
     * 添加专栏文章
     * @param ArticleInfo $articleInfo
     * @throws Exception
     */
    public function addArticleInfo(ArticleInfo $articleInfo)
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn);
            $this->articleDb->addArticleInfo($articleInfo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 更新专栏文章
     * @param ArticleInfo $articleInfo
     * @throws Exception
     */
    public function updateArticleInfo(ArticleInfo $articleInfo)
    {
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn);
            $this->articleDb->updateArticleInfo($articleInfo);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 删除文章详情
     * @param $articleId
     * @throws Exception
     */
    public function deleteArticleInfoById($articleId)
    {
        if(empty($articleId)){
            throw new Exception(Code::INVALID_PARAM);
        }
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn);
            $this->articleDb->deleteById($articleId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }


    /**
     * 根据Id获取专栏详情
     * @param $articleId
     * @return int|mixed|null
     * @throws Exception
     * @throws \Exception
     */
    public function findById($articleId)
    {
        if(empty($articleId)){
            throw new Exception(Code::INVALID_PARAM);
        }
        $articleInfo=null;
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn);
            $articleInfo=$this->articleDb->findById($articleId);
            $articleInfo=$this->arrayCastObject($articleInfo,ArticleInfo::class);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
        return $articleInfo;
    }


    /**
     * 改变专栏状态
     * @param $articleId
     * @param $status
     * @throws Exception
     * @throws \Exception
     */
    public function changeStatus($articleId,$status)
    {
        if(empty($articleId)||empty($status)){
            throw new Exception(Code::INVALID_PARAM);
        }
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleInfoDb($conn);
            $this->articleDb->changeStatus($articleId,$status);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}
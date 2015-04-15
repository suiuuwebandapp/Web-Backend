<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/15
 * Time : 下午2:42
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\services;


use backend\entity\ArticleInfo;
use common\components\Code;
use common\models\ArticleDb;
use common\models\BaseDb;
use yii\base\Exception;

class ArticleService extends BaseDb{

    private $articleDb;

    public function __construct()
    {
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
            $this->articleDb = new ArticleDb($conn);
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
            $this->articleDb = new ArticleDb($conn);
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
        try {
            $conn = $this->getConnection();
            $this->articleDb = new ArticleDb($conn);
            $this->articleDb->deleteArticleInfoById($articleId);
        } catch (Exception $e) {
            throw $e;
        } finally {
            $this->closeLink();
        }
    }
}
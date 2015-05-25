<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/21
 * Time: 下午6:39
 */

namespace frontend\services;


use common\components\Code;
use common\models\BaseDb;
use common\models\WeChatNewsListDb;
use yii\base\Exception;

class WeChatNewsListService  extends BaseDb{

    public $weChatNewsListDb;
    function __construct()
    {

    }

    public function findNewsById($newsId)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatNewsListDb=new WeChatNewsListDb($conn);
            $value =  $this->weChatNewsListDb->findNewsById($newsId);
            $rstBodyUrl = $this->getBodyLink($newsId);
            $strItem   = '';
            $strItem .= '<item>
                    <Title><![CDATA[' . $value['nTitle'] . ']]></Title>
                    <Description><![CDATA[' . $value['nIntro'] . ']]></Description>
                    <PicUrl><![CDATA[' . $value['nCover'] . ']]></PicUrl>
                    <Url><![CDATA[' . $rstBodyUrl . ']]></Url>
                    </item>';
            return Code::statusDataReturn(Code::SUCCESS,$strItem,1);
        } catch (Exception $e) {
            throw new Exception('查询列表异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    public function getNewsInfoForId($id)
    {
        try {
            $conn = $this->getConnection();
            $this->weChatNewsListDb=new WeChatNewsListDb($conn);
            return $this->weChatNewsListDb->findNewsById($id);
        } catch (Exception $e) {
            throw new Exception('查询列表异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }
    /**
     * 获取内容链接
     * @param $id
     * @return string
     */
    private function getBodyLink($id)
    {
        if (is_numeric($id)) {

            return \Yii::$app->params['weChatUrl'] . "/we-chat/get-news-info?id=" . $id;
        } else {

            return '';
        }
    }
}
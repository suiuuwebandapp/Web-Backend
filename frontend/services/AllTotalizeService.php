<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/11
 * Time: 下午5:58
 */
namespace frontend\services;

use common\components\Code;
use common\entity\AllTotalize;
use common\models\AllTotalizeDb;
use common\models\BaseDb;
use yii\base\Exception;

class AllTotalizeService extends BaseDb
{

    private $allTotalizeDb;

    public function __construct()
    {

    }

    /**得到总数信息
     * @param AllTotalize $totalize
     * @return array|bool
     * @throws Exception
     */
    public function getTotalizeInfo(AllTotalize $totalize)
    {
        try {
            $conn = $this->getConnection();
            $this->allTotalizeDb = new AllTotalizeDb($conn);
           return $this->allTotalizeDb->findTotalize($totalize);
        } catch (Exception $e) {
            throw new Exception('获取总数异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    /**更新总数量
     * @param AllTotalize $totalize  实体
     * @param $isAdd 是否是添加
     * @return int
     * @throws Exception
     */
    public function updateTotalize(AllTotalize $totalize,$isAdd)
    {
        try {
            $conn = $this->getConnection();
            $this->allTotalizeDb = new AllTotalizeDb($conn);
            $totalizeEntity=$this->allTotalizeDb->findTotalize($totalize);
            if(empty($totalizeEntity)||$totalizeEntity==false){
                $nTotalize=new AllTotalize();
                return $this->allTotalizeDb->addTotalize($nTotalize);
            }
            $nTotalize =new AllTotalize();
            if($isAdd)
            {
                $n=$totalizeEntity->totalize;
                $n++;
                $nTotalize->totalize=$n;
                $nTotalize->tType=$totalizeEntity->tType;
                $nTotalize->rId=$totalizeEntity->rId;
            }else
            {
                $n=$totalizeEntity->totalize;
                $n--;
                if($n<1){
                    $n=0;
                }
                $nTotalize->totalize=$n;
                $nTotalize->tType=$totalizeEntity->tType;
                $nTotalize->rId=$totalizeEntity->rId;
            }
            $this->allTotalizeDb->updateTotalize($nTotalize);
        } catch (Exception $e) {
            throw new Exception('获取总数异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

    public function addTotalize(AllTotalize $totalize)
    {
        try {
            $conn = $this->getConnection();
            $this->allTotalizeDb = new AllTotalizeDb($conn);
            return $this->allTotalizeDb->addTotalize($totalize);
        } catch (Exception $e) {
            throw new Exception('添加总数异常', Code::FAIL, $e);
        } finally {
            $this->closeLink();
        }
    }

}
<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/12
 * Time : 下午4:37
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


use common\entity\ArticleInfo;
use common\entity\TravelTrip;
use yii\base\Exception;

class SphinxUtils {

    private $sphinxClient;


    private function initSphinxClient()
    {
        $this->sphinxClient=new \SphinxClient;
        $this->sphinxClient->setServer("127.0.0.1", 9312);
        $this->sphinxClient->setMaxQueryTime(30);
    }

    private function closeSphinxClient()
    {
        $this->sphinxClient->close();
    }


    public function queryTripIdList($search)
    {
        $this->initSphinxClient();

        $this->sphinxClient->setMatchMode(SPH_MATCH_PHRASE);
        $this->sphinxClient->SetArrayResult(false);                                       //是否将Matches的key用ID代替
        $this->sphinxClient->SetSelect ( "*" );
        $this->sphinxClient->setFieldWeights(['title'=>1000]);
        $this->sphinxClient->setSortMode(SPH_SORT_EXTENDED,'@relevance desc,@weight desc');
        $this->sphinxClient->SetFilter ( 'status', [TravelTrip::TRAVEL_TRIP_STATUS_NORMAL, $exclude=false] );     //设置属性过滤
        $res = $this->sphinxClient->query($search,'suiuu_trip'); #[宝马]关键字，[main]数据源source
        $err = $this->sphinxClient->GetLastError();
        $words=[];
        try{
            if($err!=''){
                throw new Exception($err);
            }

            $tripIds=[];
            if(!empty($res['matches'])&&count($res['matches'])>0){
                foreach($res['matches'] as $key=> $match){
                    $tripIds[]=$key;
                }
            }
            if(!empty($res['words'])&&count($res['words'])>0){
                foreach($res['words'] as $key=> $w){
                    $words[]=$key;
                }
            }
            $rst['tripIds']=$tripIds;
            $rst['words']=$words;
            return Code::statusDataReturn(Code::SUCCESS,$rst);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e);
        }finally{
            $this->closeSphinxClient();
        }
    }

    public function queryArticleIdList($search)
    {
        try{
            $this->initSphinxClient();

            $this->sphinxClient->SetArrayResult(false);                                       //是否将Matches的key用ID代替
            $this->sphinxClient->SetSelect ( "*" );
            $this->sphinxClient->setFieldWeights(['title'=>1000]);
            $this->sphinxClient->setFieldWeights(['name'=>1000]);
            $this->sphinxClient->setSortMode(SPH_SORT_EXTENDED,'@relevance desc,@weight desc');
            $this->sphinxClient->SetFilter ( 'status', [ArticleInfo::ARTICLE_STATUS_ONLINE, $exclude=false] );     //设置属性过滤
            $res = $this->sphinxClient->query($search,'suiuu_article'); #[宝马]关键字，[main]数据源source
            $err = $this->sphinxClient->GetLastError();
            $words=[];

            if($err!=''){
                throw new Exception($err);
            }

            $articleIds=[];
            if(!empty($res['matches'])&&count($res['matches'])>0){
                foreach($res['matches'] as $key=>$match){
                    $articleIds[]=$key;
                }
            }
            if(!empty($res['words'])&&count($res['words'])>0){
                foreach($res['words'] as $key=> $w){
                    $words[]=$key;
                }
            }
            $rst['articleIds']=$articleIds;
            $rst['words']=$words;
            return Code::statusDataReturn(Code::SUCCESS,$rst);
        }catch (Exception $e){
            return Code::statusDataReturn(Code::FAIL,$e);
        }finally{
            $this->closeSphinxClient();
        }
    }
}
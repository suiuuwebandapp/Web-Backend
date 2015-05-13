<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/12
 * Time : 下午5:45
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\controllers;


use backend\components\Page;
use common\components\Code;
use common\components\PageResult;
use common\components\SphinxUtils;
use frontend\services\ArticleService;
use frontend\services\TripService;
use yii\base\Exception;

class SearchController extends UnCController{



    public function __construct($id, $module = null)
    {
        parent::__construct($id, $module);
    }


    public function actionIndex()
    {
        $search=\Yii::$app->request->get("s");

        return $this->render("list",[
            'search'=>$search
        ]);

    }


    public function actionSearch()
    {
        $search=\Yii::$app->request->post("s");
        $tp=\Yii::$app->request->post("tp",1);
        $ap=\Yii::$app->request->post("ap",1);

        try{

            $sphinxUtils=new SphinxUtils();
            $articleRst=$sphinxUtils->queryArticleIdList($search);
            $tripRst=$sphinxUtils->queryTripIdList($search);

            $articleIdStr='';
            $tripIdStr='';

            $articleList=null;
            $tripList=null;

            $words='';
            if($articleRst['status']==Code::SUCCESS){
                if(count($articleRst['data']['articleIds'])>0){
                    $articleIdStr=implode(",",$articleRst['data']['articleIds']);
                }
                $words=$articleRst['data']['words'];
            }
            if($tripRst['status']==Code::SUCCESS){
                if(count($tripRst['data']['tripIds'])>0) {
                    $tripIdStr=implode(",",$tripRst['data']['tripIds']);
                }
            }
            $articlePage=new Page();
            $articlePage->currentPage=$ap;
            $articlePage->pageSize=8;


            if(!empty($articleIdStr)){

                $articleService=new ArticleService();
                $articlePage=$articleService->getArticleListBySearch($articlePage,$articleIdStr);
                $articlePage->setList($this->sortArticleResult($articleIdStr,$articlePage->getList()));

            }

            $tripPage=new Page();
            $tripPage->currentPage=$tp;
            $tripPage->pageSize=8;

            if(!empty($tripIdStr)){

                $tripService=new TripService();
                $tripPage=$tripService->getListBySearch($tripPage,$tripIdStr);
                $tripPage->setList($this->sortTripResult($tripIdStr,$tripPage->getList()));
            }

            $articleResult=new PageResult($articlePage);

            $tripResult=new PageResult($tripPage);

            $rst=[
                'articleResult'=>$articleResult,
                'tripResult'=>$tripResult,
                'words'=>$words
            ];

            return json_encode(Code::statusDataReturn(Code::SUCCESS,$rst));
        }catch (Exception $e){
            return json_encode(Code::statusDataReturn(Code::FAIL));
        }
    }


    public function sortTripResult($ids,$list)
    {
        $listMap=[];
        $newList=[];
        foreach($list as $trip){
            $listMap[$trip['tripId']]=$trip;
        }
        $idArray=explode(",",$ids);
        foreach($idArray as $id){
            $newList[]=$listMap[$id];
        }
        return $newList;
    }

    public function sortArticleResult($ids,$list)
    {
        $listMap=[];
        $newList=[];
        foreach($list as $article){
            $listMap[$article['articleId']]=$article;
        }
        $idArray=explode(",",$ids);
        foreach($idArray as $id){
            $newList[]=$listMap[$id];
        }
        return $newList;
    }

}
<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午3:16
 * Email: zhangxinmailvip@foxmail.com
 */

namespace frontend\components;


use yii\web\Request;

class Page {

    public $currentPage = 1;
    public $startRow = 0;
    public $pageSize = 10;
    public $sortName;
    public $sortType = "asc";

    public $totalCount = 0;
    public $showAll = false;


    private $list = [];


    public function __construct(Request $request=null)
    {
        if($request!=null){
            $length = $request->post("s");
            $cPage = $request->post("p");
            $oColumn = $request->post("o");
            $oType = $request->post("t");
            $isShowAll = $request->post("showAll");

            if (!empty($length)) {
                $this->pageSize = $length;
            }
            if (!empty($start)) {
                $this->startRow = $start;
            }
            if (!empty($isShowAll) && $isShowAll != "false"&&$isShowAll!=false) {
                $this->showAll = true;
            }
            if (!empty($cPage)) {
                $this->currentPage = $cPage;
                $this->startRow = (($this->currentPage - 1) * $this->pageSize);
            }
            if (!empty($oColumn)) {
                $this->sortName = $oColumn;
            }
            if (!empty($oType)) {
                $this->sortType = $oType;
            }
        }
    }


    public function getList(){
        return $this->list;
    }

    public function setList($rst){
        if($rst!==false){
            $this->list=$rst;
        }
    }
}
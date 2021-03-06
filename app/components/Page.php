<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/4
 * Time : 下午3:16
 * Email: zhangxinmailvip@foxmail.com
 */

namespace app\components;


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
            $length = $request->get("number");//每页显示多少条
            $cPage = $request->get("page");//开始页
            $oColumn = $request->get("o");//排序的列
            $oType = $request->get("t");//排序的顺序
            $isShowAll = $request->get("showAll");
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

    public function initPage($currentPage,$pageSize)
    {
        $this->currentPage=$currentPage;
        $this->pageSize=$pageSize;
        $this->startRow = (($currentPage - 1) * $pageSize);
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
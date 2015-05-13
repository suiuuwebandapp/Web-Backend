<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/15
 * Time : 下午4:26
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\components;

use \Yii\web\Request;
class Page
{

    public $draw;
    public $currentPage = 1;
    public $startRow = 0;
    public $pageSize = 10;
    public $sortName;
    public $sortType = "asc";

    public $totalCount = 0;
    public $showAll = false;


    public $list = [];


    public function __construct(Request $request=null)
    {
        if($request!=null){
            $draw = $request->post("draw");
            $start = $request->post("start");
            $length = $request->post("length");
            $oColumn = $request->post("order[0][column]");
            $oType = $request->post("order[0][dir]");
            $isShowAll = $request->post("showAll");

            $cPage = $request->get("currentPage");

            if (!empty($draw)) {
                $this->draw = $draw;
            }
            if (!empty($length)) {
                $this->pageSize = $length;
            }
            if (!empty($start)) {
                $this->startRow = $start;
            }
            if (!empty($isShowAll) && $isShowAll != "false"&&$isShowAll!=false) {
                $this->showAll = true;
            }


            $this->currentPage = ($this->startRow / $this->pageSize + 1);

            if (!empty($cPage)) {
                $this->currentPage = $cPage;
                $this->startRow = (($this->currentPage - 1) * $this->pageSize);
            }
            if (!empty($oColumn)) {
                $orderColumn = $oColumn;
                $orderKey = "columns[" . $orderColumn . "][data]";
                $this->sortName = $request->post($orderKey);
            }
            if (!empty($oType)) {
                $this->sortType = $oType;
            }
        }
    }


    public function setCurrentPage($page)
    {
        $this->currentPage=$page;
        $this->startRow = (($this->currentPage - 1) * $this->pageSize);
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
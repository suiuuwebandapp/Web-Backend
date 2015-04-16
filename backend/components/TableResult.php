<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/16
 * Time : 下午1:15
 * Email: zhangxinmailvip@foxmail.com
 */

namespace backend\components;


class TableResult
{

    public $draw = 1;
    public $recordsTotal = 0;
    public $recordsFiltered = 0;
    public $data=[];


    public function __construct($draw, $recordsTotal,$recordsFiltered, Array $data)
    {
        $this->draw=$draw;
        $this->recordsTotal=$recordsTotal;
        $this->recordsFiltered=$recordsFiltered;
        $this->data=$data;
    }

}
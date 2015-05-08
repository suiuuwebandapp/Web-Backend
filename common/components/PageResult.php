<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/5/8
 * Time : 下午4:38
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


use backend\components\Page;

class PageResult {

    public $currentPage;

    public $pageSize;

    public $totalCount;

    public $totalPage;

    public $result;

    public $pageHtml;


    public function __construct(Page $page)
    {
        $this->currentPage=$page->currentPage;

        $this->pageSize=$page->pageSize;

        $this->totalCount=$page->totalCount;

        $this->result=$page->getList();

        $this->totalPage=ceil($this->totalCount/$this->pageSize);

    }

    public function init($cPage,$pSize,$tCount,$rst)
    {
        $this->currentPage=$cPage;

        $this->pageSize=$pSize;

        $this->totalCount=$tCount;

        $this->result=$rst;

        $this->totalPage=ceil($this->totalCount/$this->pageSize);
    }


}
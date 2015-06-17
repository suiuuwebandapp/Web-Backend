<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/3/30
 * Time: 下午5:48
 */

?>


<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper">
    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
    <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
    <div class="page-sidebar navbar-collapse collapse">
        <!-- BEGIN SIDEBAR MENU -->
        <ul id="left_menu_ul" class="page-sidebar-menu page-sidebar-menu-light" data-auto-scroll="true" data-slide-speed="200">
            <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
            <li class="sidebar-toggler-wrapper">
                <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
                <div class="sidebar-toggler">
                </div>
                <!-- END SIDEBAR TOGGLER BUTTON -->
            </li>
            <!-- DOC: To remove the search box from the sidebar you just need to completely remove the below "sidebar-search-wrapper" LI element -->
            <li class="sidebar-search-wrapper">
                <!-- BEGIN RESPONSIVE QUICK SEARCH FORM -->
                <!-- DOC: Apply "sidebar-search-bordered" class the below search form to have bordered search box -->
                <!-- DOC: Apply "sidebar-search-bordered sidebar-search-solid" class the below search form to have bordered & solid search box -->
                <form class="sidebar-search " action="extra_search.html" method="POST">
                    <a href="javascript:;" class="remove">
                        <i class="icon-close"></i>
                    </a>

                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search...">
						<span class="input-group-btn">
						<a href="javascript:;" class="btn submit"><i class="icon-magnifier"></i></a>
						</span>
                    </div>
                </form>
                <!-- END RESPONSIVE QUICK SEARCH FORM -->
            </li>
<!--            <li class="start active menu_top">-->
<!--                <a href="#~/index/dashboard" class="left_menu_link">-->
<!--                    <i class="icon-home"></i>-->
<!--                    <span class="title">控制面板</span>-->
<!--                    <span class="selected"></span>-->
<!--                </a>-->
<!--            </li>-->
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-settings"></i>
                    <span class="title"> 系统设置</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
<!--                    <li>-->
<!--                        <a href="#~/sys-user/list"  class="left_menu_link"> <i class="icon-home"></i>  员工管理</a>-->
<!--                    </li>-->
                    <li>
                        <a href="#~/country/to-country-list"> <i class="icon-pointer"></i> 国家城市配置</a>
                    </li>
<!--                    <li>-->
<!--                        <a href="ecommerce_orders_view.html"> <i class="icon-tag"></i> Order View</a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="ecommerce_products.html"> <i class="icon-handbag"></i> Products</a>-->
<!--                    </li>-->
<!--                    <li>-->
<!--                        <a href="ecommerce_products_edit.html">  <i class="icon-pencil"></i> Product Edit</a>-->
<!--                    </li>-->
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-user"></i>
                    <span class="title"> 用户管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/user-base/to-user-list"> 普通用户</a>
                    </li>
                    <li>
                        <a href="#~/user-base/to-wechat-user-list">微信用户</a>
                    </li>
                    <li>
                        <a href="index_horizontal_menu.html"> 随友管理</a>
                    </li>
                    <li>
                        <a href="#~/user-base/to-add-publisher"> 添加随友</a>
                    </li>
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-pointer"></i>
                    <span class="title"> 目的地管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li class="default_menu">
                        <a href="#~/destination/to-des-list"> 目的地列表</a>
                    </li>
                </ul>
            </li>

            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-rocket"></i>
                    <span class="title"> 专栏管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/article/add"> 添加专栏</a>
                    </li>
                    <li>
                        <a href="#~/article/list"  class="left_menu_link"> 专栏列表</a>
                    </li>
                    <li>
                        <a href="#~/article/comment-list"  class="left_menu_link"> 专栏评论</a>
                    </li>
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-basket"></i>
                    <span class="title"> 随游管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/trip/list"> 随游列表</a>
                    </li>
                    <li>
                        <a href="#~/trip/comment-list"  class="left_menu_link"> 随游评论</a>
                    </li>
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-basket"></i>
                    <span class="title"> 订单管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/trip-order/list"> 订单列表</a>
                    </li>
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-globe"></i>
                    <span class="title"> 微信消息</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/wechat-news/show-add"> 添加消息</a>
                    </li>
                    <li>
                        <a href="#~/wechat-news/list"  class="left_menu_link"> 消息列表</a>
                    </li>
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-camera"></i>
                    <span class="title"> 微信定制</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/wechat-order/list"> 定制列表</a>
                    </li>
                    <li>
                        <a href="#~/wechat-order-refund/list"  class="left_menu_link"> 退款列表</a>
                    </li>
                    <li>
                        <a href="#~/wechat-order-pay/list"  class="left_menu_link"> 支付列表</a>
                    </li>
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-diamond"></i>
                    <span class="title"> 推荐管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/recommend-list/list"> 推荐列表</a>
                    </li>
                </ul>
            </li>
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-present"></i>
                    <span class="title"> 圈子管理</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="#~/circle/list">圈子列表</a>
                    </li>
                    <li>
                        <a href="#~/circle/article-list">文章列表</a>
                    </li>
                    <li>
                        <a href="#~/circle/comment-list">评论列表</a>
                    </li>
                </ul>

            </li>
            <!--
            <li class="menu_top">
                <a href="javascript:;">
                    <i class="icon-folder"></i>
                    <span class="title">Multi Level Menu</span>
                    <span class="arrow"></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="javascript:;">
                            <i class="icon-settings"></i> Item 1 <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li class="open">
                                <a href="javascript:;">
                                    <i class="icon-user"></i>
                                    Sample Link 1 <span class="arrow"></span>
                                </a>
                                <ul class="sub-menu">
                                    <li>
                                        <a href="#~/sys-user/list"><i class="icon-power"></i> 随游游</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="icon-paper-plane"></i> Sample Link 1</a>
                                    </li>
                                    <li>
                                        <a href="#"><i class="icon-star"></i> Sample Link 1</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#"><i class="icon-camera"></i> Sample Link 1</a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-link"></i> Sample Link 2</a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-pointer"></i> Sample Link 3</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:;">
                            <i class="icon-globe"></i> Item 2 <span class="arrow"></span>
                        </a>
                        <ul class="sub-menu">
                            <li>
                                <a href="#~/sys-user/list"><i class="icon-power"></i> 随游游</a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-tag"></i> Sample Link 1</a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-pencil"></i> Sample Link 1</a>
                            </li>
                            <li>
                                <a href="#"><i class="icon-graph"></i> Sample Link 1</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="icon-bar-chart"></i>
                            Item 3 </a>
                    </li>
                </ul>
            </li>

            -->
<!--            <li class="last menu_top">-->
<!--                <a href="charts.html">  <i class="icon-bar-chart"></i> <span class="title">Visual Charts</span> </a>-->
<!--            </li>-->
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
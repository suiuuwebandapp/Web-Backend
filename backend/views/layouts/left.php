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
        <ul class="page-sidebar-menu page-sidebar-menu-light" data-auto-scroll="true" data-slide-speed="200">
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
            <li class="start active ">
                <a href="index.html">
                    <i class="icon-home"></i>
                    <span class="title">Dashboard</span>
                    <span class="selected"></span>
                </a>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="icon-basket"></i>
                    <span class="title">eCommerce</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="ecommerce_index.html"> <i class="icon-home"></i>  Dashboard</a>
                    </li>
                    <li>
                        <a href="ecommerce_orders.html"> <i class="icon-basket"></i> Orders</a>
                    </li>
                    <li>
                        <a href="ecommerce_orders_view.html"> <i class="icon-tag"></i> Order View</a>
                    </li>
                    <li>
                        <a href="ecommerce_products.html"> <i class="icon-handbag"></i> Products</a>
                    </li>
                    <li>
                        <a href="ecommerce_products_edit.html">  <i class="icon-pencil"></i> Product Edit</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="icon-rocket"></i>
                    <span class="title">Page Layouts</span>
                    <span class="arrow "></span>
                </a>
                <ul class="sub-menu">
                    <li>
                        <a href="layout_horizontal_sidebar_menu.html"> Horizontal & Sidebar Menu</a>
                    </li>
                    <li>
                        <a href="index_horizontal_menu.html"> Dashboard & Mega Menu</a>
                    </li>
                    <li>
                        <a href="layout_horizontal_menu1.html">  Horizontal Mega Menu 1</a>
                    </li>
                   <li>
                        <a href="layout_horizontal_menu2.html">  Horizontal Mega Menu 2</a>
                    </li>
                    <li>
                        <a href="layout_fontawesome_icons.html">  <span class="badge badge-roundless badge-danger">new</span>Layout with Fontawesome Icons</a>
                    </li>
                    <li>
                        <a href="layout_glyphicons.html"> Layout with Glyphicon</a>
                    </li>
                    <li>
                        <a href="layout_full_height_portlet.html">  <span class="badge badge-roundless badge-success">new</span>Full Height Portlet</a>
                    </li>
                    <li>
                        <a href="layout_full_height_content.html">  <span class="badge badge-roundless badge-warning">new</span>Full Height Content</a>
                    </li>
                    <li>
                        <a href="layout_search_on_header1.html"> Search Box On Header 1</a>
                    </li>
                    <li>
                        <a href="layout_search_on_header2.html"> Search Box On Header 2</a>
                    </li>
                    <li>
                        <a href="layout_sidebar_search_option1.html"> Sidebar Search Option 1</a>
                    </li>
                    <li>
                        <a href="layout_sidebar_search_option2.html"> Sidebar Search Option 2</a>

                    </li>
                </ul>
            </li>

            <li class="last ">
                <a href="charts.html">  <i class="icon-bar-chart"></i> <span class="title">Visual Charts</span> </a>
            </li>
        </ul>
        <!-- END SIDEBAR MENU -->
    </div>
    <!-- END SIDEBAR -->
<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/10
 * Time : 下午4:57
 * Email: zhangxinmailvip@foxmail.com
 */
?>

<link rel="stylesheet" type="text/css" href="/assets/global/plugins/select2/select2_metro.css">

<div class="clearfix"></div>
<!-- 配置文件 -->
<script type="text/javascript" src="/assets/global/plugins/ueditor/ueditor.config.js"></script>
<!-- 编辑器源码文件 -->
<script type="text/javascript" src="/assets/global/plugins/ueditor/ueditor.all.js"></script>
<!-- 实例化编辑器 -->


<div class="row">
    <div class="col-md-12">
        <!-- BEGIN SAMPLE TABLE PORTLET-->
        <div class="portlet light bg-inverse">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-list font-red-sunglo"></i>
                    <span class="caption-subject font-red-sunglo bold uppercase">添加专栏文章</span>
                    <span class="caption-helper"> 添加专栏文章基本信息 </span>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->
                <form id="form_validate" action="${base}/sys/movieInfo/add" class="form-horizontal" method="post" isSubmit="true">
                    <div class="form-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label">标题<span class="required">*</span></label>
                            <div class="col-md-4 valdate">
                                <div class="input-icon right">
                                    <i class="fa"></i>
                                    <input type="text" name="chineseName" value="" class="form-control" placeholder="请输入文章标题" maxlength="20"  required/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">名称<span class="required">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="englishName" value="" class="form-control" placeholder="请输入文章名称" maxlength="20" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">封面图<span class="required">*</span></label>
                            <div class="col-md-4">
                                <input type="text" name="englishName" value="" class="form-control" placeholder="请输入英文名称" maxlength="20" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">国家&nbsp;</label>
                            <div class="col-md-4">
                                <select id="kindIds" name="kindIds" class="form-control muti_select" placeholder=" 请选电影分类"  required>
                                        <option value=""></option>
                                        <option value="0">中国</option>
                                        <option value="1">日本</option>
                                        <option value="2">韩国</option>

                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label">城市&nbsp;</label>
                            <div class="col-md-4">
                                <select id="kindIds" name="tagIds" class="form-control muti_select" placeholder=" 请选电影分类"  required>
                                        <option value=""></option>
                                        <option value="0">北京</option>
                                        <option value="1">上海</option>
                                        <option value="2">郑州</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label">文章内容<span class="required">*</span></label>
                            <div class="col-md-6">
                                <script id="container" name="content" type="text/plain">
                                    这里写你的初始化内容
                                </script>
                            </div>
                        </div>

                    </div>
                    <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                            <button type="submit" class="btn green-meadow">&nbsp;&nbsp;添加影片&nbsp;&nbsp;</button>
                        </div>
                    </div>
                </form>
                <!-- END FORM-->
            </div>
        </div>
        <!-- END SAMPLE TABLE PORTLET-->
    </div>
</div>

<script type="text/javascript" src="/assets/global/plugins/select2/select2.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/select2/select2_locale_zh-CN.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/dist/jquery.validate.min.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/dist/additional-methods.min.js"></script>
<script type="text/javascript" src="/assets/admin/pages/scripts/form-validation.js"></script>
<script type="text/javascript" src="/assets/global/plugins/jquery-validation/localization/messages_zh.js" ></script>

<script type="text/javascript">
    var ue = UE.getEditor('container');
</script>
<script type="text/javascript">
    var parentMain=window.parent.Main;
    $(document).ready(function() {
        FormValidation.init();
        $('.muti_select').select2({
            allowClear: true
        });
    });

</script>

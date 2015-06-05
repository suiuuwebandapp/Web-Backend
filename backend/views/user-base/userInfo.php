<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/6/5
 * Time : 下午1:30
 * Email: zhangxinmailvip@foxmail.com
 */
?>


<!DOCTYPE html>
<head>
</head>
<body>
<form onsubmit="return false"  id=form_validate class="form-horizontal" novalidate="novalidate">
    <div class="modal-dialog" style="width: 700px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-hidden="false"></button>
                <h4 class="modal-title">
                    用户详情
                </h4>
            </div>
            <div class="modal-body">
                <div class="scroller" style="height: 400px" data-always-visible="1" data-rail-visible1="1">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="portlet-body form">
                                <!-- BEGIN FORM-->
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">昵称:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">Bob</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">性别:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">Nilson</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">手机:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">Bob</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">邮箱:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">Nilson</p>
                                                </div>
                                            </div>
                                        </div>
                                        <!--/span-->
                                    </div>
                                    <h3 class="form-section">Address</h3>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Address:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">
                                                        #24 Sun Park Avenue, Rolton Str
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label col-md-4">Address:</label>
                                                <div class="col-md-8">
                                                    <p class="form-control-static">
                                                        #24 Sun Park Avenue, Rolton Str
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- END FORM-->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="modal_close" data-dismiss="modal" class="btn default">
                    关闭
                </button>
            </div>
        </div>
    </div>
</form>
<!-- END PAGE LEVEL STYLES -->

<script type="text/javascript">
    jQuery(document).ready(function() {
    });

</script>
</body>
</html>

<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/ueditor/ueditor.config.js"></script>
<script type="text/javascript" src="<?=Yii::$app->params['res_url'] ?>/assets/global/plugins/ueditor/ueditor.all.min.js?<?=time().rand(100,999)?>"></script>
<style type="text/css">.edui-dialog {margin-top: 50px !important; }</style>
<a id="ueModal"  data-target="#ueModalDiv" data-toggle="modal"></a>
<div class="modal fade modal-scrollable modal-scroll" id="ueModalDiv" tabindex="-1" role="basic" aria-hidden="true" style="z-index: 999;">
    <div class="modal-dialog" style="width: 800px;margin-top: 50px;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="false"></button>
                <h4 class="modal-title">请输入富文本内容</h4>
            </div>
            <div class="scroller" style="height: 500px" data-always-visible="1" data-rail-visible1="1">
                <script id="container" name="content" type="text/plain" style="height:100%"></script>
            </div>
            <div class="modal-footer">
                <button type="submit" onclick="UEditorUtil.saveUEditorContent()" callBackFun="" class="btn green-meadow">保存</button>
                <button type="button" id="modal_close" data-dismiss="modal" class="btn default">关闭</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var ue="";
    (function(){
        //初始化ueditor
        ue= UE.getEditor('container');
        UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;
        UE.Editor.prototype.getActionUrl = function(action) {
            if (action == 'uploadimage' || action == 'uploadscrawl' || action == 'uploadimage') {
                return '/upload/upload-content-img';
            } else if (action == 'uploadvideo') {
                return 'http://a.b.com/video.php';
            } else {
                return this._bkGetActionUrl.call(this, action);
            }
        }
    })(jQuery);


    var UEditorUtil=function(){
        var jqueryObj='';
        var attribute='';
        return {
            showUEditor:function(html,attr,obj){
                html=html==undefined?'':html;
                ue.setContent(html);
                jqueryObj=obj;
                attribute=attr;
                $("#ueModal").click();
            },
            saveUEditorContent:function(){
                var html=ue.getContent();
                $(jqueryObj).attr(attribute,html);
                $("#modal_close").click();
            }
        }
    }();

</script>

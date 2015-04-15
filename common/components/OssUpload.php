<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/13
 * Time : 上午10:07
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;

require_once '../../vendor/oss/sdk.class.php';


class OssUpload
{

    /**
     * 默认Bucket
     */
    const OSS_SUIUU_BUCKET='suiuu';

    /**
     * 用户头像等图片
     */
    const OSS_SUIUU_HEAD_DIR='suiuu_head';

    /**
     * 专栏，目的地等图片
     */
    const OSS_SUIUU_CONTENT_DIR='suiuu_content';


    public $oss_sdk_service;

    private $oss_key_id;

    private $oss_key_secret;

    private $oss_host_image;

    public function __construct()
    {

        $this->oss_key_id=\Yii::$app->params['yun_oss_key_id'];
        $this->oss_key_secret=\Yii::$app->params['yun_oss_key_secret'];
        $this->oss_host_image=\Yii::$app->params['yun_oss_host_image'];

        $this->oss_sdk_service = new \ALIOSS($this->oss_key_id,$this->oss_key_secret,$this->oss_host_image);

        //设置是否打开curl调试模式
        //$this->oss_sdk_service->set_debug_mode(TRUE);
        $this->oss_sdk_service->set_enable_domain_style(TRUE);
        $this->oss_sdk_service->set_vhost("image.suiuu.com");
    }


    /**
     * 上传到Yun Oss 文件
     *
     * @param $filePath
     * @param $dir
     * @param $name
     * @return array
     * @throws \OSS_Exception
     */
    public function putObject($filePath,$dir,$name)
    {

        $bucket = self::OSS_SUIUU_BUCKET;
        $object = $dir.'/'.$name;
        $file_path = $filePath;
        $response = $this->oss_sdk_service->upload_file_by_file($bucket, $object, $file_path);
        if($response->status==200){
            return Code::statusDataReturn(Code::SUCCESS,$response->header['_info']['url']);
        }else{
            return Code::statusDataReturn(Code::FAIL,Code::UPLOAD_YUN_OSS_ERROR);
        }
    }


//格式化返回结果
    function _format($response) {
        echo '|-----------------------Start---------------------------------------------------------------------------------------------------'."\n";
        echo '|-Status:' . $response->status . "\n";
        echo '|-Body:' ."\n";
        echo $response->body . "\n";
        echo "|-Header:\n";
        print_r ( $response->header );
        echo '-----------------------End-----------------------------------------------------------------------------------------------------'."\n\n";
    }

}
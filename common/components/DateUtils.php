<?php
/**
 * Created by PhpStorm.
 * User : xin.zhang
 * Date : 15/4/24
 * Time : 上午9:47
 * Email: zhangxinmailvip@foxmail.com
 */

namespace common\components;


class DateUtils {


    /**
     * 将日期 4:50 PM 转换成 24小时制 16:50
     * @param $type  （1. 12转24   2. 24转12 ）
     * @param $time
     * @return null
     */
    public static function convertTimePicker($time,$type=1)
    {
        if(empty($time)){
            return null;
        }
        $rst=null;
        if($type==1){
            $timeArr=explode(" ",$time);
            $timeInfo=explode(":",$timeArr[0]);

            if($timeArr[1]=="AM"){
                $rst=$timeArr[0];
            }else{
                $rst=($timeInfo[0]+12).":".$timeInfo[1];
            }
        }else{
            $timeInfo=explode(":",$time);
            if($timeInfo[0]>12){
                $rst=($timeInfo[0]-12).":".$timeInfo[1]." PM";
            }else{
                $rst=$timeInfo[0].":".$timeInfo[1]." AM";
            }
        }
        return $rst;

    }

    public static function convertBirthdayToAge($birthday)
    {
        if(empty($birthday)||$birthday=='0000-00-00'){
            return "保密";
        }
        //$nowYear=date('Y',time());
        $birYear=date('y',strtotime($birthday));
        return $birYear[0]."0后";
        //return $nowYear-$birYear;
    }


}
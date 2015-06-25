<?php
namespace common\components;
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/4/19
 * Time: 下午3:08
 */

class Common{

    private $_pinyins = array(
        176161 => 'A',
        176197 => 'B',
        178193 => 'C',
        180238 => 'D',
        182234 => 'E',
        183162 => 'F',
        184193 => 'G',
        185254 => 'H',
        187247 => 'J',
        191166 => 'K',
        192172 => 'L',
        194232 => 'M',
        196195 => 'N',
        197182 => 'O',
        197190 => 'P',
        198218 => 'Q',
        200187 => 'R',
        200246 => 'S',
        203250 => 'T',
        205218 => 'W',
        206244 => 'X',
        209185 => 'Y',
        212209 => 'Z',
    );
    private $_charset = null;
    /**
     * 构造函数, 指定需要的编码 default: utf-8
     * 支持utf-8, gb2312
     *
     * @param unknown_type $charset
     */
    public function __construct( $charset = 'utf-8' )
    {
        $this->_charset    = $charset;
    }

    public static function CurlHandel($url,$data=null, $header = array(), $type = 'POST')
    {
        if($header==null){
            $header=array();
        }
        array_push($header, 'Accept:application/json');
        array_push($header, 'Content-Type:application/json');

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        //curl_setopt($ch, $method, 1);

        switch ($type) {
            case "GET" :
                curl_setopt($ch, CURLOPT_HTTPGET, true);
                break;
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                break;
            case "PUT" :
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
        }

        curl_setopt($ch, CURLOPT_USERAGENT, 'SSTS Browser/1.0');
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        if (! empty ( $data )) {
            $options = json_encode ( $data );
            curl_setopt ( $ch, CURLOPT_POSTFIELDS, $options ); // Post提交的数据包
        }
        if (count($header) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }

        $ret = curl_exec($ch);
        $err = curl_error($ch);

        curl_close($ch);

        if ($err) {
            return Code::statusDataReturn(Code::FAIL,$err);
        }
        if (empty($ret)) {
            return Code::statusDataReturn(Code::FAIL);
        }
        return Code::statusDataReturn(Code::SUCCESS, $ret);
    }

    public static function PageResult($page,$count=0)
    {

        $result='';
        if($count==0)
        {
        $pageCount =\Yii::$app->params['pageCount'];
        }else{
            $pageCount= $count;
        }
        if($page!=0)
        {
            $start=intval($page-1)*$pageCount;
            $result='LIMIT '.$start.', '.$pageCount;
        }

        return $result;


    }

    /**
     * @param $nowPage 第几页
     * @param $pageCount 每页几条
     * @param $allCount 总共多少条
     * @return string
     */
    public static function pageHtml($nowPage,$pageCount,$allCount)
    {
        if($nowPage==null||$nowPage==0){$nowPage=1;}
        if($pageCount==null||$pageCount==0){$pageCount=10;}
        if($allCount<=$pageCount){
            return '';
        }
        $str='';
        if($allCount%$pageCount==0){
            $count=floor($allCount/$pageCount);
        }else{
            $count=floor($allCount/$pageCount)+1;
        }
        if($count<=0) {
            return $str;
        }
        if($nowPage>1)
        {
            $str .= '<li><a  page="' . ($nowPage - 1) . '" href="javascript:;">上一页</a></li>';
        }
        if($count<=9){
            for($i=1;$i<=$count;$i++) {
                if ($nowPage == $i) {
                    $str .= '<li class="active"><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                } else {
                    $str .= '<li ><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                }
            }
        }else if($nowPage<=7){
            for($i=1;$i<=7;$i++){
                if($nowPage==$i){
                    $str .= '<li class="active"><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                }else{
                    $str .= '<li ><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                }
            }
            $str .= '<li ><a href="javascript:;">...</a></li>';
            $str .= '<li ><a page="' . $count . '" href="javascript:;">' . $count . '</a></li>';
        }else if($nowPage>$count-7){
            $str .= '<li ><a page="1" href="javascript:;">1</a></li>';
            $str .= '<li ><a href="javascript:;">...</a></li>';
            for($i=$count-6;$i<=$count;$i++){
                if($nowPage==$i){
                    $str .= '<li class="active"><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                }else{
                    $str .= '<li ><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                }
            }
        }else{
            $str .= '<li ><a page="1" href="javascript:;">1</a></li>';
            $str .= '<li ><a href="javascript:;">...</a></li>';
            for($i=$nowPage-2;$i<=$nowPage+2;$i++){
                if($nowPage==$i){
                    $str .= '<li class="active"><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                }else{
                    $str .= '<li ><a page="' . $i . '" href="javascript:;">' . $i . '</a></li>';
                }
            }
            $str .= '<li ><a href="javascript:;">...</a></li>';
            $str .= '<li ><a page="' . $count . '" href="javascript:;">' . $count . '</a></li>';
        }

        if($count>1)
        {
            if($nowPage<$count){  $str .= '<li><a  page="' . ($nowPage + 1) . '" href="javascript:;">下一页</a></li>';}
        }
        return $str;
    }


    /**
     * 中文字符串 substr
     *
     * @param string $str
     * @param int    $start
     * @param int    $len
     * @return string
     */
    private function _msubstr ($str, $start, $len)
    {
        $start  = $start * 2;
        $len    = $len * 2;
        $strlen = strlen($str);
        $result = '';
        for ( $i = 0; $i < $strlen; $i++ ) {
            if ( $i >= $start && $i < ($start + $len) ) {
                if ( ord(substr($str, $i, 1)) > 129 ) $result .= substr($str, $i, 2);
                else $result .= substr($str, $i, 1);
            }
            if ( ord(substr($str, $i, 1)) > 129 ) $i++;
        }
        return $result;
    }
    /**
     * 字符串切分为数组 (汉字或者一个字符为单位)
     *
     * @param string $str
     * @return array
     */
    private function _cutWord( $str )
    {
        $words = array();
        while ( $str != "" )
        {
            if ( $this->_isAscii($str) ) {/*非中文*/
                $words[] = $str[0];
                $str = substr( $str, strlen($str[0]) );
            }else{
                $word = $this->_msubstr( $str, 0, 1 );
                $words[] = $word;
                $str = substr( $str, strlen($word) );
            }
        }
        return $words;
    }
    /**
     * 判断字符是否是ascii字符
     *
     * @param string $char
     * @return bool
     */
    private function _isAscii( $char )
    {
        return ( ord( substr($char,0,1) ) < 160 );
    }
    /**
     * 判断字符串前3个字符是否是ascii字符
     *
     * @param string $str
     * @return bool
     */
    private function _isAsciis( $str )
    {
        $len = strlen($str) >= 3 ? 3: 2;
        $chars = array();
        for( $i = 1; $i < $len -1; $i++ ){
            $chars[] = $this->_isAscii( $str[$i] ) ? 'yes':'no';
        }
        $result = array_count_values( $chars );
        if ( empty($result['no']) ){
            return true;
        }
        return false;
    }
    /**
     * 获取中文字串的拼音首字符
     *
     * @param string $str
     * @return string
     */
    public function getInitials( $str )
    {
        if ( empty($str) ) return '';
        if ( $this->_isAscii($str[0]) && $this->_isAsciis( $str )){
            return $str;
        }
        $result = array();
        if ( $this->_charset == 'utf-8' ){
            $str = iconv( 'utf-8', 'gb2312', $str );
        }
        $words = $this->_cutWord( $str );
        foreach ( $words as $word )
        {
            if ( $this->_isAscii($word) ) {/*非中文*/
                $result[] = $word;
                continue;
            }
            $code = ord( substr($word,0,1) ) * 1000 + ord( substr($word,1,1) );
            /*获取拼音首字母A--Z*/
            if ( ($i = $this->_search($code)) != -1 ){
                $result[] = $this->_pinyins[$i];
            }
        }
        return strtoupper(implode('',$result));
    }
    private function _getChar( $ascii )
    {
        if ( $ascii >= 48 && $ascii <= 57){
            return chr($ascii);  /*数字*/
        }elseif ( $ascii>=65 && $ascii<=90 ){
            return chr($ascii);   /* A--Z*/
        }elseif ($ascii>=97 && $ascii<=122){
            return chr($ascii-32); /* a--z*/
        }else{
            return '-'; /*其他*/
        }
    }
    /**
     * 查找需要的汉字内码(gb2312) 对应的拼音字符( 二分法 )
     *
     * @param int $code
     * @return int
     */
    private function _search( $code )
    {
        $data = array_keys($this->_pinyins);
        $lower = 0;
        $upper = sizeof($data)-1;
        $middle = (int) round(($lower + $upper) / 2);
        if ( $code < $data[0] ) return -1;
        for (;;) {
            if ( $lower > $upper ){
                return $data[$lower-1];
            }
            $tmp = (int) round(($lower + $upper) / 2);
            if ( !isset($data[$tmp]) ){
                return $data[$middle];
            }else{
                $middle = $tmp;
            }
            if ( $data[$middle] < $code ){
                $lower = (int)$middle + 1;
            }else if ( $data[$middle] == $code ) {
                return $data[$middle];
            }else{
                $upper = (int)$middle - 1;
            }
        }
    }


    public function array_sort($arr, $keys, $type = 'asc') {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if ($type == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[] = $arr[$k];
        }
        return $new_array;
    }


}


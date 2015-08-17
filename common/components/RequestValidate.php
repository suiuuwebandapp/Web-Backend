<?php
/**
 * Created by PhpStorm.
 * User: suiuu
 * Date: 15/5/19
 * Time: 下午2:59
 */

namespace common\components;

use yii;

class RequestValidate
{

    public static function is_mobile_request()

    {

        $_SERVER['ALL_HTTP'] = isset($_SERVER['ALL_HTTP']) ? $_SERVER['ALL_HTTP'] : '';

        $mobile_browser = '0';

        if(preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|iphone|android|xoom)/i', strtolower($_SERVER['HTTP_USER_AGENT'])))

            $mobile_browser++;

        if((isset($_SERVER['HTTP_ACCEPT'])) and (strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') !== false))

            $mobile_browser++;

        if(isset($_SERVER['HTTP_X_WAP_PROFILE']))

            $mobile_browser++;

        if(isset($_SERVER['HTTP_PROFILE']))

            $mobile_browser++;

        $mobile_ua = strtolower(substr($_SERVER['HTTP_USER_AGENT'],0,4));

        $mobile_agents = array(

            'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',

            'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',

            'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',

            'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',

            'newt','noki','oper','palm','pana','pant','phil','play','port','prox',

            'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',

            'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',

            'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',

            'wapr','webc','winw','winw','xda','xda-'

        );

        if(in_array($mobile_ua, $mobile_agents))

            $mobile_browser++;

        if(strpos(strtolower($_SERVER['ALL_HTTP']), 'operamini') !== false)

            $mobile_browser++;

// Pre-final check to reset everything if the user is on Windows

        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows') !== false)

            $mobile_browser=0;

// But WP7 is also Windows, with a slightly different characteristic

        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'windows phone') !== false)

            $mobile_browser++;
        if($mobile_browser>0)

            return true;

        else

            return false;

    }

    public function validate()
    {
        if (isset($_POST) && !empty($_POST)) {
            $this->arrWalk($_POST);
        }
        if (isset($_GET) && !empty($_GET)) {
            $this->arrWalk($_GET);
        }
    }

    private function arrWalk(&$arr)
    {
        if (isset($arr) && !empty($arr)) {
            array_walk($arr, function (&$value, $key) {
                if (is_array($value)) {
                    $this->arrWalk($value);
                } else {
                    $value= yii\helpers\HtmlPurifier::process($value);
                    //$value = $this->checkhtml($value);
                }
            });
        }
    }

    //屏蔽html
    private function checkhtml($html)
    {
        //if(!checkperm('allowhtml')) {

        preg_match_all("/\<([^\<]+)\>/is", $html, $ms);
        $searchs[] = '<';
        $replaces[] = '&lt;';
        $searchs[] = '>';
        $replaces[] = '&gt;';

        if ($ms[1]) {
            $allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|strong|i|u|em|span|ol|ul|li|blockquote|object|param|embed';
            $ms[1] = array_unique($ms[1]);
            foreach ($ms[1] as $value) {
                $searchs[] = "&lt;" . $value . "&gt;";

                $value = str_replace('&', '_uch_tmp_str_', $value);
                $value = $this->dhtmlspecialchars($value);
                $value = str_replace('_uch_tmp_str_', '&', $value);

                $value = str_replace(array('\\', '/*'), array('.', '/.'), $value);
                $skipkeys = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate',
                    'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange',
                    'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick',
                    'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate',
                    'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete',
                    'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel',
                    'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart',
                    'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop',
                    'onsubmit', 'onunload', 'javascript', 'script', 'eval', 'behaviour', 'expression', 'style', 'class');
                $skipstr = implode('|', $skipkeys);
                $value = preg_replace(array("/($skipstr)/i"), '.', $value);
                if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
                    $value = '';
                }
                $replaces[] = empty($value) ? '' : "<" . str_replace('&quot;', '"', $value) . ">";
            }
        }

        $html = str_replace($searchs, $replaces, $html);
        //}
        return $html;
    }

    private function dhtmlspecialchars($string, $flags = null)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = $this->dhtmlspecialchars($val, $flags);
            }
        } else {
            if ($flags === null) {
                $string = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string);
                var_dump($string);
                if (strpos($string, '&amp;#') !== false) {
                    $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4}));)/', '&\\1', $string);

                }
            } else {
                if (PHP_VERSION < '5.4.0') {
                    $string = htmlspecialchars($string, $flags);
                } else {
                    $charset = 'UTF-8';
                    $string = htmlspecialchars($string, $flags, $charset);
                }
            }
        }
        return $string;
    }
}
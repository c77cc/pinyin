<?php

/**
 * PinyinSplit 
 * 
 * @author c77cc.Cn <yaohuaq@gmail.com> 
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class PinyinSplit 
{
    //声母表
    private $sms = array('b','c','d','f','g','h','j','k','l','m','n','p','q','r','s','t','w','x','y','z','sh','zh','ch');
    //韵母表
    private $yms = array('a','e','i','o','u','v','ai','au','ao','ei','er','ou','ue','ua','an','en','in','un','ie','uv','uo','ui','iu','ia','ang','ing','ong','eng','uan','uai','ian','iao','uang','ueng','iong','iang');

    private $result = '';

    public function __construct($string) 
    {
        // 将字母以外的字符串过滤掉
        $string = preg_replace('/[^a-z]/i', '', $string);
        // 先找声母，其实先韵母也可以，切分的效果是一致的， $this->result = substr($this->findym($string), 0, -1);
        $this->result = substr($this->findsm($string), 0, -1);
    }

    /**
     * get 
     * 
     * @access public
     * @return void
     */
    public function get() 
    {
        return $this->result;
    }

    /**
     * 查找声母
     * @param string $string: 需要切分的字符串，如tiananmen 
     * @param string $result: findym切分返回的值，默认为空，如anmen
     * @return void
     */
    public function findsm($string, $result = '') 
    {
        $find_len   = 0;    //查找出的声母的长度
        $str_len    = strlen($string);      //字符串长度

        foreach($this->sms as $sm) {        //遍历声母表
            for($i=0;$i<=$str_len;$i++) {
                if($i > 2) {                //声母长度大于2时，处于性能的考虑，跳出该循环（声母长度大于2是不成立的）
                    break;
                }
                if($sm == substr($string, 0 ,$i+1)) {       //依次切分$string字符串 ,结果与$sm做比对，成立时，记下此声母的长度
                    $find_len = strlen($sm);
                }
            }
        }

        if($find_len != 0) {    //查找出的声母长度不等与0，也就是说已经找到声母了
            $result .= substr($string, 0 ,$find_len);   // 将已经找到的声母叠加返回给$result
            $str_last = substr($string, $find_len);     // 将字符串里除了声母的的剩下部分的返回给 $str_last，给下一个findym(找韵母)方法使用
        }else {
            $result = $this->findym($string, $result);  //没有找到声母，调用 findym方法 找韵母
        }

        if(isset($str_last) && strlen($str_last) > 0) { //如果还有字符串还有剩余部分，继续 找韵母
            $result = $this->findym($str_last, $result);
        }
    
        return $result;
    }

    /**
     * findym 
     * 
     * @param mixed $string 
     * @param string $result 
     * @access public
     * @return void
     */
    public function findym($string, $result = '') 
    {
        $find_len   = 0;
        $str_len    = strlen($string);

        foreach($this->yms as $ym) {
            for($i=0;$i<=$str_len;$i++) {
                if($i > 4) {
                    break;
                }
                if($ym == substr($string, 0, $i+1)) {
                    $find_len = strlen($ym);
                }
            }
        }

        if($find_len != 0) {
            $result .= substr($string, 0, $find_len);
            $result .= ' ';
            $str_last = substr($string, $find_len);
        }else {
            $result = $this->findsm($string, $result);
        }

        if(isset($str_last) && strlen($str_last) > 0) {
            $result = $this->findsm($str_last, $result);
        }

        return $result;
    }

}

if(isset($argc) && $argc > 1) {
    $string = $argv[1];
}else {
    $string = isset($_GET['string']) ? $_GET['string'] : '';
}


$p = new PinyinSplit($string);
$rs = $p->get();
echo $rs."\n\n";
?>

<?php
namespace frontend\components;

use yii\web\Controller;

class BaseController extends Controller
{
    private $_isMobile;

    const RE_MOBILE = '/(nokia|iphone|ipad|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220)/i';

    public function getIsMobile()
    {
        if ($this->_isMobile === null) {
            $this->_isMobile = isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match(self::RE_MOBILE, $_SERVER['HTTP_USER_AGENT']);
        }
        return $this->_isMobile;
    }

    public function init()
    {
        if ($this->getIsMobile())
            $this->layout='/mobile/main';
    }
}

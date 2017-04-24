<?php
/**
 * Kerisy Framework
 *
 * PHP Version 7
 *
 * @author          kaihui.wang <hpuwang@gmail.com>
 * @copyright      (c) 2015 putao.com, Inc.
 * @package         kerisy/framework
 * @version         3.0.0
 */

namespace Kerisy\Support\Serialization;


abstract class SerializationAbstract
{
    public static $bodyOffset = 4;

    public abstract function format($data);

    public abstract function xformat($data);
    
    public abstract function trans($data);
    
    public abstract function xtrans($data);

    public static function setBodyOffset($bodyOffset)
    {
        self::$bodyOffset = $bodyOffset;
    }

    public static function getBodyOffset()
    {
        return self::$bodyOffset;
    }

    /**
     * 数据流发送字符串衔接
     *
     * @param $bufferData
     * @return string
     */
    public function getSendContent($bufferData)
    {
        if (!$bufferData) return "";
        $len = strlen($bufferData);
        $packLen = pack("N", $len);
        //兼容老框架
        $packType = pack("C",0);
        return $packLen . $packType.$bufferData;
    }

    /**
     * 数据流body内容获取
     *
     * @param $bufferData
     * @return string
     */
    public function getBody($bufferData)
    {
        if (!$bufferData) return "";
        //兼容老框架
        $bodyOffset = self::$bodyOffset+1;
        $content = substr($bufferData, $bodyOffset);
        return $content;
    }
}
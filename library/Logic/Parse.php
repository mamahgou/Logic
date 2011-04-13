<?php

class Logic_Parse
{
    /**
     * parse url for UTF-8
     *
     * @param string $url
     * @param boolean $xml
     * @return string
     */
    public static function url($url, $xml = false)
    {
        //decode the input url
        $decode = htmlspecialchars_decode(htmlspecialchars_decode(urldecode($url), ENT_QUOTES), ENT_QUOTES);

        //encode first time
        $encode = rawurlencode($decode);
        $a = array("%3A", "%2F", "%40", "%3F", "%3D", "%23", "%26");
        $b = array(":", "/", "@", "?", "=", "#", "&");

        //replace special chars first, then escape ' " < > & for XML
        $return = ($xml) ? htmlspecialchars(str_replace($a, $b, $encode), ENT_QUOTES) : str_replace($a, $b, $encode);
        return $return;
    }

    /**
     * escape xml " ' < > &
     *
     * @param string $string
     * @return string
     */
    public static function xmlEscape($string)
    {
        //decode the input url
        $decode = htmlspecialchars_decode(htmlspecialchars_decode(stripcslashes($string), ENT_QUOTES), ENT_QUOTES);
        return htmlspecialchars($decode, ENT_QUOTES);
    }

    /**
     * xml cdata
     *
     * @param string $string
     * @return string
     */
    public static function cdata($string)
    {
        if (strlen($string) > 0) {
            return '<![CDATA[' . $string . ']]>';
        }
    }
}
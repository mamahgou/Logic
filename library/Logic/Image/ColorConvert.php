<?php

class Logic_Image_ColorConvert
{
    /**
     * convert html color to rgb color
     *
     * @param string $color
     * @throws Exception
     * @return array
     */
    public static function rgb($color)
    {
        if (!self::_isValidHtmlColor($color)) {
            throw new Exception('Not a valid HTML color');
        }

        //strip the #
        $color = substr($color, 1);

        //check shorten color link #FFF
        if (strlen($color) == 3) {
            $color = $color{0} . $color{0} . $color{1} . $color{1} . $color{2} . $color{0};
        }

        $red = base_convert(substr($color, 0, 2), 16, 10);
        $green = base_convert(substr($color, 2, 2), 16, 10);
        $blue = base_convert(substr($color, 4, 2), 16, 10);

        return array($red, $green, $blue);
    }

    /**
     * is valid html color like #FFFFFF, #000000
     *
     * @param string $color
     * @return boolean
     */
    protected static function _isValidHtmlColor($color)
    {
        return (preg_match('/^#(?:(?:[a-f\d]{3}){1,2})$/i', $color) > 0) ? true : false;
    }
}
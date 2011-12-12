<?php

class Logic_Captcha_Image extends Zend_Captcha_Image
{
    /**
     * Generate random character size
     *
     * @return int
     */
    protected function _randomSize()
    {
        return 1;
    }
}
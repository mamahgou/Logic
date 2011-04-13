<?php

class Logic_View_Helper_YesNo
{
    /**
     * reture status string
     *
     * @param   null|string     $key
     * @return  array|string
     */
    public function yesNo($key = null)
    {
        $status = array(
            '1' => '是',
            '0' => '否',
        );

        if ($key === null) {
            return $status;
        }
        if (array_key_exists($key, $status)) {
            return $status[$key];
        } else {
            return '未知';
        }
    }
}
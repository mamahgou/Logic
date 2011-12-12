<?php

class Logic_Filter_Input extends Zend_Filter_Input
{
    /**
     * get error messages into flat array
     *
     * @return string
     */
    public function getErrorMessages()
    {
        $messages = $this->getMessages();
        $return = array();
        foreach ($messages as $val) {
            $tmp = array_values($val);
            foreach ($tmp as $v) {
                $return[] = $v;
            }
        }
        return implode(', ', $return);
    }
}
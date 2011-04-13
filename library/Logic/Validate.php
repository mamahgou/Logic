<?php

class Logic_Validate
{
    /**
     * filter
     *
     * @var array
     */
    protected static $_filters = array('*' => 'StringTrim');

    /**
     * validator
     *
     * @var array
     */
    protected static $_validators = array();

    /**
     * validation options
     *
     * @var array
     */
    protected static $_validOption = array('presence' => 'required', 'breakChainOnFailure'=>true);

    /**
     * validate input
     *
     * @param   array       $validators
     * @param   array       $filters
     * @param   array       $validOption
     * @return  Logic_Filter_Input
     */
    public static function input($validators = array(), $filters = array(), $validOption = array())
    {
        if (!empty($validators)) {
            self::$_validators = $validators;
        }

        if (!empty($filters)) {
            self::$_filters = $filters;
        }

        if (!empty($validOption)) {
            self::$_validOption = $validOption;
        }

        $request = Zend_Controller_Front::getInstance()->getRequest();
        $input = new Logic_Filter_Input(self::$_filters, self::$_validators, $request->getPost(), self::$_validOption);
        $input->setDefaultEscapeFilter(new Zend_Filter_HtmlEntities(ENT_COMPAT, 'UTF-8'));
        return $input;
    }
}
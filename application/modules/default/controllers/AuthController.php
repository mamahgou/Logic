<?php

class AuthController extends Logic_Controller_Action_Default
{

    public function init()
    {
        $this->noSideBar();
        $this->setLayoutName('login');

        //head title
        $this->view->headTitle('登入', 'PREPEND');
    }

    public function indexAction()
    {
        $this->_forward('login');
    }

    public function loginAction()
    {
        //is logined?
        $auth = Zend_Auth::getInstance();
        $module = $this->getRequest()->getModuleName();

        if (isset($this->_storage[$module]) && !empty($this->_storage[$module])) {
            //already login
            $this->_redirect('/');
        }

        //captcha
        $captcha = new Logic_Captcha_Image(array(
            'captcha' => 'Image',
            'wordLen' => 4,
            'timeout' => 300,
            'font' => FONT_PATH . '/MONACO.TTF',
            'imgDir' => CAPTCHA_PATH,
            'imgUrl' => $this->getFrontController()->getBaseUrl() . '/captcha/',
            'width' => 74,
            'height' => 24,
            'fontSize' => 11,
            'imgAlt' => '驗證碼',
            'DotNoiseLevel' => 0,
            'LineNoiseLevel' => 0,
            'GcFreq' => 10,
            'useNumbers' => false
        ));

        if ($this->isGet()) {
            $captcha->generate();
        }

        $errorMessage = array();

        if ($this->isPost()) {
            //validate captcha
            if (!$captcha->isValid($this->_getParam('captcha'))) {
                $captcha->generate();
                $errorMessage[] = '驗證碼錯誤';
            }

            //validator
            $validator = array(
                'account' => array(
                    'NotEmpty',
                    'EmailAddress',
                    'messages' => array(
                        '請輸入帳號',
                        array(
                            Zend_Validate_EmailAddress::INVALID_FORMAT => '帳號格式錯誤'
                        )
                    )
                )
                ,
                'password' => array(
                    'NotEmpty',
                    'messages' => '請輸入密碼'
                )
            );

            $input = Logic_Validate::input($validator);

            //validate input
            if (!$input->isValid() || !empty($errorMessage)) {
                $captcha->generate();
                $inputErrorMessage = $input->getErrorMessages();
                if (!empty($inputErrorMessage)) {
                    $errorMessage[] = $inputErrorMessage;
                }
            } else {
                $db = Zend_Registry::get('db');
                $authAdapter = new Logic_Auth_Default($db);
                $authAdapter->setAccount($input->account)->setPassword($input->password);

                //authenticate
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $member = $authAdapter->getAuthResult();

                    //write identity storage
                    $this->_storage[$module] = $member;
                    $auth->getStorage()->write($this->_storage);

                    //redirect
                    $this->_redirect('/');
                } else {
                    $captcha->generate();
                    $errorMessage = $result->getMessages();
                }
            }
            $this->view->account = isset($_POST['account']) ? $_POST['account'] : '';
        }


        $this->view->errorMessage = $errorMessage;
        $this->view->captcha = $captcha;
    }

    public function logoutAction()
    {
        $this->blankPage();
        Zend_Session::forgetMe();
        $module = $this->getRequest()->getModuleName();
        unset($this->_storage[$module]);
        Zend_Auth::getInstance()->getStorage()->write($this->_storage);
        $this->_redirect('/');
    }

}


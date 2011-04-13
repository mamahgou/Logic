<?php

class Admin_AuthController extends Logic_Controller_Action_Admin
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
        $storage = $auth->getStorage()->read();

        //captcha
        $captcha = new Logic_Captcha_Image(array(
            'captcha' => 'Image',
            'wordLen' => 4,
            'timeout' => 300,
            'font' => FONT_PATH . '/MONACO.TTF',
            'imgDir' => CAPTCHA_PATH,
            'imgUrl' => $this->_request->getBaseUrl() . '/captcha/',
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

            //validate captcha
            if (!$captcha->isValid($this->_getParam('captcha'))) {
                $captcha->generate();
                $errorMessage[] = '認証碼錯誤';
            }

            //validate input
            if (!$input->isValid() || !empty($errorMessage)) {
                $captcha->generate();
                $inputErrorMessage = $input->getErrorMessages();
                if (!empty($inputErrorMessage)) {
                    $errorMessage[] = $inputErrorMessage;
                }
            } else {
                $db = $this->_bootstrap->getResource('db');
                $db = Zend_Registry::get('db');
                $authAdapter = new Logic_Auth_Admin($db);
                $authAdapter->setAccount($input->account)->setPassword($input->password);

                //authenticate
                $result = $auth->authenticate($authAdapter);

                if ($result->isValid()) {
                    $member = $authAdapter->getAuthResult();

                    //write identity storage
                    $storage['admin'] = $member;
                    $auth->getStorage()->write($storage);

                    $settings = array(
                        '2' => array(
                            'company', 'milestone', 'public', 'customer', 'partner'
                        ),
                        '4' => array(
                            'news'
                        ),
                        '8' => array(
                            'portfolio'
                        ),
                        '16' => array(
                            'monitor', 'radio', 'testimony'
                        ),
                        '32' => array(
                            'contact'
                        ),
                        '64' => array(
                            'operator'
                        ),
                    );

                    //check permission, then redirect
                    $goto = 'profile';
                    foreach ($settings as $bitMapping => $resources) {
                        $bitMapping = intval($bitMapping);
                        if ((intval($storage['admin']['permission']) & $bitMapping) == $bitMapping) {
                            $goto = $resources[0];
                            break;
                        }
                    }

                    //redirect
                    $this->_redirect('/admin/' . $goto . '/');
                } else {
                    $captcha->generate();
                    $errorMessage = $result->getMessages();
                }
            }
        }

        $this->view->errorMessage = $errorMessage;
        $this->view->captcha = $captcha;
    }

    public function logoutAction()
    {
        $this->blankPage();
        Zend_Session::forgetMe();
        unset($this->_storage['admin']);
        Zend_Auth::getInstance()->getStorage()->write($this->_storage);
        $this->_redirect('/admin/auth/login/');
    }

}


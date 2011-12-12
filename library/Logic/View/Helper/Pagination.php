<?php

class Logic_View_Helper_Pagination extends Zend_View_Helper_PaginationControl
{
    /**
     * Render the provided pages.  This checks if $view->paginator is set and,
     * if so, uses that.  Also, if no scrolling style or partial are specified,
     * the defaults will be used (if set).
     *
     * @param  Zend_Paginator (Optional) $paginator
     * @param  string $scrollingStyle (Optional) Scrolling style
     * @param  string $partial (Optional) View partial
     * @param  array|string $params (Optional) params to pass to the partial
     * @return string
     * @throws Zend_View_Exception
     */
    public function pagination(Zend_Paginator $paginator = null, $scrollingStyle = null,
        $partial = null, $params = null)
    {
        if ($paginator === null) {
            if (isset($this->view->paginator) && $this->view->paginator !== null
                && $this->view->paginator instanceof Zend_Paginator) {
                $paginator = $this->view->paginator;
            } else {
                /**
                 * @see Zend_View_Exception
                 */
                require_once 'Zend/View/Exception.php';

                $e = new Zend_View_Exception('No paginator instance provided or incorrect type');
                $e->setView($this->view);
                throw $e;
            }
        }

        if ($partial === null) {
            if (self::$_defaultViewPartial === null) {
                /**
                 * @see Zend_View_Exception
                 */
                require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception('No view partial provided and no default set');
                $e->setView($this->view);
                throw $e;
            }

            $partial = self::$_defaultViewPartial;
        }

        //load pagination css
        if ($paginator->count()) {
            $front = Zend_Controller_Front::getInstance();
            $headLink = new Zend_View_Helper_HeadLink();
            $headLink->appendStylesheet($front->getBaseUrl() . '/css/pagenav.css');
        }

        $pages = get_object_vars($paginator->getPages($scrollingStyle));

        if ($params !== null) {
            $pages = array_merge($pages, (array) $params);
        }

        if (is_array($partial)) {
            if (count($partial) != 2) {
                /**
                 * @see Zend_View_Exception
                 */
                require_once 'Zend/View/Exception.php';
                $e = new Zend_View_Exception(
                    'A view partial supplied as an array must contain two values: the filename and its module'
                );
                $e->setView($this->view);
                throw $e;
            }

            if ($partial[1] !== null) {
                return $this->view->partial($partial[0], $partial[1], $pages);
            }

            $partial = $partial[0];
        }

        return $this->view->partial($partial, $pages);
    }
}
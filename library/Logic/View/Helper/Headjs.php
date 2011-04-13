<?php

class Logic_View_Helper_Headjs extends Zend_View_Helper_HeadScript
{
    public function headjs()
    {
        $container = $this->getContainer();
        $newContainer = new Zend_View_Helper_Placeholder_Container();

        $general = '';
        $conditional = array();

        $i = 100;
        foreach ($container as $key => $c) {
            $attributes = $c->attributes;
            if (isset($attributes['src']) && !empty($attributes['src'])) {
                if (isset($attributes['conditional']) && !empty($attributes['conditional'])) {
                    $conditional[$attributes['conditional']][] = 'head.js("' . $attributes['src'] . '");' . PHP_EOL;
                } else {
                    $general .= 'head.js("' . $attributes['src'] . '");' . PHP_EOL;
                }
            } else {
                $newContainer->offsetSet($i, $c);
                $i++;
            }
        }

        //set into new container
        $newContainer->offsetSet(0, $this->createData('text/javascript', array(), $general));
        $j = 1;
        foreach ($conditional as $key => $val) {
            $tmp = implode(PHP_EOL, $val);
            $newContainer->offsetSet(
                $j,
                $this->createData(
                    'text/javascript',
                    array('conditional' => $key),
                    $tmp
                )
            );
        }
        $newContainer->ksort();

        $this->setContainer($newContainer);
        return $this->toString();
    }
}
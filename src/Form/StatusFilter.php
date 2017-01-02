<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Support\Form;

use Pi;
use Zend\InputFilter\InputFilter;

class StatusFilter extends InputFilter
{
    public function __construct()
    {
        // status
        $this->add(array(
            'name' => 'status',
            'required' => true,
        ));
        // status_financial
        $this->add(array(
            'name' => 'status_financial',
            'required' => true,
        ));
        // Label
        $this->add(array(
            'name' => 'label',
            'required' => false,
        ));
        // time_suggested
        $this->add(array(
            'name' => 'time_suggested',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // time_execution
        $this->add(array(
            'name' => 'time_execution',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
    }
}
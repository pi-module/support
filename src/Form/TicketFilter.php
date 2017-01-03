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

class TicketFilter extends InputFilter
{
    public function __construct($option = array())
    {
        // Select user
        if (isset($option['selectUser']) && $option['selectUser'] == 1) {
            $this->add(array(
                'name' => 'user',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
            ));
        }
        // subject
        $this->add(array(
            'name' => 'subject',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // message
        $this->add(array(
            'name' => 'message',
            'required' => true,
        ));
        // attach
        if ($option['attach']) {
            $this->add(array(
                'name' => 'attach',
                'required' => false,
            ));
        }
    }
}
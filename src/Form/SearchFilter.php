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

class SearchFilter extends InputFilter
{
    public function __construct()
    {
        // search Status
        $this->add(array(
            'name' => 'searchStatus',
            'required' => true,
        ));
        // search User
        $this->add(array(
            'name' => 'searchUser',
            'required' => true,
        ));
        // search User
        $this->add(array(
            'name' => 'searchLabel',
            'required' => true,
        ));
    }
}
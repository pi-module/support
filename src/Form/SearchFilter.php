<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
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
        $this->add(
            [
                'name'     => 'searchStatus',
                'required' => true,
            ]
        );
        // search User
        $this->add(
            [
                'name'     => 'searchUser',
                'required' => true,
            ]
        );
        // search User
        $this->add(
            [
                'name'     => 'searchLabel',
                'required' => true,
            ]
        );
    }
}

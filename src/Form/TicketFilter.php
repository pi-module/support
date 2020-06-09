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
use Laminas\InputFilter\InputFilter;

class TicketFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // Select user
        if (isset($option['selectUser']) && $option['selectUser'] == 1) {
            $this->add(
                [
                    'name'     => 'user',
                    'required' => true,
                    'filters'  => [
                        [
                            'name' => 'StringTrim',
                        ],
                    ],
                ]
            );
        }

        // subject
        $this->add(
            [
                'name'     => 'subject',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // message
        $this->add(
            [
                'name'     => 'message',
                'required' => true,
            ]
        );

        // attach
        if ($option['attach']) {
            $this->add(
                [
                    'name'     => 'attach',
                    'required' => false,
                ]
            );
        }
    }
}

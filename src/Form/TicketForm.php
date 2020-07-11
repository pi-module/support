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
use Pi\Form\Form as BaseForm;

class TicketForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new TicketFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // Select user
        if (isset($this->option['selectUser']) && $this->option['selectUser'] == 1) {
            $this->add(
                [
                    'name'       => 'user',
                    'options'    => [
                        'label' => __('To user ID'),
                    ],
                    'attributes' => [
                        'type'     => 'text',
                        'required' => true,
                    ],
                ]
            );
        }

        // Subject
        $this->add(
            [
                'name'       => 'subject',
                'options'    => [
                    'label' => __('Subject'),
                ],
                'attributes' => [
                    'type'     => 'text',
                    'required' => true,
                ],
            ]
        );

        // Label
        if (isset($this->option['label']) && $this->option['label'] == 1) {
            $this->add(
                [
                    'name'       => 'label',
                    'type'       => 'Module\Support\Form\Element\Label',
                    'options'    => [
                        'label'      => __('Department'),
                        'zero-title' => __('Select department'),
                    ],
                    'attributes' => [
                        'required' => true,
                    ],
                ]
            );
        }

        // Message
        $this->add(
            [
                'name'       => 'message',
                'options'    => [
                    'label' => __('Message'),
                ],
                'attributes' => [
                    'required' => true,
                    'type'     => 'textarea',
                    'rows'     => '10',
                    'cols'     => '40',
                ],
            ]
        );

        // Attach
        if ($this->option['attach']) {
            $this->add(
                [
                    'name'       => 'attach',
                    'options'    => [
                        'label' => __('Attach file'),
                    ],
                    'attributes' => [
                        'type'        => 'file',
                        'description' => '',
                    ],
                ]
            );
        }

        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}

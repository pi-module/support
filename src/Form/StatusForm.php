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

class StatusForm extends BaseForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new StatusFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // status
        $this->add(
            [
                'name'    => 'status',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Change status'),
                    'value_options' => [
                        1  => __('Open'),
                        2  => __('Answered'),
                        3  => __('Customer-Reply'),
                        4  => __('In Progress'),
                        5  => __('Finished'),
                        6  => __('Hold'),
                        7  => __('Development'),
                        8  => __('Support'),
                        9  => __('Financial'),
                        10 => __('Follow up'),
                    ],
                ],
            ]
        );
        // status_financial
        $this->add(
            [
                'name'    => 'status_financial',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Financial status'),
                    'value_options' => [
                        0 => __('Not defined'),
                        1 => __('Paid'),
                        2 => __('Not paid'),
                        3 => __('Including contract'),
                        4 => __('Free'),
                    ],
                ],
            ]
        );
        // Label
        $this->add(
            [
                'name'    => 'label',
                'type'    => 'Module\Support\Form\Element\Label',
                'options' => [
                    'label'      => __('Label'),
                    'zero-title' => '',
                ],
            ]
        );
        // time_suggested
        $this->add(
            [
                'name'       => 'time_suggested',
                'options'    => [
                    'label' => __('Suggested time'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('minute'),
                ],
            ]
        );
        // time_execution
        $this->add(
            [
                'name'       => 'time_execution',
                'options'    => [
                    'label' => __('Execution time'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('minute'),
                ],
            ]
        );
        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Update'),
                    'class' => 'btn btn-primary',
                ],
            ]
        );
    }
}

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
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Change status'),
                'value_options' => array(
                    1 => __('Open'),
                    2 => __('Answered'),
                    3 => __('Customer-Reply'),
                    4 => __('In Progress'),
                    5 => __('Finished'),
                ),
            ),
        ));
        // status_financial
        $this->add(array(
            'name' => 'status_financial',
            'type' => 'select',
            'options' => array(
                'label' => __('Financial status'),
                'value_options' => array(
                    0 => __('Not defined'),
                    1 => __('Paid'),
                    2 => __('Not paid'),
                    3 => __('Including contract'),
                    4 => __('Free'),
                ),
            ),
        ));
        // Label
        $this->add(array(
            'name' => 'label',
            'type' => 'Module\Support\Form\Element\Label',
            'options' => array(
                'label' => __('Label'),
                'zero-title' => '',
            ),
        ));
        // time_suggested
        $this->add(array(
            'name' => 'time_suggested',
            'options' => array(
                'label' => __('Suggested time'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('minute'),
            )
        ));
        // time_execution
        $this->add(array(
            'name' => 'time_execution',
            'options' => array(
                'label' => __('Execution time'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('minute'),
            )
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Update'),
                'class' => 'btn btn-primary',
            )
        ));
    }
}
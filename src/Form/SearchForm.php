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

class SearchForm extends BaseForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new SearchFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // searchStatus
        $this->add(array(
            'name' => 'searchStatus',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    'open' => __('Opened tickets'),
                    'finish' => __('Finished tickets'),
                    'all' => __('All tickets'),
                ),
            ),
        ));
        // searchUser
        $this->add(array(
            'name' => 'searchUser',
            'type' => 'Module\Support\Form\Element\User',
            'options' => array(
                'label' => __('User'),
            ),
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Filter'),
                'class' => 'btn btn-primary',
            )
        ));
    }
}
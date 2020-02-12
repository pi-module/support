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
        // search Status
        $this->add(
            [
                'name'    => 'searchStatus',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Status'),
                    'value_options' => [
                        'open'   => __('Opened tickets'),
                        'finish' => __('Finished tickets'),
                        'all'    => __('All tickets'),
                        1        => __('Open'),
                        2        => __('Answered'),
                        3        => __('Customer-Reply'),
                        4        => __('In Progress'),
                        5        => __('Finished'),
                        6        => __('Hold'),
                        7        => __('Development'),
                        8        => __('Support'),
                        9        => __('Financial'),
                        10       => __('Follow up'),
                    ],
                ],
            ]
        );
        // searc hUser
        $this->add(
            [
                'name'    => 'searchUser',
                'type'    => 'Module\Support\Form\Element\User',
                'options' => [
                    'label' => __('User'),
                ],
            ]
        );
        // search Label
        $this->add(
            [
                'name'    => 'searchLabel',
                'type'    => 'Module\Support\Form\Element\Label',
                'options' => [
                    'label'      => __('Label'),
                    'zero-title' => __('All labels'),
                ],
            ]
        );
        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Filter'),
                    'class' => 'btn btn-primary',
                ],
            ]
        );
    }
}

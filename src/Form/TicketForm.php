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

class TicketForm extends BaseForm
{
    public function __construct($name = null, $option = array())
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
            $this->add(array(
                'name' => 'user',
                'options' => array(
                    'label' => __('To user ID'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'required' => true,
                )
            ));
        }
        // Subject
        $this->add(array(
            'name' => 'subject',
            'options' => array(
                'label' => __('Subject'),
            ),
            'attributes' => array(
                'type' => 'text',
                'required' => true,
            )
        ));

        // Message
        $this->add(array(
            'name' => 'message',
            'options' => array(
                'label' => __('Message'),
            ),
            'attributes' => array(
                'required' => true,
                'type' => 'textarea',
                'rows' => '10',
                'cols' => '40',
            )
        ));
        // Attach
        if ($this->option['attach']) {
            $this->add(array(
                'name' => 'attach',
                'options' => array(
                    'label' => __('Attach file'),
                ),
                'attributes' => array(
                    'type' => 'file',
                    'description' => '',
                )
            ));
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}
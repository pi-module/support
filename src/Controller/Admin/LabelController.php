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

namespace Module\Support\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Support\Form\LabelForm;
use Module\Support\Form\LabelFilter;

class LabelController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list = Pi::api('label', 'support')->getLabelList();
        // Set view
        $this->view()->setTemplate('label-index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Set form
        $form = new LabelForm('label');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new LabelFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('label')->find($values['id']);
                } else {
                    $row = $this->getModel('label')->createRow();
                }
                $row->assign($values);
                $row->save();
                // clear registry
                Pi::registry('labelList', 'support')->clear();
                // Add log
                $message = __('Label data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            }
        } else {
            if ($id) {
                $label = $this->getModel('label')->find($id)->toArray();
                $form->setData($label);
            }
        }
        // Set view
        $this->view()->setTemplate('label-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add label'));
    }
}
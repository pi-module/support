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

namespace Module\Support\Form\Element;

use Pi;
use Zend\Form\Element\Select;

class Label extends Select
{
    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            $labelList = Pi::api('label', 'support')->getLabelList();
            $list[0] = $this->options['zero-title'];
            foreach ($labelList as $label) {
                $list[$label['id']] = $label['title'];
            }
            $this->valueOptions = $list;
        }
        return $this->valueOptions;
    }
}
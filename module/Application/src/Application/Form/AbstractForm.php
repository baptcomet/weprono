<?php
namespace Application\Form;

use Zend\Form\Form;

class AbstractForm extends Form
{
    /**
     * @param array $match
     * @return bool
     */
    public function hasError($match)
    {
        if (!is_array($match)) {
            $match = (array) $match;
        }

        foreach ($match as $value) {
            if (in_array($value, array_keys($this->getInputFilter()->getMessages()))) {
                return true;
                break;
            }
        }
        return false;
    }
}

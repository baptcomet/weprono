<?php

namespace Application\View\Helper;

use Zend\Form\Element;
use Zend\Form\Element\Button;
use Zend\Form\Element\Captcha;
use Zend\Form\Element\MonthSelect;
use Zend\Form\ElementInterface;
use Zend\Form\LabelAwareInterface;
use Zend\Form\View\Helper\FormRow;

class FormRowHelper extends FormRow
{
    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  null|ElementInterface $element
     * @param  null|array $icon
     * @return string|FormRow
     */
    public function __invoke(ElementInterface $element = null, array $icon = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element, $icon);
    }

    /**
     * Utility form helper that renders a label (if it exists), an element and errors
     *
     * @param  ElementInterface $element
     * @param  null|array $icon
     * @throws \Zend\Form\Exception\DomainException
     * @return string
     */
    public function render(ElementInterface $element, array $icon = null)
    {
        $escapeHtmlHelper = $this->getEscapeHtmlHelper();
        $labelHelper = $this->getLabelHelper();
        $elementHelper = $this->getElementHelper();
        $elementErrorsHelper = $this->getElementErrorsHelper();

        $label = $element->getLabel();
        $inputErrorClass = $this->getInputErrorClass();

        if (isset($label) && '' !== $label) {
            // Translate the label
            if (null !== ($translator = $this->getTranslator())) {
                $label = $translator->translate($label, $this->getTranslatorTextDomain());
            }
        }

        // Does this element have errors ?
        if (count($element->getMessages()) > 0 && !empty($inputErrorClass)) {
            $classAttributes = ($element->hasAttribute('class') ? $element->getAttribute('class') . ' ' : '');
            $classAttributes = $classAttributes . $inputErrorClass;

            $element->setAttribute('class', $classAttributes);
            $wrapper = '<div class="form-group has-error">';
        } else {
            $wrapper = '<div class="form-group">';
        }
        $endwrapper = '</div>';

        if ($this->partial) {
            $vars = array(
                'element' => $element,
                'label' => $label,
                'labelAttributes' => $this->labelAttributes,
                'renderErrors' => $this->renderErrors,
            );

            return $wrapper . $this->view->render($this->partial, $vars) . $endwrapper;
        }

        $elementErrors = null;
        if ($this->renderErrors) {
            $elementErrors = $elementErrorsHelper->render($element, array('class' => 'text-danger'));
        }

        $elementString = $elementHelper->render($element);

        // hidden elements do not need a <label> -https://github.com/zendframework/zf2/issues/5607
        $type = $element->getAttribute('type');
        if (isset($label) && '' !== $label && $type !== 'hidden') {
            $labelAttributes = array();

            if ($element instanceof LabelAwareInterface) {
                $labelAttributes = $element->getLabelAttributes();
            }

            if (!$element instanceof LabelAwareInterface || !$element->getLabelOption('disable_html_escape')) {
                $label = $escapeHtmlHelper($label);
            }

            if (empty($labelAttributes)) {
                $labelAttributes = $this->labelAttributes;
            }

            // Multicheckbox elements have to be handled differently as the HTML standard does not allow nested
            // labels. The semantic way is to group them inside a fieldset
            if ($type === 'multi_checkbox'
                || $type === 'radio'
                || $element instanceof MonthSelect
                || $element instanceof Captcha
            ) {
                $markup = sprintf(
                    '<label>%s</label><fieldset><div class="checkbox">%s</div></fieldset>',
                    $label,
                    $elementString
                );
            } else {
                // Ensure element and label will be separated if element has an `id`-attribute.
                // If element has label option `always_wrap` it will be nested in any case.
                if ($element->hasAttribute('id')
                    && ($element instanceof LabelAwareInterface && !$element->getLabelOption('always_wrap'))
                ) {
                    $labelOpen = '';
                    $labelClose = '';
                    $label = $labelHelper($element);
                } else {
                    $labelOpen = $labelHelper->openTag($labelAttributes);
                    $labelClose = $labelHelper->closeTag();
                }


                if ($label !== '' && (!$element->hasAttribute('id'))
                    || ($element instanceof LabelAwareInterface && $element->getLabelOption('always_wrap'))
                ) {
                    $label = '<span>' . $label . '</span>';
                }

                // Button element is a special case, because label is always rendered inside it
                if ($element instanceof Button) {
                    $labelOpen = $labelClose = $label = '';
                }

                if (!is_null($icon) && is_array($icon) &&
                    array_key_exists('tag', $icon) && array_key_exists('class', $icon)
                ) {
                    $markup = '<div class="input-group">' .
                        '<div class="input-group-addon">' .
                        '<' . $icon['tag'] . ' class="help ' . $icon['class'] . '" title="' . $element->getLabel() . '">' .
                        '</' . $icon['tag'] . '>' .
                        '</div>' .
                        $elementString .
                        '</div>';
                } else {
                    $markup = $labelOpen . $label . $labelClose . $elementString;
                }

            }
            if ($this->renderErrors) {
                $markup .= $elementErrors;
            }
        } else {
            if ($this->renderErrors) {
                $markup = $elementString . $elementErrors;
            } else {
                $markup = $elementString;
            }
        }

        return $wrapper . $markup . $endwrapper;
    }
}

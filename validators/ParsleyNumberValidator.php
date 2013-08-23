<?php
/**
 * ParsleyNumberValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package parsley.validators
 */

/**
 * Validator for numeric values.
 */
class ParsleyNumberValidator extends CNumberValidator implements ParsleyValidator
{
    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if (!$this->allowEmpty) {
            $htmlOptions['data-notblank'] = 'true';
        }
        if ($this->integerOnly) {
            $htmlOptions['data-type'] = 'digits';
        }
        if (isset($this->min, $this->max)) {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'range';
                $htmlOptions['min'] = $this->min;
                $htmlOptions['max'] = $this->max;
            } else {
                $htmlOptions['data-range'] = CJavaScript::encode(array($this->min, $this->max));
            }
        } else {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'number';
                if (isset($this->min)) {
                    $htmlOptions['min'] = $this->min;
                }
                if (isset($this->max)) {
                    $htmlOptions['max'] = $this->max;
                }
            } else {
                if (isset($this->min)) {
                    $htmlOptions['data-min'] = $this->min;
                }
                if (isset($this->max)) {
                    $htmlOptions['data-max'] = $this->max;
                }
            }
        }
        if (isset($this->message)) {
            $htmlOptions['data-error-message'] = $this->message;
        }
    }
}
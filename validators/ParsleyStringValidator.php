<?php
/**
 * ParsleyStringValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package parsley.validators
 */

/**
 * Validator for strings.
 */
class ParsleyStringValidator extends CStringValidator implements ParsleyValidator
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
        if (isset($this->min, $this->max)) {
            $htmlOptions['data-rangelength'] = CJavaScript::encode(array($this->min, $this->max));
        } else {
            if (isset($this->min)) {
                $htmlOptions['data-minlength'] = $this->min;
            }
            if (isset($this->max)) {
                $htmlOptions['data-maxlength'] = $this->max;
            }
        }
        if (isset($this->message)) {
            $htmlOptions['data-error-message'] = $this->message;
        }
    }
}
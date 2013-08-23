<?php
/**
 * ParsleyRequiredValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package parsley.validators
 */

/**
 * Validator for required values.
 */
class ParsleyRequiredValidator extends CRequiredValidator implements ParsleyValidator
{
    /**
     * @var bool whether to use HTML5 attributes instead of data-attributes.
     */
    public $html5Mode;

    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if ($this->html5Mode) {
            $htmlOptions['required'] = 'required';
        } else {
            $htmlOptions['data-required'] = 'true';
        }
        if (isset($this->message)) {
            $htmlOptions['data-error-message'] = $this->message;
        }
    }
}
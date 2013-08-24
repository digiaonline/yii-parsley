<?php
/**
 * ParsleyEmailValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator for email addresses.
 */
class ParsleyEmailValidator extends CEmailValidator implements ParsleyValidator
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
        if (!$this->allowEmpty) {
            $htmlOptions['data-notblank'] = 'true';
        }
        if ($this->html5Mode) {
            $htmlOptions['type'] = 'email';
        } else {
            $htmlOptions['data-type'] = 'email';
        }
    }
}
<?php
/**
 * ParsleyCompareValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package parsley.validators
 */

/**
 * Validator for comparing input values.
 */
class ParsleyCompareValidator extends CValidator implements ParsleyValidator
{
    /**
     * @var string the CSS selector for the element to compare values with.
     */
    public $compareSelector;

    /**
     * Validates a single attribute.
     * This method should be overridden by child classes.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute)
    {
        // client side validation only.
    }

    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if (isset($this->compareSelector)) {
            $htmlOptions['data-equalto'] = $this->compareSelector;
        }
    }
}
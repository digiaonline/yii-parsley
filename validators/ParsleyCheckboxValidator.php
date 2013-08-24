<?php
/**
 * ParsleyCheckboxValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator for checkboxes.
 */
class ParsleyCheckboxValidator extends CValidator implements ParsleyValidator
{
    /**
     * @var int the minimum amount of checkboxes that must be selected.
     */
    public $min;
    /**
     * @var int the maximum amount of checkboxes that must be selected.
     */
    public $max;

    /**
     * Validates a single attribute.
     * This method should be overridden by child classes.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute)
    {
        // todo: implement server-side validation.
    }

    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if (isset($this->min, $this->max)) {
            $htmlOptions['data-rangecheck'] = CJavaScript::encode(array($this->min, $this->max));
        } else {
            if (isset($this->min)) {
                $htmlOptions['data-mincheck'] = $this->min;
            }
            if (isset($this->max)) {
                $htmlOptions['data-maxcheck'] = $this->max;
            }
        }
    }
}
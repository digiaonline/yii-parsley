<?php
/**
 * ParsleyCheckboxValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
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
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute)
    {
        // todo: implement server-side validation.
    }

    /**
     * Registers the parsley html attributes.
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerClientValidation($object, $attribute, &$htmlOptions)
    {
        if (isset($this->min, $this->max)) {
            $htmlOptions['data-rangecheck'] = CJavaScript::encode(array($this->min, $this->max));
            $htmlOptions['data-rangecheck-message'] = $this->getErrorMessage($object, $attribute);
        } elseif (isset($this->min)) {
            $htmlOptions['data-mincheck'] = $this->min;
            $htmlOptions['data-mincheck-message'] = $this->getErrorMessage($object, $attribute);
        } elseif (isset($this->max)) {
            $htmlOptions['data-maxcheck'] = $this->max;
            $htmlOptions['data-maxcheck-message'] = $this->getErrorMessage($object, $attribute);
        }
    }

    /**
     * Returns the validation error message.
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     * @return string the message.
     */
    public function getErrorMessage($object, $attribute)
    {
        if (isset($this->message)) {
            $message = $this->message;
        } elseif (isset($this->min, $this->max)) {
            $message = Yii::t('validator', 'You must select between {min} and {max} choices.');
        } elseif (isset($this->min)) {
            $message = Yii::t('validator', 'You must select at least {min} choices.');
        } elseif (isset($this->max)) {
            $message = Yii::t('validator', 'You cannot have more than {max} choices selected.');
        } else {
            $message = Yii::t('validator', 'Invalid amount of choices selected.');
        }
        return strtr(
            $message,
            array(
                '{attribute}' => $object->getAttributeLabel($attribute),
                '{min}' => $this->min,
                '{max}' => $this->max,
            )
        );
    }
}
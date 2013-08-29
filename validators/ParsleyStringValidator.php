<?php
/**
 * ParsleyStringValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator for strings.
 */
class ParsleyStringValidator extends CStringValidator implements ParsleyValidator
{
    /**
     * Registers the parsley html attributes.
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerClientValidation($object, $attribute, &$htmlOptions)
    {
        if (!$this->allowEmpty) {
            $htmlOptions['data-notblank'] = 'true';
        }
        if (isset($this->min, $this->max)) {
            $htmlOptions['data-rangelength'] = CJavaScript::encode(array($this->min, $this->max));
            $htmlOptions['data-rangelength-message'] = $this->getErrorMessage($object, $attribute);
        } elseif (isset($this->min)) {
            $htmlOptions['data-minlength'] = $this->min;
            $htmlOptions['data-minlength-message'] = $this->getErrorMessage($object, $attribute);
        } elseif (isset($this->max)) {
            $htmlOptions['data-maxlength'] = $this->max;
            $htmlOptions['data-maxlength-message'] = $this->getErrorMessage($object, $attribute);
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
            $message = Yii::t('validator', 'The value must gave between {min} and {max} characters.');
        } elseif (isset($this->min)) {
            $message = Yii::t('validator', 'The value is too short (minimum is {min} characters).');
        } elseif (isset($this->max)) {
            $message = Yii::t('validator', 'The value is too long (maximum is {max} characters).');
        } else {
            $message = Yii::t('validator', 'The value is invalid.');
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
<?php
/**
 * ParsleyNumberValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validates that a value is a valid number.
 */
class ParsleyNumberValidator extends CNumberValidator implements ParsleyValidator
{
    /**
     * @var bool whether to use HTML5 attributes instead of data-attributes.
     */
    public $html5Mode;

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
        if ($this->integerOnly) {
            $htmlOptions['data-type'] = 'digits';
            $htmlOptions['data-type-digits-message'] = Yii::t(
                'validator',
                '{attribute} must be an integer.',
                array(
                    '{attribute}' => $object->getAttributeLabel($attribute),
                )
            );
        } elseif (isset($this->min, $this->max)) {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'range';
                $htmlOptions['min'] = $this->min;
                $htmlOptions['max'] = $this->max;
            } else {
                $htmlOptions['data-range'] = CJavaScript::encode(array($this->min, $this->max));
            }
            $htmlOptions['data-range-message'] = $this->getErrorMessage($object, $attribute);
        } elseif (isset($this->min)) {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'number';
                $htmlOptions['min'] = $this->min;
            } else {
                $htmlOptions['data-min'] = $this->min;
            }
            $htmlOptions['data-min-message'] = $this->getErrorMessage($object, $attribute);
        } elseif (isset($this->max)) {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'number';
                $htmlOptions['max'] = $this->max;
            } else {
                $htmlOptions['data-max'] = $this->max;
            }
            $htmlOptions['data-max-message'] = $this->getErrorMessage($object, $attribute);
        } else {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'number';
            } else {
                $htmlOptions['data-type'] = 'number';
            }
            $htmlOptions['data-type-number-message'] = $this->getErrorMessage($object, $attribute);
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
            if ($this->integerOnly) {
                $message = Yii::t('validator', 'The value must be an integer between {min} and {max}.');
            } else {
                $message = Yii::t('validator', 'The value must be a number between {min} and {max}.');
            }
        } elseif (isset($this->min)) {
            $message = Yii::t('validator', 'The value is too small (minimum is {min}).');
        } elseif (isset($this->min)) {
            $message = Yii::t('validator', 'The value is too big (maximum is {max}).');
        } elseif ($this->integerOnly) {
            $message = Yii::t('validator', 'The value must be an integer.');
        } else {
            $message = Yii::t('validator', 'The value must be a number.');
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
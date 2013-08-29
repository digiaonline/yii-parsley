<?php
/**
 * ParsleyEmailValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
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
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerClientValidation($object, $attribute, &$htmlOptions)
    {
        if (!$this->allowEmpty) {
            $htmlOptions['data-notblank'] = 'true';
        }
        if ($this->html5Mode) {
            $htmlOptions['type'] = 'email';
        } else {
            $htmlOptions['data-type'] = 'email';
        }
        $htmlOptions['data-type-email-message'] = $this->getErrorMessage($object, $attribute);
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
        } else {
            $message = Yii::t('validator', 'This is not a valid email address.');
        }
        return strtr(
            $message,
            array(
                '{attribute}' => $object->getAttributeLabel($attribute),
            )
        );
    }
}
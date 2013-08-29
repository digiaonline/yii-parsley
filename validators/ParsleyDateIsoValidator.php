<?php
/**
 * ParsleyDateIsoValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator for ISO dates.
 */
class ParsleyDateIsoValidator extends CDateValidator implements ParsleyValidator
{
    /**
     * @var string the date format.
     */
    public $format = 'yyyy-MM-dd';

    /**
     * Registers the parsley html attributes.
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerClientValidation($object, $attribute, &$htmlOptions)
    {
        $htmlOptions['data-type'] = 'dateIso';
        $htmlOptions['data-type-dateIso-message'] = $this->getErrorMessage($object, $attribute);
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
            $message = Yii::t('validator', 'The format is invalid.');
        }
        return strtr(
            $message,
            array(
                '{attribute}' => $object->getAttributeLabel($attribute),
            )
        );
    }
}
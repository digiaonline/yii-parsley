<?php
/**
 * ParsleyUrlValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validates that a value is a valid url.
 */
class ParsleyUrlValidator extends CUrlValidator implements ParsleyValidator
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
        // strict URL validation, i.e. the URL must contain the scheme.
        if ($this->defaultScheme === null) {
            $htmlOptions['type'] = 'urlstrict';
            $htmlOptions['data-urlstrict-message'] = $this->getErrorMessage($object, $attribute);
        } else {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'url';
            } else {
                $htmlOptions['data-type'] = 'url';
            }
            $htmlOptions['data-type-url-message'] = $this->getErrorMessage($object, $attribute);
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
        } else {
            $message = Yii::t('validator', 'This is not a valid URL.');
        }
        return strtr(
            $message,
            array(
                '{attribute}' => $object->getAttributeLabel($attribute),
            )
        );
    }
}
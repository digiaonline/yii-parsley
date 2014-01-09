<?php
/**
 * ParsleyRegularExpressionValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator for regular expressions.
 */
class ParsleyRegularExpressionValidator extends CRegularExpressionValidator implements ParsleyValidator
{
    /**
     * @var bool whether to use HTML5 attributes instead of data-attributes.
     */
    public $html5Mode;

    /**
     * Validates the specified object.
     * @param CModel $object the data object being validated
     * @param array $attributes the list of attributes to be validated. Defaults to null,
     * meaning every attribute listed in {@link attributes} will be validated.
     */
    public function validate($object,$attributes=null)
    {
        $this->normalizePattern();
        parent::validate($object, $attributes);
    }

    /**
     * Registers the parsley html attributes.
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerClientValidation($object, $attribute, &$htmlOptions)
    {
        if (!$this->allowEmpty) {
            $htmlOptions['data-notblank'] = true;
        }
        if ($this->html5Mode) {
            $htmlOptions['pattern'] = $this->pattern;
            $htmlOptions['data-pattern-message'] = $this->getErrorMessage($object, $attribute);
        } else {
            // todo: support flags, e.g. incase sensitive
            $htmlOptions['data-regexp'] = $this->pattern;
            $htmlOptions['data-regexp-message'] = $this->getErrorMessage($object, $attribute);
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
            $message = Yii::t('validator', 'The value is invalid.');
        }
        return strtr(
            $message,
            array(
                '{attribute}' => $object->getAttributeLabel($attribute),
            )
        );
    }

    /**
     * Normalizes regexp pattern, i.e. adds the beginning and ending delimiter.
     */
    protected function normalizePattern()
    {
        $this->pattern = '/' . $this->pattern . '/';
    }
}
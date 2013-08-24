<?php
/**
 * ParsleyRemoteValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator with support for remote validation calls.
 */
class ParsleyRemoteValidator extends CValidator implements ParsleyValidator
{
    /**
     * @var mixed the validation url.
     */
    public $url;

    /**
     * Validates a single attribute.
     * This method should be overridden by child classes.
     * @param CModel $object the data object being validated
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute)
    {
        // client-side validation only.
    }

    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if (isset($this->url)) {
            $htmlOptions['data-remote'] = CHtml::normalizeUrl($this->url);
        }
    }
}
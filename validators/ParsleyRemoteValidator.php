<?php
/**
 * ParsleyRemoteValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator with support for remote validation calls (Ajax).
 *
 * You can show frontend server-side specific error messages by returning:
 * { "error": "your custom message" } or { "message": "your custom message" }
 */
class ParsleyRemoteValidator extends CValidator implements ParsleyValidator
{
    /**
     * @var string the validation url.
     */
    public $url;

    /**
     * @var string request method, i.e. get or post.
     */
    public $method = 'get';

    /**
     * @var bool if you make cross domain Ajax calls and expect jsonp,
     * Parsley will accept these valid returns with a 200 response code:
     * 1, true,  { "success": "..." }
     * and assumes false otherwise.
     */
    public $jsonp = false;

    /**
     * Validates a single attribute.
     * This method should be overridden by child classes.
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     */
    protected function validateAttribute($object, $attribute)
    {
        // client-side validation only.
    }

    /**
     * Registers the parsley html attributes.
     * @param CModel $object the data object being validated.
     * @param string $attribute the name of the attribute to be validated.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerClientValidation($object, $attribute, &$htmlOptions)
    {
        $htmlOptions['data-remote'] = CHtml::normalizeUrl($this->url);
        $htmlOptions['data-remote-method'] = strtoupper($this->method);
        if ($this->jsonp) {
            $htmlOptions['data-remote-datatype'] = 'jsonp';
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
        // todo: Is there a message for this?
        return '';
    }
}
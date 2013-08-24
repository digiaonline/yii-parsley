<?php
/**
 * ParsleyRegularExpressionValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
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
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if (!$this->allowEmpty) {
            $htmlOptions['data-notblank'] = true;
        }
        if ($this->html5Mode) {
            $htmlOptions['pattern'] = $this->pattern;
        } else {
            // todo: support flags, e.g. incase sensitive
            $htmlOptions['data-regexp'] = $this->pattern;
        }
    }
}
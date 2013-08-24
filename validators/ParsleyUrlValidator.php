<?php
/**
 * ParsleyUrlValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Validator for url addresses.
 */
class ParsleyUrlValidator extends CUrlValidator implements ParsleyValidator
{
    /**
     * @var bool whether the protocol must be matched as well.
     */
    public $strict;

    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if ($this->strict) {
            $htmlOptions['type'] = 'urlstrict';
        } else {
            if ($this->html5Mode) {
                $htmlOptions['type'] = 'url';
            } else {
                $htmlOptions['data-type'] = 'url';
            }
        }
    }
}
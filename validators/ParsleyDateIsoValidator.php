<?php
/**
 * ParsleyDateIsoValidator class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package parsley.validators
 */

/**
 * Validator for ISO dates.
 */
class ParsleyDateIsoValidator extends CDateValidator implements ParsleyValidator
{
    /**
     * @var string the date format.
     */
    public $format = 'yyyy-mm-dd';

    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions)
    {
        if (!$this->allowEmpty) {
            $htmlOptions['data-notblank'] = 'true';
        }
        $htmlOptions['data-type'] = 'dateIso';
        if (isset($this->message)) {
            $htmlOptions['data-error-message'] = $this->message;
        }
    }
}
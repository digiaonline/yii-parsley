<?php
/**
 * ParsleyValidator interface file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.validators
 */

/**
 * Interface for all parsley validators.
 */
interface ParsleyValidator
{
    /**
     * Registers the parsley html attributes.
     * @param array $htmlOptions the HTML attributes.
     */
    public function registerValidation(&$htmlOptions);
}
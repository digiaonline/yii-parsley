<?php
/**
 * ParsleyActiveForm class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.widgets
 */

Yii::import('vendor.crisu83.yii-extension.behaviors.WidgetBehavior');

/**
 * Active form with support for client-side validation through parsley.js.
 *
 * @method copyId() via WidgetBehavior
 * @method publishAssets($path, $forceCopy = false) via WidgetBehavior
 * @method registerCssFile($url, $media = '') via WidgetBehavior
 * @method registerScriptFile($url, $position = null) via WidgetBehavior
 * @method getClientScript() via WidgetBehavior
 */
class ParsleyActiveForm extends CWidget
{
    const FOCUS_FIRST = 'first';
    const FOCUS_LAST = 'last';
    const FOCUS_NONE = 'none';

    const TRIGGER_CHANGE = 'change';
    const TRIGGER_FOCUSIN = 'focusin';
    const TRIGGER_FOCUSOUT = 'focusout';
    const TRIGGER_KEYUP = 'keyup';

    /**
     * @var string mixed the form action URL.
     */
    public $action = '';

    /**
     * @var string the form submission method. This should be either 'post' or 'get'.
     * Defaults to 'post'.
     */
    public $method = 'post';

    /**
     * @var string the form layout.
     */
    public $layout = TbHtml::FORM_LAYOUT_VERTICAL;

    /**
     * @var bool whether to generate a stateful form. Defaults to false.
     */
    public $stateful = false;

    /**
     * @var array the HTML attributes for the form.
     */
    public $htmlOptions = array();

    /**
     * @var array the javascript options for parsley.js.
     */
    public $options = array();

    // todo: add support for setting the minimum length to trigger validation.

    /**
     * @var bool whether to use HTML5 attributes instead of data-attributes.
     */
    public $html5Mode = false;

    /**
     * @var array a list of event listeners (name => handler).
     */
    public $events = array();

    /**
     * Initializes the widget.
     */
    public function init()
    {
        $this->attachBehavior('extension', new WidgetBehavior());
        $this->copyId();
        TbArray::defaultValue('successClass', 'success', $this->options);
        TbArray::defaultValue('errorClass', 'error', $this->options);
        $method = $this->stateful ? 'statefulFormTb' : 'beginFormTb';
        echo TbHtml::$method($this->layout, $this->action, $this->method, $this->htmlOptions);
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        echo CHtml::endForm();
        $id = $this->getId();
        $onFieldError = <<<EOD
function(elem, parsleyField) {
    var controlGroup = elem.closest('.control-group');
    if (controlGroup.length) {
        controlGroup.removeClass('success').addClass('error');
    }
}
EOD;
        $onFieldSuccess = <<<EOD
function(elem, parsleyField) {
    var controlGroup = elem.closest('.control-group');
    if (controlGroup.length) {
        controlGroup.removeClass('error').addClass('success');
    }
}
EOD;
        $this->events['onFieldError'] = $onFieldError;
        $this->events['onFieldSuccess'] = $onFieldSuccess;
        $this->registerEvents();
        $options = !empty($this->options) ? CJavaScript::encode($this->options) : '';
        $assetsUrl = $this->publishAssets(__DIR__ . '/../assets', true);
        /* @var CClientScript $cs */
        $cs = $this->getClientScript();
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($assetsUrl . '/js/parsley.js', CClientScript::POS_END);
        $cs->registerScript(__CLASS__ . '#' . $id, "jQuery('#{$id}').parsley({$options});", CClientScript::POS_END);
    }

    /**
     * Registers the event handlers.
     */
    protected function registerEvents()
    {
        $listeners = array();
        foreach ($this->events as $name => $handler) {
            if ($handler instanceof CJavaScriptExpression) {
                $listeners[$name] = $handler;
            } else {
                $listeners[$name] = new CJavaScriptExpression($handler);
            }
        }
        $this->options['listeners'] = $listeners;
    }

    /**
     * Renders a text field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see TbHtml::activeTextField
     */
    public function textField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeTextField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a password field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see TbHtml::activePasswordField
     */
    public function passwordField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activePasswordField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a url field for a model attribute.
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field
     * @see TbHtml::activeUrlField
     */
    public function urlField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeUrlField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders an email field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see TbHtml::activeEmailField
     */
    public function emailField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeEmailField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a number field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see TbHtml::activeNumberField
     */
    public function numberField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeNumberField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a range field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     * @see TbHtml::activeRangeField
     */
    public function rangeField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeRangeField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a date field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input field.
     */
    public function dateField($model, $attribute, $htmlOptions = array())
    {
        return TbHtml::activeDateField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a text area for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated text area.
     * @see TbHtml::activeTextArea
     */
    public function textArea($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeTextArea($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a file field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes
     * @return string the generated input field.
     * @see TbHtml::activeFileField
     */
    public function fileField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeFileField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a radio button for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated radio button.
     * @see TbHtml::activeRadioButton
     */
    public function radioButton($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeRadioButton($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a checkbox for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated check box.
     * @see TbHtml::activeCheckBox
     */
    public function checkBox($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeCheckBox($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a dropdown list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated drop down list.
     * @see TbHtml::activeDropDownList
     */
    public function dropDownList($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeDropDownList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders a list box for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated list box.
     * @see TbHtml::activeListBox
     */
    public function listBox($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeListBox($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders a radio button list for a model attribute
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated radio button list.
     * @see TbHtml::activeRadioButtonList
     */
    public function radioButtonList($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeRadioButtonList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders an inline radio button list for a model attribute
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated radio button list.
     * @see TbHtml::activeInlineRadioButtonList
     */
    public function inlineRadioButtonList($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeInlineRadioButtonList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders a checkbox list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated checkbox list.
     * @see TbHtml::activeCheckBoxList
     */
    public function checkBoxList($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeCheckBoxList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders an inline checkbox list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $data data for generating the list options (value=>display)
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated checkbox list.
     * @see TbHtml::activeInlineCheckBoxList
     */
    public function inlineCheckBoxList($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeInlineCheckBoxList($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Renders an uneditable field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated field.
     * @see TbHtml::activeUneditableField
     */
    public function uneditableField($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeUneditableField($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a search query field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated input.
     * @see TbHtml::activeSearchField
     */
    public function searchQuery($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeSearchQueryField($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a text field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeTextFieldControlGroup
     */
    public function textFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeTextFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a password field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activePasswordFieldControlGroup
     */
    public function passwordFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activePasswordFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with an url field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeUrlFieldControlGroup
     */
    public function urlFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeUrlFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with an email field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeEmailFieldControlGroup
     */
    public function emailFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeTextFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a number field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeNumberFieldControlGroup
     */
    public function numberFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeNumberFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a range field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeRangeFieldControlGroup
     */
    public function rangeFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeRangeFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a date field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeDateFieldControlGroup
     */
    public function dateFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeDateFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a text area for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeTextAreaControlGroup
     */
    public function textAreaControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeTextAreaControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a check box for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeCheckBoxControlGroup
     */
    public function checkBoxControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeCheckBoxControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a radio button for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeRadioButtonControlGroup
     */
    public function radioButtonControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeRadioButtonControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a drop down list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeDropDownListControlGroup
     */
    public function dropDownListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeDropDownListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with a list box for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeListBoxControlGroup
     */
    public function listBoxControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeListBoxControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with a file field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeFileFieldControlGroup
     */
    public function fileFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeFileFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a radio button list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeRadioButtonListControlGroup
     */
    public function radioButtonListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeRadioButtonListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with an inline radio button list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeInlineCheckBoxListControlGroup
     */
    public function inlineRadioButtonListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeInlineRadioButtonListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with a check box list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeCheckBoxListControlGroup
     */
    public function checkBoxListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeCheckBoxListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with an inline check box list for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $data data for generating the list options (value=>display).
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeInlineCheckBoxListControlGroup
     */
    public function inlineCheckBoxListControlGroup($model, $attribute, $data, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeInlineCheckBoxListControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Generates a control group with an uneditable field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeUneditableFieldControlGroup
     */
    public function uneditableFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeUneditableFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Generates a control group with a search field for a model attribute.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @return string the generated row.
     * @see TbHtml::activeSearchFieldControlGroup
     */
    public function searchQueryControlGroup($model, $attribute, $htmlOptions = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return TbHtml::activeSearchQueryControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Registers the validators by adding validation HTML attributes to the given options.
     * @param CModel $model the model class.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions the HTML attributes.
     */
    protected function registerValidators($model, $attribute, &$htmlOptions)
    {
        foreach ($model->getValidators($attribute) as $validator) {
            if ($validator instanceof ParsleyValidator) {
                if (property_exists($validator, 'html5Mode')) {
                    $validator->html5Mode = $this->html5Mode;
                }
                $validator->registerValidation($htmlOptions);
            }
        }
    }
}
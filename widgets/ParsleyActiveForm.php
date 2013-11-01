<?php
/**
 * ParsleyActiveForm class file.
 * @author Christoffer Niska <christoffer.niska@gmail.com>
 * @author Christoffer Lindqvist <christoffer.lindqvist@nordsoftware.com>
 * @copyright Copyright &copy; Nord Software 2013-
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package nordsoftware.yii-parsley.widgets
 */

Yii::import('bootstrap.widgets.TbActiveForm');

/**
 * Active form with support for client-side validation through parsley.js.
 *
 * Methods accessible through the 'TbWidget' class:
 * @method string resolveId($id = null)
 * @method string publishAssets($path, $forceCopy = false)
 * @method void registerCssFile($url, $media = '')
 * @method void registerScriptFile($url, $position = null)
 * @method string resolveScriptVersion($filename, $minified = false)
 * @method CClientScript getClientScript()
 */
class ParsleyActiveForm extends TbActiveForm
{
    const FOCUS_FIRST = 'first';
    const FOCUS_LAST = 'last';
    const FOCUS_NONE = 'none';

    const TRIGGER_CHANGE = 'change';
    const TRIGGER_FOCUSIN = 'focusin';
    const TRIGGER_FOCUSOUT = 'focusout';
    const TRIGGER_KEYUP = 'keyup';

    /**
     * @var string the form layout.
     */
    public $layout = TbHtml::FORM_LAYOUT_VERTICAL;

    /**
     * @var array the javascript options for parsley.js.
     */
    public $pluginOptions = array();

    /**
     * @var bool whether to use HTML5 attributes instead of data-attributes.
     */
    public $html5Mode = false;

    /**
     * @var array a list of event listeners (name => handler).
     */
    public $events = array();

    /**
     * @var bool whether to bind the plugin to the associated dom element.
     */
    public $bindPlugin = true;

    /**
     * @var string path to widget assets.
     */
    public $assetPath;

    // todo: add support for setting the minimum length to trigger validation.

    /**
     * Initializes the widget.
     */
    public function init()
    {
        TbArray::defaultValue('successClass', 'success', $this->pluginOptions);
        TbArray::defaultValue('errorClass', 'error', $this->pluginOptions);
        if (!isset($this->assetPath)) {
            $this->assetPath = Yii::getPathOfAlias('vendor.guillaumepotier.parsleyjs.dist');
        }
        if (!$this->bindPlugin) {
            $this->htmlOptions['data-plugin-options'] = CJSON::encode($this->pluginOptions);
        }
        parent::init();
    }

    /**
     * Runs the widget.
     */
    public function run()
    {
        echo TbHtml::endForm();
        $this->registerEvents();
        $this->registerMessages();

        if ($this->assetPath !== false) {
            $assetsUrl = $this->publishAssets($this->assetPath);
            /* @var CClientScript $cs */
            $cs = $this->getClientScript();
            $cs->registerCoreScript('jquery');
            $cs->registerScriptFile($assetsUrl . '/parsley.min.js', CClientScript::POS_END);
        }

        if ($this->bindPlugin) {
            $id = $this->getId();
            $options = !empty($this->pluginOptions) ? CJavaScript::encode($this->pluginOptions) : '';
            $this->getClientScript()->registerScript(
                __CLASS__ . '#' . $id,
                "jQuery('#{$id}').parsley({$options});",
                CClientScript::POS_END
            );
        }
    }

    /**
     * Registers the event handlers.
     */
    protected function registerEvents()
    {
        if (empty($this->events)) {
            return;
        }

        $listeners = array();
        foreach ($this->events as $name => $handler) {
            if ($handler instanceof CJavaScriptExpression) {
                $listeners[$name] = $handler;
            } else {
                $listeners[$name] = new CJavaScriptExpression($handler);
            }
        }
        $this->pluginOptions['listeners'] = $listeners;
    }

    /**
     * Registers default validator messages.
     */
    protected function registerMessages()
    {
        $this->pluginOptions['messages'] = array(
            'defaultMessage' => Yii::t('validation', 'This value seems to be invalid.'),
            'type' => array(
                'email' => Yii::t('validation', 'This value should be a valid email.'),
                'url' => Yii::t('validation', 'This value should be a valid url.'),
                'urlstrict' => Yii::t('validation', 'This value should be a valid url.'),
                'number' => Yii::t('validation', 'This value should be a valid number.'),
                'digits' => Yii::t('validation', 'This value should be digits.'),
                'dateIso' => Yii::t('validation', 'This value should be a valid date (YYYY-MM-DD).'),
                'alphanum' => Yii::t('validation', 'This value should be alphanumeric.'),
                'phone' => Yii::t('validation', 'This value should be a valid phone number.'),
            ),
            'notnull' => Yii::t('validation', 'This value should not be null.'),
            'notblank' => Yii::t('validation', 'This value should not be blank.'),
            'required' => Yii::t('validation', 'This value is required.'),
            'regexp' => Yii::t('validation', 'This value seems to be invalid.'),
            'min' => Yii::t('validation', 'This value should be greater than or equal to %s.'),
            'max' => Yii::t('validation', 'This value should be lower than or equal to %s.'),
            'range' => Yii::t('validation', 'This value should be between %s and %s.'),
            'minlength' => Yii::t('validation', 'This value is too short. It should have %s characters or more.'),
            'maxlength' => Yii::t('validation', 'This value is too long. It should have %s characters or less.'),
            'rangelength' => Yii::t(
                'validation',
                'This value length is invalid. It should be between %s and %s characters long.'
            ),
            'mincheck' => Yii::t('validation', 'You must select at least %s choices.'),
            'maxcheck' => Yii::t('validation', 'You must select %s choices or less.'),
            'rangecheck' => Yii::t('validation', 'You must select between %s and %s choices.'),
            'equalto' => Yii::t('validation', 'This value should be the same.'),
        );
    }

    /**
     * Generates an input for a model attribute.
     * @param string $type the input type.
     * @param CModel $model the data model.
     * @param string $attribute the attribute.
     * @param array $htmlOptions additional HTML attributes.
     * @param array $data data for generating the list options (value=>display).
     * @return string the generated input.
     * @see TbActiveForm::createInput
     */
    public function createInput($type, $model, $attribute, $htmlOptions = array(), $data = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return parent::createInput($type, $model, $attribute, $htmlOptions, $data);
    }

    /**
     * Generates a control group for a model attribute.
     * @param string $type the input type.
     * @param CModel $model the data model.
     * @param string $attribute the attribute name.
     * @param array $htmlOptions additional HTML attributes.
     * @param array $data data for generating the list options (value=>display).
     * @return string the generated control group.
     * @see TbActiveForm::createControlGroup
     */
    public function createControlGroup($type, $model, $attribute, $htmlOptions = array(), $data = array())
    {
        $this->registerValidators($model, $attribute, $htmlOptions);
        return parent::createControlGroup($type, $model, $attribute, $htmlOptions, $data );
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
                $validator->registerClientValidation($model, $attribute, $htmlOptions);
            }
        }
    }
}
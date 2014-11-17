<?php

namespace yii\mozayka\form;

use yii\kladovka\helpers\Text,
    yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\DatePickerAsset,
    Yii;


class DateField extends ActiveField
{

    public $dateFormat = 'j M Y';

    public $altDateFormat = 'Y-m-d';

    public $hiddenInputOptions = [];

    public $pluginOptions = [
        'numberOfMonths' => 3,
        'showButtonPanel' => true
    ];

    public function init()
    {
        $value = $this->model->{$this->attribute};
        if (is_int($value) || ($value && is_string($value))) {
            $this->inputOptions['value'] = Text::date($this->dateFormat, $value);
            $this->hiddenInputOptions['value'] = date($this->altDateFormat, $value);
        }
        if (!$this->readOnly) {
            $pluginOptions = array_merge($this->pluginOptions, [
                'dateFormat' => Text::juiDateFormat($this->dateFormat),
                'altFormat' => Text::juiDateFormat($this->altDateFormat)
            ]);
            $formId = $this->form->getId();
            $inputId = Html::getInputId($this->model, $this->attribute);
            $pluginOptions['altField'] = '#' . $formId . ' #' . $inputId . '-alt';
            $js = 'jQuery(\'#' . $formId . ' #' . $inputId . '\').datepicker(' . Json::encode($pluginOptions) . ');';
            $this->inputOptions['name'] = false;
            $this->hiddenInputOptions['id'] = $inputId . '-alt';
            $this->template .= "\n{hiddenInput}";
            $this->parts['{hiddenInput}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->hiddenInputOptions);
            if (Yii::$app->getRequest()->getIsAjax()) {
                $this->template .= "\n{script}";
                $this->parts['{script}'] = Html::script($js);
            } else {
                $view = $this->form->getView();
                DatePickerAsset::register($view);
                $view->registerJs($js);
            }
        }
        parent::init();
    }
}

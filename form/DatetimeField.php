<?php

namespace yii\mozayka\form;

use yii\kladovka\helpers\Text,
    yii\helpers\Html,
    yii\helpers\Json,
    yii\mozayka\web\TimePickerAsset,
    Yii;


class DatetimeField extends ActiveField
{

    public $dateFormat = 'j M Y';

    public $altDateFormat = 'Y-m-d';

    public $separator = ' ';

    public $altSeparator = ' ';

    public $timeFormat = 'H:i:s';

    public $altTimeFormat = 'H:i:s';

    public $hiddenInputOptions = [];

    public $pluginOptions = [
        'numberOfMonths' => 3,
        'showButtonPanel' => true,
        'altFieldTimeOnly' => false
    ];

    public function init()
    {
        $value = $this->model->{$this->attribute};
        if (is_int($value)) {
            $this->inputOptions['value'] = Text::date($this->dateFormat . $this->separator . $this->timeFormat, $value);
            $this->hiddenInputOptions['value'] = date($this->altDateFormat . $this->altSeparator . $this->altTimeFormat, $value);
        }
        if (!$this->readOnly) {
            $pluginOptions = array_merge($this->pluginOptions, [
                'dateFormat' => Text::juiDateFormat($this->dateFormat),
                'altFormat' => Text::juiDateFormat($this->altDateFormat),
                'separator' => $this->separator,
                'altSeparator' => $this->altSeparator,
                'timeFormat' => Text::juiTimeFormat($this->timeFormat),
                'altTimeFormat' => Text::juiTimeFormat($this->altTimeFormat)
            ]);
            $formId = $this->form->getId();
            $inputId = Html::getInputId($this->model, $this->attribute);
            $pluginOptions['altField'] = '#' . $formId . ' #' . $inputId . '-alt';
            $js = 'jQuery(\'#' . $formId . ' #' . $inputId . '\').datetimepicker(' . Json::encode($pluginOptions) . ');';
            $this->inputOptions['name'] = false;
            $this->hiddenInputOptions['id'] = $inputId . '-alt';
            $this->template .= "\n{hiddenInput}";
            $this->parts['{hiddenInput}'] = Html::activeHiddenInput($this->model, $this->attribute, $this->hiddenInputOptions);
            if (Yii::$app->getRequest()->getIsAjax()) {
                $this->template .= "\n{script}";
                $this->parts['{script}'] = Html::script($js);
            } else {
                $view = $this->form->getView();
                TimePickerAsset::register($view);
                $view->registerJs($js);
            }
        }
        parent::init();
    }
}

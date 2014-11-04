<?php

namespace yii\mozayka\grid;

use yii\kladovka\helpers\Text;


class DateColumn extends DataColumn
{

    public $dateFormat = 'j M Y';

    public function getDataCellValue($model, $key, $index)
    {
        $value = parent::getDataCellValue($model, $key, $index);
        if (is_int($value)) {
            return Text::date2($this->dateFormat, $value);
        }
        return $value;
    }

protected function renderFilterCellContent()
{
$form = $this->grid->form;
$model = $this->grid->filterModel;
$fields = $this->grid->filterFields;
if (array_key_exists($this->attribute, $fields)) {
$options = $fields[$this->attribute];
if (!array_key_exists('class', $options)) {
$options['class'] = 'yii\mozayka\form\DateField';
}
return $form->field($model, $this->attribute, $options);
}
return parent::renderFilterCellContent();
}
}

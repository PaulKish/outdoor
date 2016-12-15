<?php

namespace app\common;

use Yii;
use \PHPExcel;
use kartik\export\ExportMenu as ExportBase;

/**
 * Modded to allow hyper links
 */
class ExportMenu extends ExportBase
{
    /**
     * @var array the output style configuration options. It must be the style configuration array as required by
     *     PHPExcel.
     */
    public $styleOptions = [];


    /**
     * @var string the view file for rendering the export form
     */
    public $exportFormView = '../../vendor/kartik-v/yii2-export/views/_form';

    public $contentBefore = [];

    public $contentAfter = [];

    /**
     * Initializes PHP Excel Object Instance
     *
     * @return void
     */
    public function initPHPExcel()
    {
        $this->_objPHPExcel = new PHPExcel();
        $creator = '';
        $title = '';
        $subject = '';
        $description = Yii::t('kvexport', 'Reelforge)');
        $category = '';
        $keywords = '';
        $manager = '';
        $company = 'Reelforge';
        $created = date("Y-m-d H:i:s");
        $lastModifiedBy = 'Reelforge';
        extract($this->docProperties);
        $this->_objPHPExcel->getProperties()
            ->setCreator($creator)
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription($description)
            ->setCategory($category)
            ->setKeywords($keywords)
            ->setManager($manager)
            ->setCompany($company)
            ->setCreated($created)
            ->setLastModifiedBy($lastModifiedBy);
        $this->raiseEvent('onInitExcel', [$this->_objPHPExcel, $this]);
    }

    /**
     * Generates an output data row with the given data model and key.
     *
     * @param mixed   $model the data model to be rendered
     * @param mixed   $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the model array returned by [[dataProvider]].
     *
     * @return void
     */
    public function generateRow($model, $key, $index)
    {
        /**
         * @var Column $column
         */
        $this->_endCol = 0;
        foreach ($this->getVisibleColumns() as $column) {
            if ($column instanceof SerialColumn) {
                $value = $column->renderDataCell($model, $key, $index);
            } elseif ($column instanceof ActionColumn) {
                $value = '';
            } else {
                $format = $this->enableFormatter && isset($column->format) ? $column->format : 'raw';
                $value = ($column->content === null) ? (method_exists($column, 'getDataCellValue') ?
                    $this->formatter->format($column->getDataCellValue($model, $key, $index), $format) :
                    $column->renderDataCell($model, $key, $index)) :
                    call_user_func($column->content, $model, $key, $index, $column);


            }
            if (empty($value) && !empty($column->attribute) && $column->attribute !== null) {
                $value = ArrayHelper::getValue($model, $column->attribute, '');
            }

            $this->_endCol++;

            // if cell is raw add hyperlink
            if(isset($column->format) &&  $column->format == 'raw'){
                // style array for link color
                $styleArray = array(
                'font'  => array(
                    'color' => array('rgb' => '0000FF'),
                ));

                // set link placeholder
                $cell = $this->_objPHPExcelSheet->setCellValue(
                    self::columnName($this->_endCol) . ($index + $this->_beginRow + 1),
                    'View',
                    true
                )->getHyperlink()->setUrl($value);

                //style link
                $this->_objPHPExcelSheet->getStyle(self::columnName($this->_endCol) . ($index + $this->_beginRow + 1))->applyFromArray($styleArray);
            } else {
                $cell = $this->_objPHPExcelSheet->setCellValue(
                    self::columnName($this->_endCol) . ($index + $this->_beginRow + 1),
                    empty($value) ? '' : strip_tags($value),
                    true
                );
            }
            
            $this->raiseEvent('onRenderDataCell', [$cell, $value, $model, $key, $index, $this]);
        }
    }
}

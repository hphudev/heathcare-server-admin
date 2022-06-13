<?php

namespace App\Admin\Controllers;

use App\Models\Drug;
use App\Models\ProducerUnit;
use App\Models\Receipt;
use App\Models\ReceiptDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use PhpParser\Node\Expr\Cast\String_;

class ReceiptController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Receipt';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Receipt());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('receiptdetail', 'Number of drugs')->display(function ($receiptDetails) {
            $sum = 0;
            foreach($receiptDetails as $receiptDetail)
                $sum += $receiptDetail['SoLuong'];
            $sum = number_format($sum , 0, ',', '.');
            return "<span class='label label-warning'>{$sum}</span>";
        });
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Receipt::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Receipt());
        $form->hasMany('receiptdetail', "Receipt details", function (Form\NestedForm $form) {
            $form->number('SoLuong', __('Number of drugs'))->default(0)->min(0);
            // $form->number('receipt_id', __('Receipt id'));
            $drugs = Drug::get();
            $drugs_associate = [];
            foreach ($drugs as $drug) {
                $producerName = ProducerUnit::find($drug['producer_unit_id'])->TenDonViSanXuat;
                $drugs_associate += [$drug['id'] => 'Name: ' . $drug['TenThuoc'] . ', Price: ' . $drug['GiaBan'] . ', Producer unit: ' . $producerName . ', Expiry: ' . $drug['HanSuDung']];
            }
            $form->select('drug_id', __('Drug'))->options($drugs_associate);
            // $form->number('SoLuong', __('SoLuong'));
        });
        return $form;
    }

}

<?php

namespace App\Admin\Controllers;

use App\Models\Drug;
use App\Models\ReceiptDetail;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ReceiptDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Receipt detail';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ReceiptDetail());

        $grid->column('id', __('ID'));
        $grid->column('receipt_id', __('Receipt id'))->sortable();
        $grid->column('drug_id', __('Drug'))->director()->display(function ($drugid) {
            $name = Drug::find($drugid);
            $name = ($name == null) ? 'Not found' : $name->TenThuoc;
            return $name;
        })->sortable(); 
        $grid->column('SoLuong', __('Number of drugs'))->display(function ($num) {
            $num = number_format($num, 0, '.', ',');
            return "<span class='label label-warning'>{$num}</span>";
        })->sortable();
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
        $show = new Show(ReceiptDetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('receipt_id', __('Receipt id'));
        $show->field('drug_id', __('Drug id'));
        $show->field('SoLuong', __('SoLuong'));
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
        $form = new Form(new ReceiptDetail());

        $form->number('receipt_id', __('Receipt id'));
        $form->number('drug_id', __('Drug id'));
        $form->number('SoLuong', __('SoLuong'));

        return $form;
    }
}

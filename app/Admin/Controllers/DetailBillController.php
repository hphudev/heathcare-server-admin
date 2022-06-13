<?php

namespace App\Admin\Controllers;

use App\Models\DetailBill;
use App\Models\Drug;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DetailBillController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'DetailBill';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DetailBill());

        $grid->column('id', __('ID'));
        $grid->column('bill_id', __('Bill id'));
        $grid->column('drug_id', __('Drug'))->display(function ($drugid) {
            $drug = Drug::find($drugid);
            if ($drug !== null)
                return $drug->TenThuoc;
            else
                return "<span class='label label-danger'>Lỗi</span>";
        });
        $grid->column('DonGia', __('Price'));
        $grid->column('ChietKhau', __('Discount'));
        $grid->column('SoLuong', __('Number of drugs'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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
        $show = new Show(DetailBill::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('bill_id', __('Bill id'));
        $show->field('drug_id', __('Drug id'));
        $show->field('DonGia', __('DonGia'));
        $show->field('ChietKhau', __('ChietKhau'));
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
        $form = new Form(new DetailBill());
        $form->number('bill_id', __('Bill id'))->disable();
        $form->number('drug_id', __('Drug id'))->disable();
        $form->number('DonGia', __('Price'))->disable();
        $form->number('ChietKhau', __('Discount'))->disable();
        $form->number('SoLuong', __('Number of drugs'));
        return $form;
    }
}

<?php

namespace App\Admin\Controllers;

use App\Models\Drug;
use App\Models\DrugGroup;
use App\Models\ProducerUnit;
use App\Models\ProductUnit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class DrugController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Drug';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Drug());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('drug_group_id', __('Drug group'))->director()->display(function ($drugGroupId) {
            return DrugGroup::find($drugGroupId)->TenNhomThuoc;
        })->sortable();
        $grid->column('TenThuoc', __('Name'))->sortable();
        // $grid->column('HinhAnh', __('Image'));
        // $grid->column('SoLuong', __('Number of drug'));
        $grid->column('HanSuDung', __('Expiry'))->sortable();
        $grid->column('GiaNhap', __('Import price'))->display(function ($price) {
            return number_format($price, 0, '.', ',');
        })->sortable();
        $grid->column('GiaBan', __('Price'))->display(function ($price) {
            return number_format($price, 0, '.', ',');
        })->sortable();
        $grid->column('ChietKhau', __('Discount'))->sortable();
        $grid->column('product_unit_id', __('Product unit'))->director()->display(function ($id) {
            if (ProductUnit::find($id) !== null)
                return ProductUnit::find($id)->TenDonViTinh;
            else
                return "<span class = 'label label-danger'>lỗi</span>";
        })->sortable();
        $grid->column('producer_unit_id', __('Producer unit'))->director()->display(function ($id) {
            if (ProducerUnit::find($id) !== null)
                return ProducerUnit::find($id)->TenDonViSanXuat;
            else
                return "<span class = 'label label-danger'>lỗi</span>";
        })->sortable();
        $grid->column('MoTa', __('Describe'));
        // $grid->column('created_at', __('Created at'));
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
        $show = new Show(Drug::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('drug_group_id', __('ID drug group'));
        $show->field('TenThuoc', __('Name'));
        $show->field('HinhAnh', __('Image'));
        // $show->field('SoLuong', __('Number of drug'));
        $show->field('HanSuDung', __('Expiry'));
        $show->field('GiaNhap', __('Import price'));
        $show->field('GiaBan', __('Price'));
        $show->field('ChietKhau', __('Discount'));
        $show->field('product_unit_id', __('ID product unit'));
        $show->field('producer_unit_id', __('ID producer unit'));
        $show->field('MoTa', __('Describe'));
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
        $form = new Form(new Drug());
// 
        $drug_groups = DrugGroup::get();
        $drug_groups_select = [];
        foreach ($drug_groups as $drug_group) {
            $drug_groups_select += [$drug_group['id'] => $drug_group['TenNhomThuoc']];
        }
        $form->select('drug_group_id', __('ID drug group'))->options($drug_groups_select);
// 
        $form->text('TenThuoc', __('Name'));
        $form->image('HinhAnh', __('Image'));
        // $form->number('SoLuong', __('Number of drug'))->min(0);
        $form->date('HanSuDung', __('Expiry'))->default(date('Y-m-d'));
        $form->number('GiaNhap', __('Import price (vnd)'))->min(0);
        $form->number('GiaBan', __('Price (vnd)'))->min(0);
        $form->number('ChietKhau', __('Discount (%)'))->default(0);
// 
        $product_units = ProductUnit::get();
        $product_units_select = [];
        foreach ($product_units as $product_unit) {
            $product_units_select += [$product_unit['id'] => $product_unit['TenDonViTinh']];
        }
        $form->select('product_unit_id', __('Product unit'))->options($product_units_select);
// 
        $producer_units = ProducerUnit::get();
        $producer_units_select = [];
        foreach ($producer_units as $producer_unit) {
            $producer_units_select += [$producer_unit['id'] => $producer_unit['TenDonViSanXuat']];
        }
        $form->select('producer_unit_id', __('Producer unit'))->options($producer_units_select);
// 
        $form->text('MoTa', __('Describe'));

        return $form;
    }
}

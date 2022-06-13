<?php

namespace App\Admin\Controllers;

use App\Models\ProductUnit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ProductUnitController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Product Unit';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProductUnit());

        $grid->column('id', __('ID'));
        $grid->column('TenDonViTinh', __('Name'))->sortable();
        $grid->column('drug', 'Number of Drug')->display(function ($drugs) {
            $count = number_format(count($drugs), 0, '.', ',');
            return "<span class='label label-warning'>{$count}</span>";
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
        $show = new Show(ProductUnit::findOrFail($id));
        $show->field('id', __('Id'));
        $show->field('TenDonViTinh', __('Name'));
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
        $form = new Form(new ProductUnit());

        $form->text('TenDonViTinh', __('Name'));

        return $form;
    }
}

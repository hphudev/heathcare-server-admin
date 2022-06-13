<?php

namespace App\Admin\Controllers;

use App\Models\DrugGroup;
use App\Models\ProducerUnit;
use App\Models\ProductUnit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Grid\Tools\TotalRow;
use Encore\Admin\Show;

class ProducerUnitController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Producer Unit';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ProducerUnit());

        $grid->column('id', __('Id'));
        $grid->column('TenDonViSanXuat', __('Name'))->sortable();
        $grid->column('drug', 'Number of drugs')->display(function ($drugs) {
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
        $show = new Show(ProducerUnit::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('TenDonViSanXuat', __('Name'));
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
        $form = new Form(new ProducerUnit());

        $form->text('TenDonViSanXuat', __('Name'));

        return $form;
    }
    
}

<?php

namespace App\Admin\Controllers;

use App\Models\TransportUnit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class TransportUnitController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Transport Unit';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TransportUnit());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('TenDonViVanChuyen', __('Name'))->sortable();
        $grid->column('shippingdetail', __('Number of bills'))->display(function ($shippingdetails) {
            $count = number_format(count($shippingdetails), 0, '.', ',');
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
        $show = new Show(TransportUnit::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('TenDonViVanChuyen', __('Name'));
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
        $form = new Form(new TransportUnit());

        $form->text('TenDonViVanChuyen', __('Name'));

        return $form;
    }
}

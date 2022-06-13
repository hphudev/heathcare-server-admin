<?php

namespace App\Admin\Controllers;

use App\Models\ShippingDetail;
use App\Models\TransportUnit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ShippingDetailController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ShippingDetail';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ShippingDetail());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('bill_id', __('Bill id'))->sortable();
        $grid->column('transport_unit_id', __('Transport unit name'))->display(function ($transport_unit_id) {
            $transport = TransportUnit::find($transport_unit_id);
            if ($transport !== null)
                return $transport->TenDonViVanChuyen;
            else
                return "<span class='label label-danger'>Lỗi</span>";
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
        $show = new Show(ShippingDetail::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('bill_id', __('Bill id'));
        $show->field('transport_unit_id', __('Transport unit id'));
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
        $form = new Form(new ShippingDetail());

        $form->number('bill_id', __('Bill id'))->disable();
        $form->number('transport_unit_id', __('Transport unit id'))->disable();

        return $form;
    }
}

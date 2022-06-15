<?php

namespace App\Admin\Controllers;

use App\Models\Bill;
use App\Models\Drug;
use App\Models\User;
use App\Models\ProducerUnit;
use App\Models\ProductUnit;
use App\Models\TransportUnit;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;
use GuzzleHttp\Psr7\Request;
use Illuminate\Mail\Transport\Transport;

class BillController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Bill';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Bill());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('user_id', __('Name'))->display(function ($userid) {
            $user = User::find($userid);
            if  ($user !== null)
                return $user->first_name . ' ' . $user->last_name;
        })->sortable();
        $grid->column('NgayThanhToan', __('Date of payment'))->sortable();
        $grid->column('TinhTrang', __('State'))->sortable();
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        // $grid->filter(function ($filter) {
        //     $filter->like('user_id', 'User');
        // });
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
        $show = new Show(Bill::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('user_id', __('Name'));
        $show->field('NgayThanhToan', __('Date of payment'));
        $show->field('TinhTrang', __('State'));
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
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $form = new Form(new Bill());

        $users = User::get();
        $users_asscociate = [];
        foreach ($users as $user) {
            $users_asscociate += [$user->id => 'ID: ' . $user->id . ', Name: ' . $user->name];
        }
        $form->select('user_id', __('Name'))->options($users_asscociate);
        $form->date('NgayThanhToan', __('Date of payment'))->default(date('Y-m-d'));
        $form->select('TinhTrang', __('State'))->options(['Đang chờ' => 'Đang chờ', 'Đang đóng gói' => 'Đang đóng gói', 'Đang vận chuyển' => 'Đang vận chuyển', 'Đã thanh toán' => 'Đã thanh toán'])->default('Đang chờ');
        $form->hasMany('detailbill', "Bill details", function (Form\NestedForm $form) {
            $drugs = Drug::get();
            $drugs_associate = [];
            foreach ($drugs as $drug) {
                $producerName = ProducerUnit::find($drug['producer_unit_id'])->TenDonViSanXuat;
                $productUnitName = ProductUnit::find($drug['product_unit_id'])->TenDonViTinh;
                $drugs_associate += [$drug->id => 'Name: ' . $drug['TenThuoc'] . ', Price: ' . $drug['GiaBan'] . ', Product unit: ' . $productUnitName . ', Producer unit: ' . $producerName . ', Expiry: ' . $drug['HanSuDung']];
            }   
            $form->select('drug_id', __('Drug'))->options($drugs_associate)->loads(['DonGia', 'ChietKhau'], ['/public/api/order/bills/autoGeneratePrice', '/public/api/order/bills/autoGenerateDiscount'])->rules('required');
            $form->number('DonGia', __('Price'))->rules('required')->readonly();
            $form->number('ChietKhau', __('Discount'))->rules('required')->readonly();
            $form->number('SoLuong', __('Number of drugs'))->min(0)->default(0);
        });
        
        $transport_units = TransportUnit::get();
        $transport_units_associate = [];
        foreach ($transport_units as $transport_unit){
            $transport_units_associate += [$transport_unit->id => $transport_unit->TenDonViVanChuyen];
        }
        $form->select('shippingdetail.transport_unit_id', 'Transport unit')->options($transport_units_associate);
        return $form;
    }

    public function autoGeneratePrice(Request $request) {
        return response()->json('ok');
    }
    
}

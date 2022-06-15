<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bill;
use App\Models\Cart;
use App\Models\DetailBill;
use Illuminate\Http\Request;
use App\Models\Drug;
use App\Models\ReceiptDetail;
use App\Models\ShippingDetail;
use App\Models\User;
use Brick\Math\BigInteger;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\TryCatch;

class TestController extends Controller
{
    //
    public function index()
    {
        return response()->json(['id' => 'ok']);
    }
    //
    public function autoGeneratePrice(Request $request)
    {
        $drug = Drug::find($request->q);
        return response()->json([$drug->GiaBan]);
    }
    //
    public function autoGenerateDiscount(Request $request)
    {
        $drug = Drug::find($request->q);
        return response()->json([$drug->ChietKhau]);
    }
    // 
    public function getUsersAndAdmins()
    {
        $admins = DB::table('admin_users')->get();
        $users = DB::table('users')->get();
        return response()->json(['num_users' => count($users), 'num_admins' => count($admins)]);
    }
    // 
    public function getBills(Request $request)
    {
        $start_date = strtotime($request->start_date);
        $end_date = strtotime($request->end_date);
        // return [date('Y-m-d', $start_date), date('Y-m-d', $end_date)];
        $bills = DB::table('hoadon')
            ->select(DB::raw('DATE_FORMAT(hoadon.NgayThanhToan, \'%Y-%m\') as date'), DB::raw('sum(cthd.DonGia * cthd.SoLuong - cthd.ChietKhau) as price'))
            ->join('cthd', 'cthd.bill_id', '=', 'hoadon.id')
            ->whereRaw('NgayThanhToan >= ?', [date('Y-m-d', $start_date)])
            ->whereRaw('NgayThanhToan <= ?', [date('Y-m-d', $end_date)])
            ->whereRaw('TinhTrang = ?', ['Đã thanh toán'])
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        return $bills;
    }
    // 
    public function getAllDrugs(Request $request)
    {
        $drugs = DB::table('thuoc')
            ->join('nhomthuoc', 'thuoc.drug_group_id', '=', 'nhomthuoc.id')
            ->get();
        return response()->json($drugs);
    }
    // 
    public function getDrugsWithDrugGroup(Request $request)
    {
        $drug_group_id = $request->id;
        $name = $request->name;
        $drugs = DB::table('thuoc')
            ->selectRaw('thuoc.*, nhomthuoc.TenNhomThuoc')
            ->join('nhomthuoc', 'thuoc.drug_group_id', '=', 'nhomthuoc.id')
            ->whereRaw('nhomthuoc.TenNhomThuoc like ?', ['%' . $drug_group_id . '%'])
            ->whereRaw('thuoc.TenThuoc like ?', ['%' . $name . '%'])
            ->get();
        return response()->json($drugs);
    }
    // 
    public function getTopFourNewDrugs()
    {
        $drugs = DB::table('thuoc')
            ->selectRaw('thuoc.*, nhomthuoc.TenNhomThuoc')
            ->join('nhomthuoc', 'thuoc.drug_group_id', '=', 'nhomthuoc.id')
            ->orderBy('thuoc.created_at', 'asc')
            ->limit(4)
            ->get();
        return response()->json($drugs);
    }
    // 
    public function getUser(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $user = User::where([['email', '=', $email], ['password', '=', md5($password)]])->get();
        if (count($user) > 0)
            return response()->json($user[0]);
        else
            return 'null';
    }
    // 
    public function registerAccount(Request $request)
    {
        try {
            //code...
            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->province = $request->province;
            $user->district = $request->district;
            $user->ward = $request->ward;
            $user->detail_address = $request->detail_address;
            $user->email = $request->email;
            $user->email_verified_at = now();
            $user->password = md5($request->password);
            $user->save();
            return response()->json($user);
        } catch (\Throwable $th) {
            //throw $th;
            return 'error';
        }
    }
    // 
    public function getDrugByID(Request $request)
    {
        $drug = Drug::find($request->id);

        $receipt_detail = DB::table('ctphieunhap')
            ->where('drug_id', $request->id)
            ->selectRaw(DB::raw('SUM(SoLuong) as TongSoLuong'))
            ->get();
        $num_input = ($receipt_detail[0]->TongSoLuong == null) ? 0 : intval($receipt_detail[0]->TongSoLuong);

        $detail_bill = DB::table('cthd')
            ->where('drug_id', $request->id)
            ->selectRaw(DB::raw('SUM(SoLuong) as TongSoLuong'))
            ->get();
        $num_output = ($detail_bill[0]->TongSoLuong == null) ? 0 : intval($detail_bill[0]->TongSoLuong);
        return response()->json(['drug_info' => $drug, 'sum' => $num_input - $num_output]);
    }
    // 
    public function addItemToCart(Request $request)
    {
        $check_cart = DB::table('carts')
            ->where([['user_id', '=', $request->user_id], ['drug_id', '=', $request->drug_id]])
            ->get();
        if (count($check_cart) == 0) {
            $cart = new Cart;
            $cart->user_id = $request->user_id;
            $cart->drug_id = $request->drug_id;
            $cart->quantity = $request->quantity;
            $cart->save();
            return "The item is added";
        } else {
            return TestController::updateItemInCart($request);
        }
    }
    // 
    static public function updateItemInCart(Request $request)
    {
        DB::table('carts')
            ->where([['user_id', '=', $request->user_id], ['drug_id', '=', $request->drug_id]])
            ->update(['quantity' => $request->quantity]);
        return "The item in your cart is updated";
    }
    // 
    static public function getCart(Request $request)
    {
        $cart = DB::table('carts')
            ->selectRaw('carts.*, thuoc.GiaBan, thuoc.ChietKhau, thuoc.HinhAnh, thuoc.TenThuoc')
            ->join('thuoc', 'thuoc.id', '=', 'carts.drug_id')
            ->where('user_id', $request->user_id)
            ->get();
        return response()->json($cart);
    }
    // 
    static public function removeItemInCart(Request $request)
    {
        try {
            DB::table('carts')
                ->where('id', $request->cart_id)
                ->delete();
            return "success";
        } catch (\Throwable $th) {
            return "error";
        }
    }
    // 
    static public function getTransportUnit()
    {
        $transports = DB::table('donvivanchuyen')->get();
        return response()->json($transports);
    }
    // 
    static public function addBill(Request $request)
    {
        try {
            $bill = new Bill;
            $bill->user_id = $request->user_id;
            $bill->NgayThanhToan = now();
            $bill->save();
            // return $bill->id;
            $test = TestController::addDetailBill($bill->id, $request);
            $test = TestController::addShippingDetail($bill->id, $request);
            return "success";
        } catch (\Throwable $th) {
            throw $th;
            return "error";
        }
    }
    // 
    static public function addDetailBill($bill_id, Request $request)
    {
        $cart = json_decode($request->cart);
        foreach ($cart as $item) {
            $detail_bill = new DetailBill;
            $detail_bill->bill_id = $bill_id;
            $detail_bill->drug_id = $item->drug_id;
            $detail_bill->DonGia = $item->GiaBan;
            $detail_bill->ChietKhau = $item->ChietKhau;
            $detail_bill->SoLuong = $item->quantity;
            $detail_bill->save();
            TestController::removeItemInCartWithID($item->id);
        }
        return "ok";
    }
    // 
    static public function addShippingDetail($bill_id, Request $request)
    {
        $shipping_detail = new ShippingDetail;
        $shipping_detail->bill_id = $bill_id;
        $shipping_detail->transport_unit_id = $request->transport_unit_id;
        $shipping_detail->save();
        return "ok";
    }
    // 
    static public function removeItemInCartWithID($id)
    {
        try {
            DB::table('carts')
                ->where('id', $id)
                ->delete();
            return "success";
        } catch (\Throwable $th) {
            return "error";
        }
    }
    // 
    static public function getBillsForUser(Request $request) {
        $bills = DB::table('hoadon')
                ->where('user_id', $request->user_id)
                ->orderBy('created_at', 'desc')
                ->get();
        $result = [];
        foreach($bills as $bill) {
            $detail_bills = DB::table('cthd')
                    ->selectRaw('cthd.*, thuoc.TenThuoc, thuoc.HinhAnh')
                    ->join('thuoc', 'thuoc.id', '=', 'cthd.drug_id')
                    ->where('bill_id', $bill->id)
                    ->get();
            array_push($result, ['bill' => $bill, 'detail_bills' => $detail_bills]);
        }
        return response()->json($result);
    }
    // 
    static public function removeBillDetailbillShipping(Request $request) {
        DB::table('vanchuyen')
        ->where('bill_id', $request->bill_id)
        ->delete();
        DB::table('cthd')
        ->where('bill_id', $request->bill_id)
        ->delete();
        DB::table('hoadon')
        ->where('id', $request->bill_id)
        ->delete();
        return "success";
    }
    // 
    static public function getUserByToken(Request $request) {
        $email = $request->email;
        $token = $request->token;
        $user = User::where([['email', '=', $email], ['remember_token', '=', $token]])->get();
        if (count($user) > 0)
            return response()->json($user[0]);
        else
            return 'null';
    }
    // 
    static public function updateTokenUser(Request $request) {
        $email = $request->email;
        $token = $request->token;
        User::where('email', $email)->update(['remember_token' => $token]);
        return "success";
    }
    // 
    static public function updateUser(Request $request) {
        try {
            //code...
            $user = User::find($request->id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->province = $request->province;
            $user->district = $request->district;
            $user->ward = $request->ward;
            $user->detail_address = $request->detail_address;
            $user->email = $request->email;
            $user->email_verified_at = now();
            if ($user->password != $request->password)
                $user->password = md5($request->password);
            $user->update();
            // User::where('id', $request->id)->update($request->all());
            return response()->json(User::find($request->id));
        } catch (\Throwable $th) {
            //throw $th;
            return 'error';
        }
    }
}

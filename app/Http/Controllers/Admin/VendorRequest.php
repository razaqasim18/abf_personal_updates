<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\User;
use App\Models\UserAccountDetail;
use App\Models\Vendor;
use App\Models\VendorBalanceRequest;
use App\Models\VendorRequest as ModelsVendorRequest;
use App\Models\VendorWallet;
use App\Models\VendorWalletTransaction;
use App\Models\VendorWithdraw;
use App\Notifications\BalanceNotification;
use Illuminate\Http\Request;

use App\Notifications\VendorRequestApprovedNotification;
use App\Notifications\VendorRequestFailNotification;
use App\Notifications\WithDrawNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendorRequest extends Controller
{
    public function index()
    {
        $vendor = ModelsVendorRequest::orderBy('id', 'desc')->get();
        return view('admin.vendor.request.list', compact('vendor'));
    }

    public function detail($id)
    {
        $vendor = ModelsVendorRequest::findOrFail($id);
        $bank = Bank::select("*", 'banks.id AS id')->join("business_accounts", "business_accounts.bank_id", "=", "banks.id")->where('business_accounts.is_active', '1')->get();

        return view('admin.vendor.request.detail', compact('vendor', 'bank'));
    }

    public function delete($id)
    {
        $vendor = ModelsVendorRequest::findOrFail($id);
        $response = $vendor->delete();
        if ($response) {
            $json = [
                'type' => 1,
                'msg' => 'Data is deleted successfully',
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Something went wrong',
            ];
        }
        return redirect()->route('admin.vendor.request.withdraw.list');
    }

    public function status(Request $request)
    {
        $vendor = ModelsVendorRequest::findOrFail($request->id);
        $user = $vendor->user;
        $response = false;
        $other_data = [];
        $status = $request->status;
        $remarks = $request->remarks;
        if ($status == "2" || $status == "4") {
            $vendor->remarks = null;
            if ($status == "2") {
                $subject = "Vendor request is approved";
                $mailmsg =  "Your vendor request is approved.<br />Please make payment using the follwing link.<br />";
                $link = "/vendor/request/payment";
                $linktext = "Proceed Payment";
                $msgshow = "Application is accepted";
            } else {
                DB::transaction(function () use ($vendor, $status, $remarks, &$response, &$other_data) {

                    $user = User::findOrFail($vendor->user_id);
                    $user->is_vendor = 1;
                    $user->is_vendor_allowed = 1;
                    $user->save();

                    $data = json_decode($vendor->vendor_data);
                    $other_data = [
                        "previous_work" => $data->previous_work,
                        "business_withabf" => $data->business_withabf,
                        "describe_product" => $data->describe_product,
                        "career_goal" => $data->career_goal,
                        "experience" => $data->experience,
                        "delivery" => $data->delivery,
                        "market_business" => $data->market_business,
                        "herbel_product" => $data->herbel_product
                    ];
                    $is_order_handle_by_admin = ($data->delivery == "by_abf") ? 1 : 0;
                    $mainvendor = new Vendor();
                    $mainvendor->user_id = $vendor->user_id;
                    $mainvendor->business_name = $data->business_name;
                    $mainvendor->business_mail = $data->business_mail;

                    $mainvendor->category = $data->category;
                    $mainvendor->shop_phone = $data->shop_phone;
                    $mainvendor->mobile_phone = $data->mobile_phone;
                    $mainvendor->business_logo = $data->business_logo;
                    $mainvendor->shop_card = $data->shop_card;
                    $mainvendor->owner_image = $data->owner_image;
                    $mainvendor->website_link = $data->website_link;
                    $mainvendor->social_media_link = $data->social_media_link;
                    $mainvendor->business_address = $data->business_address;
                    $mainvendor->is_order_handle_by_admin = $is_order_handle_by_admin; 
                    $mainvendor->other_data = json_encode($other_data);
                    $mainvendor->save();

                    // Set $response to true upon successful completion
                    // $response = true;
                });

                $vendor->payment_approved_at = now();
                $subject = "Vendor payment is approved";
                $mailmsg =  "Your vendor payment is approved.<br />Congratulations.<br />";
                $link = "/login";
                $linktext = "Login";
                $msgshow = "Payment is accepted";
            }
            $user->notify(new VendorRequestApprovedNotification($subject, $mailmsg, $link, $linktext));
        } else {
            $vendor->remarks = $remarks;
            if ($status == '0') {
                $subject = "Vendor request is rejected";
                $mailmsg =  "Your vendor request is denied.<br /> <b>Admin remarks: <br/>" . $remarks . "</b><br/>";
                $msgshow = "Application is rejected";
                $link = "/vendor/request";
                $linktext = "Vendor Request";
            } else {
                $subject = "Vendor payment is rejected";
                $mailmsg =  "Your vendor payment is denied.<br /> <b>Admin remarks: <br/>" . $remarks . "</b><br/>";
                $msgshow = "Payment is rejected";
                $link = "/vendor/request/payment";
                $linktext = "Proceed Payment";
            }
            $user->notify(new VendorRequestFailNotification($subject, $mailmsg, $link, $linktext));
        }

        // save data
        $vendor->status = $status;
        $vendor->save();
        // Set $response to true upon successful completion
        $response = true;
        if ($response) {
            $json = [
                'type' => 1,
                'msg' => $msgshow,
            ];
        } else {

            $json = [
                'type' => 0,
                'msg' => 'Something went wrong',
            ];
        }
        return response()->json($json);
    }

    public function balanceList()
    {
        $balance = VendorBalanceRequest::select('*', 'vendor_balance_requests.id AS balanceid')->join('users', 'users.id', '=', 'vendor_balance_requests.user_id')->orderBy('vendor_balance_requests.id', 'DESC')->get();
        return view('admin.vendor.request.balance', [
            'balance' => $balance,
        ]);
    }

    public function balanceChangeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $balancerequest = VendorBalanceRequest::find($id);
        $user = User::find($balancerequest->user_id);

        if ($status == '1') {
            $balancerequest->status = $status;
            $balancerequest->approved_at = date("Y-m-d H:i:s");
            $wallet = VendorWallet::updateOrCreate(
                [
                    'user_id' => $balancerequest->user_id,
                ],
                ['amount' => DB::raw('amount + ' . $balancerequest->amount)]
            );
            $wallettransaction = VendorWalletTransaction::insert([
                'wallet_id' => $wallet->id,
                'user_id' => $balancerequest->user_id,
                'vendor_id' => $balancerequest->vendor_id,
                'amount' => $balancerequest->amount,
                'detail' => "Balance added requested by user approved by admin"
            ]);
            DB::beginTransaction();
            $balanceresponse = $balancerequest->save();
            if ($balanceresponse && $wallet && $wallettransaction) {
                $user->notify(new BalanceNotification($status, $balancerequest));
                DB::commit();
                $json = ['type' => 1, 'msg' => 'Balance request is approved'];

                //  remember to use dispatch for queue  dispatch($epinrequest->notify(new EpinRequestApprovedNotification("12983")));
            } else {
                DB::rollback();
                $json = ['type' => 0, 'msg' => 'Something went wrong'];
            }
        } else {
            $balancerequest->status = $status;
            $balanceresponse = $balancerequest->save();
            $user->notify(new BalanceNotification($status, $balancerequest));
            $json = ['type' => 1, 'msg' => 'Balance request is denied'];
        }
        return response()->json($json);
    }

    public function balanceDelete($id)
    {
        $response = VendorBalanceRequest::destroy($id);
        if ($response) {
            $json = [
                'type' => 1,
                'msg' => 'Data is deleted successfully',
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Something went wrong',
            ];
        }
        return response()->json($json);
    }

    public function balanceDetail($id)
    {
        $balance = VendorBalanceRequest::find($id);
        $user = User::where('id', $balance->user_id)->first();
        return view('admin.vendor.request.balance_detail', [
            'balance' => $balance,
            'user' => $user,
        ]);
    }

    public function balanceRemark(Request $request, $id)
    {
        $balance = VendorBalanceRequest::find($id);
        $response = $balance->update([
            'remarks' => $request->remark,
        ]);
        if ($response) {
            return redirect()
                ->route('admin.vendor.request.balance.detail', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.vendor.request.balance.detail', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function withdrawList()
    {
        $withdraw = VendorWithdraw::select('*', 'vendor_withdraws.id AS withdrawsid', 'users.id AS userid')->join('users', 'users.id', '=', 'vendor_withdraws.user_id')->orderBy('vendor_withdraws.id', 'DESC')->get();
        return view('admin.vendor.request.withdraw', [
            'withdraw' => $withdraw,
        ]);
    }

    public function withdrawChangeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $withdrawrequest = VendorWithdraw::find($id);
        $user = User::find($withdrawrequest->user_id);

        if ($status == '1') {
            $withdrawrequest->cashout_amount = $withdrawrequest->requested_amount - ($withdrawrequest->requested_amount * ($withdrawrequest->transectioncharges / 100));
            $withdrawrequest->status = $status;
            $withdrawrequest->approved_id = Auth::guard('admin')->user()->id;
            $withdrawrequest->approved_at = date("Y-m-d H:i:s");

            $wallet = VendorWallet::where('user_id', $withdrawrequest->user_id)->first();
            $walleresponse = $wallet->update(['amount' => DB::raw('amount - ' . $withdrawrequest->requested_amount)]);
            $wallettransaction = VendorWalletTransaction::insert([
                'wallet_id' => $wallet->id,
                'user_id' => $wallet->user_id,
                'vendor_id' => $wallet->vendor_id,
                'amount' => $withdrawrequest->requested_amount,
                'status' => '0',
                'detail' => "Balance withdraw requested by user approved by admin"
            ]);

            $withdrawresponse = $withdrawrequest->save();
            if ($withdrawresponse && $walleresponse && $wallettransaction) {
                $user->notify(new WithDrawNotification($status, $withdrawrequest));
                DB::commit();
                $json = ['type' => 1, 'msg' => 'Withdrawal request is approved'];
                //  remember to use dispatch for queue  dispatch($epinrequest->notify(new EpinRequestApprovedNotification("12983")));
            } else {
                DB::rollback();
                $json = ['type' => 0, 'msg' => 'Something went wrong'];
            }
        } else {
            $withdrawrequest->status = $status;
            $withdrawresponse = $withdrawrequest->save();
            $user->notify(new WithDrawNotification($status, $withdrawrequest));
            $json = ['type' => 1, 'msg' => 'Withdrawal request is denied'];
        }
        return response()->json($json);
    }

    public function withdrawDelete($id)
    {
        $response = VendorWithdraw::destroy($id);
        if ($response) {
            $json = [
                'type' => 1,
                'msg' => 'Data is deleted successfully',
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Something went wrong',
            ];
        }
        return response()->json($json);
    }

    public function withdrawDetail($id)
    {
        // $withdraw = Withdraw::join('users')
        // ->where('withdraws',$id)->first();
        $withdraw = VendorWithdraw::findorFail($id);
        $useraccount = UserAccountDetail::join('banks', 'banks.id', '=', 'user_account_details.bank_id')->where('user_id', $withdraw->user_id)->get();
        $user = User::where('id', $withdraw->user_id)->first();
        return view('admin.vendor.request.withdraw_detail', [
            'withdraw' => $withdraw,
            'user' => $user,
            'useraccount' => ($useraccount) ? $useraccount : [],
        ]);
    }

    public function withdrawApprove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'transectionid' => 'required|unique:withdraws',
            'date' => 'required',
            'amount' => 'required',
            'image' => 'mimes:jpeg,png,jpg,gif|max:10240',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'type' => 0,
                'validator_error' => 1,
                'errors' => $validator->errors(),
            ]);
        }
        $image = null;
        if (!empty($request->file('image'))) {
            $image = time() . '.' . $request->file('image')->extension();
            $request
                ->file('image')
                ->move(base_path('uploads/withdraw_proof'), $image);
        }

        $id = $request->id;
        $status = '1';
        $withdrawrequest = VendorWithdraw::find($id);
        $user = User::find($withdrawrequest->user_id);

        $withdrawrequest->cashout_amount = $withdrawrequest->requested_amount - ($withdrawrequest->requested_amount * ($withdrawrequest->transectioncharges / 100));
        $withdrawrequest->transectiondate = $request->date;
        $withdrawrequest->transectionid = $request->transectionid;
        $withdrawrequest->proof = $image;
        $withdrawrequest->status = $status;
        $withdrawrequest->approved_id = Auth::guard('admin')->user()->id;
        $withdrawrequest->approved_at = date("Y-m-d H:i:s");

        $wallet = VendorWallet::where('user_id', $withdrawrequest->user_id)->first();
        $walleresponse = $wallet->update(['amount' => DB::raw('amount - ' . $withdrawrequest->requested_amount)]);
        $wallettransaction = VendorWalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'user_id' => $wallet->user_id,
            'vendor_id' => $wallet->vendor_id,
            'amount' => $withdrawrequest->requested_amount,
            'status' => '0',
            'detail' => "Balance withdraw requested by user approved by admin"
        ]);
        DB::beginTransaction();
        $withdrawresponse = $withdrawrequest->save();
        if ($withdrawresponse && $walleresponse && $wallettransaction) {
            $user->notify(new WithDrawNotification($status, $withdrawrequest));
            DB::commit();
            $json = ['type' => 1, 'msg' => 'Withdrawal request is approved'];
            //  remember to use dispatch for queue  dispatch($epinrequest->notify(new EpinRequestApprovedNotification("12983")));
        } else {
            DB::rollback();
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function withdrawRemark(Request $request, $id)
    {
        $withdrawrequest = VendorWithdraw::find($id);
        $response = $withdrawrequest->update([
            'remarks' => $request->remark,
        ]);
        if ($response) {
            return redirect()
                ->route('admin.vendor.request.withdraw.detail', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.vendor.request.withdraw.detail', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function getUserPaymentInformation($userid)
    {
        $user = UserAccountDetail::select('*', 'banks.name AS bankName', 'user_account_details.account_name AS userAccountHolderName', 'user_account_details.account_number AS userAccountNumber', 'user_account_details.account_iban AS useraccountIBAN')
            ->join('banks', 'banks.id', '=', 'user_account_details.bank_id')->where('user_id', $userid)->first();
        if ($user) {
            $json = [
                'type' => 1,
                'object' => $user,
            ];
        } else {
            $json = [
                'type' => 0,
                'msg' => 'Not payment information found',
            ];
        }
        return response()->json($json);
    }
}

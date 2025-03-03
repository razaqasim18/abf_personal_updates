<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use App\Models\BalanceRequest;
use App\Models\EpinRequest;
use App\Models\User;
use App\Models\UserAccountDetail;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Models\Withdraw;
use App\Notifications\BalanceNotification;
use App\Notifications\EpinRequestApprovedNotification;
use App\Notifications\EpinRequestFailedNotification;
use App\Notifications\WithDrawNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RequestController extends Controller
{
    public function epinList()
    {
        $epin = EpinRequest::orderBy('id', 'DESC')->get();
        return view('admin.request.epin', [
            'epin' => $epin,
        ]);
    }

    public function epinChangeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $epinrequest = EpinRequest::find($id);
        $uuid = CustomHelper::createNewEpin();
        if ($status == '1') {
            //  remember to use dispatch for queue
            $epinrequest->notify(new EpinRequestApprovedNotification($uuid, $epinrequest));
            // dispatch($epinrequest->notify(new EpinRequestApprovedNotification("12983")));
            $json = [
                'type' => 1,
                'msg' => 'RG Code Transection is approved',
            ];
        } else {
            //    remember to use dispatch for queue
            $epinrequest->notify(new EpinRequestFailedNotification($epinrequest));
            $json = [
                'type' => 1,
                'msg' => 'RG Code Transection is denied',
            ];
        }
        $epinrequest->epin = $uuid;
        $epinrequest->status = $status;
        $epinrequest->approved_at = date("Y-m-d H:i:s");
        $epinrequest->save();
        return response()->json($json);
    }

    public function epinDelete($id)
    {
        $response = EpinRequest::destroy($id);
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

    public function epinDetail($id)
    {
        // $epin = EpinRequest::find($id)->leftJoin('users','users.id','=','epin_requests.allotted_to_user_id');
        $epin = EpinRequest::find($id);
        $user = array();
        if ($epin->allotted_to_user_id != null) {
            $user = User::where('id', $epin->allotted_to_user_id)->first();
        }
        return view('admin.request.epin_detail', [
            'epin' => $epin,
            'user' => $user,
        ]);
    }

    public function balanceList()
    {
        $balance = BalanceRequest::select('*', 'balance_requests.id AS balanceid')->join('users', 'users.id', '=', 'balance_requests.user_id')->orderBy('balance_requests.id', 'DESC')->get();
        return view('admin.request.balance', [
            'balance' => $balance,
        ]);
    }

    public function balanceChangeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $balancerequest = BalanceRequest::find($id);
        $user = User::find($balancerequest->user_id);

        if ($status == '1') {
            $balancerequest->status = $status;
            $balancerequest->approved_at = date("Y-m-d H:i:s");
            $wallet = Wallet::updateOrCreate(
                ['user_id' => $balancerequest->user_id],
                ['amount' => DB::raw('amount + ' . $balancerequest->amount)]
            );
            $wallettransaction = WalletTransaction::insert([
                'wallet_id' => $wallet->id,
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
        $response = BalanceRequest::destroy($id);
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
        $balance = BalanceRequest::find($id);
        $user = User::where('id', $balance->user_id)->first();
        return view('admin.request.balance_detail', [
            'balance' => $balance,
            'user' => $user,
        ]);
    }

    public function balanceRemark(Request $request, $id)
    {
        $balance = BalanceRequest::find($id);
        $response = $balance->update([
            'remarks' => $request->remark,
        ]);
        if ($response) {
            return redirect()
                ->route('admin.request.balance.detail', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.request.balance.detail', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function withdrawList()
    {
        $withdraw = Withdraw::select('*', 'withdraws.id AS withdrawsid', 'users.id AS userid')->join('users', 'users.id', '=', 'withdraws.user_id')->orderBy('withdraws.id', 'DESC')->get();
        return view('admin.request.withdraw', [
            'withdraw' => $withdraw,
        ]);
    }

    public function withdrawChangeStatus(Request $request)
    {
        $id = $request->id;
        $status = $request->status;
        $withdrawrequest = Withdraw::find($id);
        $user = User::find($withdrawrequest->user_id);

        if ($status == '1') {
            $withdrawrequest->cashout_amount = $withdrawrequest->requested_amount - ($withdrawrequest->requested_amount * ($withdrawrequest->transectioncharges / 100));
            $withdrawrequest->status = $status;
            $withdrawrequest->approved_id = Auth::guard('admin')->user()->id;
            $withdrawrequest->approved_at = date("Y-m-d H:i:s");

            $wallet = Wallet::where('user_id', $withdrawrequest->user_id)->first();
            $walleresponse = $wallet->update(['amount' => DB::raw('amount - ' . $withdrawrequest->requested_amount)]);
            $wallettransaction = WalletTransaction::insert([
                'wallet_id' => $wallet->id,
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
        $response = Withdraw::destroy($id);
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
               return redirect()->route('admin.request.withdraw.list');
    }

    public function withdrawDetail($id)
    {
        // $withdraw = Withdraw::join('users')
        // ->where('withdraws',$id)->first();
        $withdraw = Withdraw::find($id);
        $useraccount = UserAccountDetail::join('banks', 'banks.id', '=', 'user_account_details.bank_id')->where('user_id', $withdraw->user_id)->get();
        $user = User::where('id', $withdraw->user_id)->first();
        return view('admin.request.withdraw_detail', [
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
        $withdrawrequest = Withdraw::find($id);
        $user = User::find($withdrawrequest->user_id);

        $withdrawrequest->cashout_amount = $withdrawrequest->requested_amount - ($withdrawrequest->requested_amount * ($withdrawrequest->transectioncharges / 100));
        $withdrawrequest->transectiondate = $request->date;
        $withdrawrequest->transectionid = $request->transectionid;
        $withdrawrequest->proof = $image;
        $withdrawrequest->status = $status;
        $withdrawrequest->approved_id = Auth::guard('admin')->user()->id;
        $withdrawrequest->approved_at = date("Y-m-d H:i:s");

        $wallet = Wallet::where('user_id', $withdrawrequest->user_id)->first();
        $walleresponse = $wallet->update(['amount' => DB::raw('amount - ' . $withdrawrequest->requested_amount)]);
        $wallettransaction = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
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
        $withdrawrequest = Withdraw::find($id);
        $response = $withdrawrequest->update([
            'remarks' => $request->remark,
        ]);
        if ($response) {
            return redirect()
                ->route('admin.request.withdraw.detail', $id)
                ->with('success', 'Data is updated successfully');
        } else {
            return redirect()
                ->route('admin.request.withdraw.detail', $id)
                ->with('error', 'Something went wrong');
        }
    }

    public function getUserPaymentInformation($userid)
    {
        $user = UserAccountDetail::select('*', 'banks.name AS bankName', 'user_account_details.account_holder_name AS userAccountHolderName', 'user_account_details.account_number AS userAccountNumber', 'user_account_details.account_iban AS useraccountIBAN')
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

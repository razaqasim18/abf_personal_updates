<?php

namespace App\Http\Controllers;

use App\Helpers\SettingHelper;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserAccountDetail;
use App\Models\Withdraw;
use App\Models\FundTransfer;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Rules\UserAmount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use DB;

class TransferController extends Controller
{
    public function add()
    {
        $user = User::where('is_blocked', 0)->where('is_deleted', 0)->where('id', '!=', Auth()->user()->id)->get();
        return view('user.transfer.add', [
            'user' => ($user) ? $user : [],
        ]);
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required', 'numeric', new UserAmount],
        ]);

        DB::beginTransaction();

        $fundtransfer = new FundTransfer();
        $fundtransfer->user_id = Auth::guard('web')->user()->id;
        $fundtransfer->receiver_id = $request->user;
        $fundtransfer->amount = $request->amount;
        $response = $fundtransfer->save();

        // deducted from sender amount
        $wallet = Wallet::updateOrCreate(
            ['user_id' => Auth::guard('web')->user()->id],
            ['amount' => DB::raw('amount - ' . $request->amount)]
        );
        WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'is_gift' => 0,
            'status' => 0,
            'detail' => 'transfer to user ABF-' . $request->user,
        ]);

        // receiver_id from sender amount
        $wallet = Wallet::updateOrCreate(
            ['user_id' => $request->user],
            ['amount' => DB::raw('amount + ' . $request->amount)]
        );
        WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'is_gift' => 0,
            'status' => 1,
            'detail' => 'received from user ABF-' . Auth::guard('web')->user()->id,
        ]);

        if ($response) {
            DB::commit();
            return redirect()->route('transfer.add')->with('success', "Fund is transfer to user");
        } else {
            DB::rollback();
            return redirect()->route('transfer.add')->with('error', "Something went wrong please try again");
        }
    }

    public function listSend()
    {
        $transfer = FundTransfer::where('user_id', Auth::user()->id)->orderBy('created_at', "DESC")->get();
        return view('user.transfer.list', ['transfer' => $transfer, "is_send" => '1']);
    }

    public function listReceive()
    {
        $transfer = FundTransfer::where('receiver_id', Auth::user()->id)->orderBy('created_at', "DESC")->get();
        return view('user.transfer.list', ['transfer' => $transfer, "is_send" => '0']);
    }

    public function rewardAdd()
    {
        $user = User::where('is_blocked', 0)->where('is_deleted', 0)->where('id', '!=', Auth()->user()->id)->get();
        return view('user.transfer.reward_add', [
            'user' => ($user) ? $user : [],
        ]);
    }

    public function rewardInsert(Request $request)
    {
        $this->validate($request, [
            'amount' => ['required', 'numeric', new UserAmount],
        ]);

        DB::beginTransaction();

        $wallet = Wallet::updateOrCreate(
            ['user_id' => Auth::guard('web')->user()->id],
            [
                'amount' => DB::raw('amount - ' . $request->amount),
                'gift' => DB::raw('gift + ' . $request->amount)
            ],
        );
        $responseDeductfromWallet = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'is_gift' => 0,
            'status' => 0,
            'is_reward_deducted' => 1,
            'detail' => 'transfer from wallet to reward',
        ]);

        $responseAddtoReward = WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $request->amount,
            'status' => 1,
            'detail' => 'received from wallet to reward',
            'is_gift' => 1,
            'reward_type' => 5,
        ]);

        if ($wallet && $responseDeductfromWallet && $responseAddtoReward) {
            DB::commit();
            return redirect()->route('transfer.reward.add')->with('success', "Fund is transfer from wallet to reward");
        } else {
            DB::rollback();
            return redirect()->route('transfer.reward.add')->with('error', "Something went wrong please try again");
        }
    }

    public function rewardList()
    {
        $transfer = Wallet::join('wallet_transactions', 'wallet_transactions.wallet_id', '=', 'wallets.id', 'inner')
            ->where('wallets.user_id', Auth::user()->id)
            ->where('wallet_transactions.reward_type', 5)
            ->orderBy('wallet_transactions.created_at', "DESC")->get();
        return view('user.transfer.reward_list', ['transfer' => $transfer]);
    }
}

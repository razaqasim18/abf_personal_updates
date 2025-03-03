<?php

namespace App\Http\Controllers\Auth;

use App\Helpers\SettingHelper;
use App\Http\Controllers\Controller;
use App\Jobs\assignPointsToUserAndParentsJob;
use App\Models\Admin;
use App\Models\EpinRequest;
use App\Models\Point;
use App\Models\PointTransaction;
use App\Models\TeamReward;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Rules\verifyEPINByEmail;
use App\Rules\verifyEPINByPin;
use App\Rules\verifyEPINsame;
use App\Rules\verifySponsor;
use DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = 'login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', new verifyEPINByEmail],
            'password' => ['required', 'string', 'min:8'],
            'epin' => ['required', new verifyEPINByPin, new verifyEPINsame($data['email'])],
            'sponsor' => ['required', new verifySponsor],
            'phone' => ['required'],
            'cnic' => ['required'],
            'dob' => ['required'],
            'cnic_image_front' => ['required', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
            'cnic_image_back' => ['required', 'mimes:jpeg,png,jpg,gif', 'max:10240'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        if (!empty($data['cnic_image_front'])) {
            $cnic_image_front = time() . '_front.' . $data['cnic_image_front']->extension();
            $data['cnic_image_front']->move(base_path('uploads/cnic'), $cnic_image_front);
        }

        if (!empty($data['cnic_image_back'])) {
            $cnic_image_back = time() . '_back.' . $data['cnic_image_back']->extension();
            $data['cnic_image_back']->move(base_path('uploads/cnic'), $cnic_image_back);
        }

        DB::beginTransaction();

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->cnic = $data['cnic'];
        $user->sponserid = $data['sponsor'];
        $user->phone = $data['phone'];
        $user->dob = $data['dob'];
        $user->cnic_image_front = $cnic_image_front;
        $user->cnic_image_back = $cnic_image_back;
        $responseuser = $user->save();
        $EpinRequest = EpinRequest::where('epin', $data['epin'])
            ->where('email', $data['email'])
            ->where('status', 1)
            ->whereNull('allotted_to_user_id')
            ->first();
        $EpinRequest->allotted_to_user_id = $user->id;
        $responseepin = $EpinRequest->save();

        if (
            $responseuser && $responseepin
        ) {
            DB::commit();

            $msg = "New User is registered";
            $type = 1;
            $link = "admin/client/detail/" . $user->id;
            $detail = "New User is registered by ID of ABF-" . $user->id;
            $admin = Admin::find(1);
            $adminnotification = new AdminNotification($msg, $type, $link, $detail);
            Notification::send($admin, $adminnotification);


            //giving register reward to sponsor
            $amount = (SettingHelper::getSettingValueBySLug('register_reward')) ? SettingHelper::getSettingValueBySLug('register_reward') : 0;
            if ($amount) {
                $wallet = Wallet::updateOrCreate(
                    ['user_id' => $data['sponsor']],
                    ['amount' => DB::raw("amount + $amount")]
                );

                WalletTransaction::insert([
                    'wallet_id' => $wallet->id,
                    'amount' => $amount,
                    'status' => 1,
                    'is_gift' => 1,
                    'detail' => 'new user registration',
                    'reward_type' => '1',
                ]);
            }


            //assign team reward to sponsor
            // $myteam = User::select(DB::raw('COUNT(id) AS count'))
            //     ->where('sponserid', $data['sponsor'])
            //     ->whereYear('created_at', '>=', '2023')
            //     ->whereMonth('created_at', '>=', '11')
            //     ->first();
            $myteam = User::select(DB::raw('COUNT(id) AS count'))
            ->where('sponserid', $data['sponsor'])
            ->where(function ($query) {
                $query->whereYear('created_at', '>', '2023')
                    ->orWhere(function ($query) {
                        $query->whereYear('created_at', '2023')->whereMonth('created_at', '>=', '11');
                    });
            })
            ->first();

            $teamreward = TeamReward::where('members', $myteam->count)->first();
            $teamrewardresponse = 1;
            if ($teamreward) {
                $teamrewardresponse = 0;
                $wallet = Wallet::updateOrCreate(
                    ['user_id' => $data['sponsor']],
                    ['gift' => DB::raw("gift + $teamreward->reward")]
                );
                WalletTransaction::insert([
                    'wallet_id' => $wallet->id,
                    'amount' =>  $teamreward->reward,
                    'status' => 1,
                    'is_gift' => 1,
                    'detail' => 'team reward',
                    'reward_type' => '3',
                ]);
                $teamrewardresponse = 1;
            }


            // assign Referral points to sponsor
            $referred_points = SettingHelper::getSettingValueBySLug('referred_points');
            $referredpointsresponse = 1;
            if ($referred_points) {
                $referredpointsresponse = 0;
                $point = Point::updateOrCreate(
                    ['user_id' => $data['sponsor']],
                    ['point' => DB::raw('point + ' . $referred_points)],
                );
                PointTransaction::insert([
                    'user_id' => $data['sponsor'],
                    'point_id' => $point->id,
                    'point' => $referred_points,
                    'status' => 1,
                    'is_child' => 0,
                ]);
                $referredpointsresponse = 1;

                $assignPointsToUserAndParentsJob = new assignPointsToUserAndParentsJob($data['sponsor'], $data['sponsor'], $referred_points);
                Queue::push($assignPointsToUserAndParentsJob);
            }

            return $user;
        } else {
            DB::rollback();
            return false;
        }
    }
}

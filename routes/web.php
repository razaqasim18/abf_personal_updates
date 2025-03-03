
<?php

use App\Helpers\CartHelper;
use App\Helpers\CustomHelper;
use App\Helpers\SettingHelper;
use App\Http\Controllers\Admin\VendorController;
use App\Models\Admin;
use App\Models\Commission;
use App\Models\EpinRequest;
use App\Models\Point;
use App\Models\PSPReward;
use App\Models\User;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Notifications\AdminNotification;
use App\Notifications\EpinRequestApprovedNotification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
 */

//  commands to run

// composer update
// composer dump-autoload
// php artisan clear-compiled
// php artisan config:cache
// php artisan cache:clear
// php artisan optimize:clear
// php artisan route:clear
// php artisan migrate:fresh --seed
// php artisan queue:work
// php artisan schedule:run
// * * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
//*    *    *    *    *    curl -s https://tryptu.digitallinkcard.xyz/public/schedule-run
//*    *    *    *    *    curl -s https://tryptu.digitallinkcard.xyz/public/queue-work

Route::get('/refresh-csrf-token', function () {
    return response()->json(['csrf_token' => csrf_token()]);
})->name("refresh-csrf-token");

Route::get('/check', function () {
    $referred_points = SettingHelper::getSettingValueBySLug('referred_points');
    if($referred_points){
        echo "true";
    } else {
        echo "false";
    }
});

Route::get('/user-psp/{id}', function ($userid) {
    //psp rewad
    // $user = 1325;
    $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $userid)->first();

    //old
    // $personalpoints = DB::table('point_transactions')
    //                 ->select(DB::raw("SUM(point) as point"))
    //                 ->where('status', '1')
    //                 ->where('user_id', $user->userid)
    //                 ->where('is_child', 0)
    //                 ->whereMonth('created_at', '>=', '11')
    //                 ->whereYear('created_at', '>=', '2023')
    //                 ->first();
    // old

    // new
    $personalpoints = DB::table('point_transactions')
        ->select(DB::raw("SUM(point) as point"))
        ->where('status', '1')
        ->where('user_id', $user->userid)
        ->where('is_child', 0)
        ->where(function ($query) {
            $query->whereYear('created_at', '>', '2023')
                ->orWhere(function ($query) {
                    $query->whereYear('created_at', '2023')->whereMonth('created_at', '>=', '11');
                });
        })
        ->first();
    echo "user pionts-" . $personalpoints->point;

    $userpersonalpoints = ($personalpoints) ? $personalpoints->point : 0;

    $pspreward  =  PSPReward::all();
    foreach ($pspreward as $psprewardrow) {
        if ($userpersonalpoints >= $psprewardrow->points) {
            if ($user->psp_id == null || $user->psp_id < $psprewardrow->id) {
                Point::updateOrCreate(
                    ['user_id' => $user->userid],
                    ['psp_id' => $psprewardrow->id]
                );
                DB::transaction(function () use ($user, $psprewardrow) {
                    $wallet = Wallet::updateOrCreate(
                        ['user_id' => $user->userid],
                        ['gift' => DB::raw('gift + ' . $psprewardrow->reward)]
                    );
                    WalletTransaction::insert([
                        'wallet_id' => $wallet->id,
                        'amount' => $psprewardrow->reward,
                        'status' => 1,
                        'is_gift' => 1,
                        'detail' => 'PSP reward',
                        'reward_type' => '4',
                    ]);
                });
            }
        }
    }
    // psp reward end

});

Route::get('/migrate', function () {
    Artisan::call('migrate');
    echo "migrate";
});

Route::get('/migrate-rollback', function () {
    Artisan::call('migrate:rollback');
    echo "migrate rollback";
});

Route::get('/cache-clear', function () {
    Artisan::call('config:cache');
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('route:clear');
    echo "cache-clear";
});

Route::get('/queue-work', function () {
    info("Queue worker executed " . now());
    Artisan::call('queue:work');
    echo "queue-work";
});

Route::get('/schedule-run', function () {
    info("schedule run executed " . now());
    Artisan::call('schedule:run');
    echo "schedule-run";
});

Route::get('/calculate-user-commission/{id}', function ($userid) {
    $childpoints = CustomHelper::calculateChildPoint($userid);
    $childcommision = CustomHelper::calculateCommission($userid);
    echo "Points child " . $childpoints . "<br/>";
    echo "Commission " . $childcommision . "<br/>";
});

Route::get('/add-user-commission/{id}', function ($userid) {
    $childpoints = CustomHelper::calculateChildPoint($userid);
    $childcommision = CustomHelper::calculateCommission($userid);
    if ($childcommision) {
        $wallet = Wallet::updateOrCreate(
            ['user_id' => $userid],
            ['amount' => DB::raw('amount + ' . $childcommision)]
        );
        WalletTransaction::insert([
            'wallet_id' => $wallet->id,
            'amount' => $childcommision,
            'status' => 2,
            'detail' => 'comission',
        ]);
    }
    echo "commision given";
});

Route::get('/calculate-users-commission', function () {

    $output = '';
    $user = User::where('is_blocked', '0')->where('is_deleted', '0')->get();
    foreach ($user as $row) {
        $childcommision = CustomHelper::calculateCommission($row->id);
        // $output .= "commission " . $childcommision . " - user : " . $row->id . "</br>";
        // if ($childcommision) {
        //     $wallet = Wallet::updateOrCreate(
        //         ['user_id' => $row->id],
        //         ['amount' => DB::raw('amount + ' . $childcommision)]
        //     );
        //     WalletTransaction::insert([
        //         'wallet_id' => $wallet->id,
        //         'amount' => $childcommision,
        //         'status' => 2,
        //         'detail' => 'comission',
        //     ]);
        // }
        echo $childcommision . " - user : " . $row->id . "</br>";;
    }
    // $currentDate = Carbon::now();
    // // $currentDate = Carbon::now();
    // $currentMonth = $currentDate->month;
    // $currentYear = $currentDate->year;
    // $filePath =  $currentYear . "-" .  $currentMonth  . '.txt'; // File path where you want to store the output
    // Storage::disk('local')->put($filePath, $output);
});

// change user rank
Route::get('/rank/{id}', function ($userid) {
    // $singleUserRankJob = new singleUserRankJob($id);
    // Queue::push($singleUserRankJob);

    $user = User::select('*', 'users.id AS userid')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->where('users.id', $userid)->first();
    if ($user) {
        $commission = Commission::all();
        foreach ($commission as $commissionrow) {
            if ($user->point >= $commissionrow->points) {
                Point::updateOrCreate(
                    ['user_id' => $userid],
                    ['commission_id' => $commissionrow->id]
                );

                $pointtransactions = DB::table('point_transactions')
                    ->select(DB::raw("SUM(point) as point"))
                    ->where('status', '1')
                    ->where('user_id', $userid)
                    ->where('is_child', 0)
                    // ->whereMonth('created_at', '=', $currentMonth)
                    // ->whereYear('created_at', '=', $currentYear)
                    ->first();
                echo " User Points: " . $pointtransactions->point . "</br>";;

                if ($user->commission_id == null || $user->commission_id < $commissionrow->id) {

                    if ($pointtransactions->point >= 100) {
                        DB::transaction(function () use ($userid, $commissionrow) {
                            $wallet = Wallet::updateOrCreate(
                                ['user_id' => $userid],
                                ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                            );
                            WalletTransaction::insert([
                                'wallet_id' => $wallet->id,
                                'amount' => $commissionrow->gift,
                                'is_gift' => 1,
                                'status' => 1,
                            ]);
                        });
                    }
                }
            }
        }
    }
});

//change all user rank
Route::get('/all/rank', function () {
    $user = User::select('commission_id', 'users.id AS userid', 'point')->join('points', 'points.user_id', '=', 'users.id')->where('users.is_blocked', '0')->get();
    $commission = Commission::all();
    foreach ($user as $userrow) {
        foreach ($commission as $commissionrow) {
            if ($userrow->point >= $commissionrow->points) {
                Point::updateOrCreate(
                    ['user_id' => $userrow->userid],
                    ['commission_id' => $commissionrow->id]
                );
                if ($userrow->commission_id == null || $userrow->commission_id < $commissionrow->id) {
                    DB::transaction(function () use ($userrow, $commissionrow) {
                        $wallet = Wallet::updateOrCreate(
                            ['user_id' => $userrow->userid],
                            ['gift' => DB::raw('gift + ' . $commissionrow->gift)]
                        );
                        WalletTransaction::insert([
                            'wallet_id' => $wallet->id,
                            'amount' => $commissionrow->gift,
                            'is_gift' => 1,
                            'status' => 1,
                        ]);
                    });
                }
            }
        }
    }
});

//

Route::get('/monthly/commission/list', function () {

    $user = User::where('is_blocked', '0')->where('is_deleted', '0')->get();
    foreach ($user as $row) {
        // $childpoints = self::calculateChildPoint($row->id);
        // $childcommision = self::calculateCommission($row->id);

        $point = Point::where('user_id', $row->id)->first();
        $calculatedcommision = 0;
        if ($point) {
            if ($point->commission_id != null) {
                $commission = Commission::findorFail($point->commission_id);
                $currentDate = Carbon::now()->subMonth();
                // $currentDate = Carbon::now();
                $currentMonth = $currentDate->month;
                $currentYear = $currentDate->year;

                //current user points old this not return null if no record found
                $usertotalpoint = DB::table('point_transactions')
                    ->select(DB::raw("SUM(point) as totalpoint"))
                    ->where('status', '1')
                    ->where('is_child', '0')
                    ->where('user_id', $row->id)
                    ->whereMonth('created_at', '=', $currentMonth)
                    ->whereYear('created_at', '=', $currentYear)
                    ->first();

                // echo  "User personal point " . $usertotalpoint->totalpoint . "<br/>";
                $childuser = DB::table('users')
                    ->where('sponserid', $row->id)
                    ->where('is_deleted', 0)
                    ->where('is_blocked', 0)
                    ->get();

                $childcommision = $nonchildcommission = 0;
                foreach ($childuser as $innerrow) {

                    $childpoint = Point::where('user_id', $innerrow->id)->first();
                    $childpointtransactions = DB::table('point_transactions')
                        ->select(DB::raw("SUM(point) as point"))
                        ->where('status', '1')
                        ->where('user_id', $innerrow->id)
                        ->whereMonth('created_at', '=', $currentMonth)
                        ->whereYear('created_at', '=', $currentYear)
                        ->first();

                    if ($childpoint) {
                        if ($childpoint->commission_id != null && $childpoint->commission_id < $point->commission_id) {
                            $childcommission = Commission::findorFail($childpoint->commission_id);

                            if ($commission->ptp == null || $usertotalpoint->totalpoint == null) {
                                $calculatedcommision = ($childpointtransactions->point * (($commission->profit - $childcommission->profit) / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                                $childcommision = $childcommision + $calculatedcommision;
                            }

                            if ($commission->ptp == null || $usertotalpoint->totalpoint >= $commission->ptp) {
                                $calculatedcommision = ($childpointtransactions->point * (($commission->profit - $childcommission->profit) / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                                $childcommision = $childcommision + $calculatedcommision;
                            }
                        } else if ($childpoint->commission_id != null && $childpoint->commission_id >= $point->commission_id) {
                            $childcommision = $childcommision + 0;
                        } else {
                            if ($commission->ptp == null || $usertotalpoint->totalpoint >= $commission->ptp) {
                                $calculatedcommision = ($childpointtransactions->point * ($commission->profit / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                                $nonchildcommission = $nonchildcommission + $calculatedcommision;
                            } else {
                                $calculatedcommision = ($childpointtransactions->point * ($commission->profit / 100)) * SettingHelper::getSettingValueBySLug('money_rate');
                                $calculatedcommision = $calculatedcommision - ($calculatedcommision * (SettingHelper::getSettingValueBySLug('admin_charges')) / 100);
                                $nonchildcommission = $nonchildcommission + $calculatedcommision;
                            }
                        }
                    }
                }
                $calculatedcommision = $childcommision + $nonchildcommission;
            }
        }
        $childcommision = $calculatedcommision;
        echo $row->id . " point- " . $childpoint . " commission-" . $childcommision . "<br/>";
    }
});

// home pages
Route::get('/', [App\Http\Controllers\FrontController::class, 'index'])->name('welcome');
Route::get('/home', [App\Http\Controllers\FrontController::class, 'index'])->name('home');

//static pages
Route::get('/privacy-policy', [App\Http\Controllers\FrontController::class, 'privacyPolicy'])->name('privacy.policy');
Route::get('/terms-condition', [App\Http\Controllers\FrontController::class, 'termCondition'])->name('terms.condition');
Route::get('/contact-us', [App\Http\Controllers\FrontController::class, 'contactUs'])->name('contact.us');
Route::get('/about-us', [App\Http\Controllers\FrontController::class, 'aboutUs'])->name('about.us');
Route::get('/success-stories', [App\Http\Controllers\FrontController::class, 'successStories'])->name('success.stories');


//blogs
Route::get('/blogs', [App\Http\Controllers\FrontController::class, 'blogs'])->name('blogs');
Route::get('/blog/{id}', [App\Http\Controllers\FrontController::class, 'blogSingle'])->name('blog.single');

// shop
Route::get('/shop', [App\Http\Controllers\FrontController::class, 'shop'])->name('shop');
Route::get('/shop/search', [App\Http\Controllers\FrontController::class, 'shopSearch'])->name('shop.search');

Route::get('/other-brand', [App\Http\Controllers\FrontController::class, 'otherBrand'])->name('other.brand');
Route::get('/other-brand/search', [App\Http\Controllers\FrontController::class, 'otherBrandSearch'])->name('other.brand.search');

Route::get('/customize', [App\Http\Controllers\FrontController::class, 'customize'])->name('customize');
Route::get('/customize/search', [App\Http\Controllers\FrontController::class, 'customizeSearch'])->name('customize.search');


// vendors frontend
Route::get('/vendor-store', [App\Http\Controllers\FrontController::class, 'vendorStore'])->name('vendor.store');
Route::get('/vendor-store/search', [App\Http\Controllers\FrontController::class, 'vendorstoreSearch'])->name('vendor.store.search');
Route::get('/vendor-store/category/{id}', [App\Http\Controllers\FrontController::class, 'vendorSubcategorysearch'])->name('vendor.store.category.search');
Route::get('/vendor/profile/detail/{id}', [App\Http\Controllers\FrontController::class, 'vendorProfileDetail'])->name('vendor.profile.detail');


// single product
Route::get('/product/detail/{id}', [App\Http\Controllers\FrontController::class, 'productDetail'])->name('product.detail');
Route::get('/vendor/product/detail/{id}', [App\Http\Controllers\FrontController::class, 'vendorProductDetail'])->name('vendor.product.detail');

Route::get('/ajax/product/detail/{id}', [App\Http\Controllers\FrontController::class, 'ajaxProductDetail'])->name('ajax.product.detail');

// cart
Route::prefix('/cart')->name('cart.')->group(function () {
    Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index');
    Route::post('/insert', [App\Http\Controllers\CartController::class, 'insert'])->name('insert');
    Route::post('/insert/discount', [App\Http\Controllers\CartController::class, 'insertDiscount'])->name('insert.discount');
    Route::post('/update', [App\Http\Controllers\CartController::class, 'update'])->name('update');
    Route::post('/delete', [App\Http\Controllers\CartController::class, 'delete'])->name('delete');
    Route::get('/list', [App\Http\Controllers\CartController::class, 'ajaxList'])->name('list');
    Route::get('/discount/{id}', [App\Http\Controllers\CartController::class, 'discount'])->name('discount');

    // vendor
    Route::prefix('/vendor')->name('vendor.')->group(function () {
        Route::post('/insert', [App\Http\Controllers\CartController::class, 'vendorInsert'])->name('insert');
    });
});

Route::prefix('/request/epin')->name('request.epin.')->group(function () {
    Route::get('/', [App\Http\Controllers\Auth\EpinController::class, 'loadEpinRequest'])->name('load');
    Route::post('/save', [App\Http\Controllers\Auth\EpinController::class, 'saveEpinRequest'])->name('save');
});

Auth::routes(['verify' => true]);

Route::middleware(['auth:web', 'verified', 'isblocked', 'isdeleted'])->group(function () {

    // adding comment in vendor product on single product
    Route::post('/vendor/product/comment', [App\Http\Controllers\FrontController::class, 'vendorProductComment'])->name('vendor.product.comment');
    Route::post('/vendor/product/comment/reply', [App\Http\Controllers\FrontController::class, 'vendorProductCommentreply'])->name('vendor.product.comment.reply');
    Route::post('/vendor/product/comment/delete', [App\Http\Controllers\FrontController::class, 'vendorProductCommentdelete'])->name('vendor.product.comment.delete');

    // checkout
    Route::get('/checkout', [App\Http\Controllers\FrontController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/process', [App\Http\Controllers\FrontController::class, 'checkoutProcess'])->name('checkout.process');

    Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');

    // ticket
    Route::prefix('/ticket')->name('ticket.')->group(function () {
        Route::get('/', [App\Http\Controllers\TicketController::class, 'index'])->name('list');
        Route::get('/add', [App\Http\Controllers\TicketController::class, 'add'])->name('add');
        Route::post('/insert', [App\Http\Controllers\TicketController::class, 'insert'])->name('insert');
        Route::delete('/delete/{id}', [App\Http\Controllers\TicketController::class, 'delete'])->name('delete');
        Route::get('/detail/{id}', [App\Http\Controllers\TicketController::class, 'detail'])->name('detail');
        Route::get('/reply/{id}', [App\Http\Controllers\TicketController::class, 'reply'])->name('reply');
        Route::post('/reply/insert/{id}', [App\Http\Controllers\TicketController::class, 'replyInsert'])->name('reply.insert');
    });

    //balance
    Route::prefix('/balance')->name('balance.')->group(function () {
        Route::get('/history', [App\Http\Controllers\BalanceController::class, 'history'])->name('history');
        Route::get('/add', [App\Http\Controllers\BalanceController::class, 'add'])->name('add');
        Route::post('/insert', [App\Http\Controllers\BalanceController::class, 'insert'])->name('insert');
        Route::get('/detail/{id}', [App\Http\Controllers\BalanceController::class, 'detail'])->name('detail');
    });

    //withdraw
    Route::prefix('/withdraw')->name('withdraw.')->group(function () {
        Route::get('/history', [App\Http\Controllers\WithdrawController::class, 'history'])->name('history');
        Route::get('/add', [App\Http\Controllers\WithdrawController::class, 'add'])->name('add');
        Route::post('/insert', [App\Http\Controllers\WithdrawController::class, 'insert'])->name('insert');
        Route::get('/detail/{id}', [App\Http\Controllers\WithdrawController::class, 'detail'])->name('detail');
    });

    //transfer
    Route::prefix('/transfer')->name('transfer.')->group(function () {
        Route::get('/list/send', [App\Http\Controllers\TransferController::class, 'listSend'])->name('list.send');
        Route::get('/list/receive', [App\Http\Controllers\TransferController::class, 'listReceive'])->name('list.receive');
        Route::get('/add', [App\Http\Controllers\TransferController::class, 'add'])->name('add');
        Route::post('/insert', [App\Http\Controllers\TransferController::class, 'insert'])->name('insert');
        Route::get('/reward/add', [App\Http\Controllers\TransferController::class, 'rewardAdd'])->name('reward.add');
        Route::post('/reward/insert', [App\Http\Controllers\TransferController::class, 'rewardInsert'])->name('reward.insert');
        Route::get('/reward/list', [App\Http\Controllers\TransferController::class, 'rewardList'])->name('reward.list');
    });

    // payment information
    Route::prefix('/payment/information')->name('payment.information.')->group(function () {
        Route::get('/add', [App\Http\Controllers\ProfileController::class, 'paymentInformationLoad'])->name('load');
        Route::post('/update', [App\Http\Controllers\ProfileController::class, 'paymentInformationUpdate'])->name('update');
    });

    //profile
    Route::prefix('/profile')->name('profile.')->group(function () {
        Route::get('/add', [App\Http\Controllers\ProfileController::class, 'profileLoad'])->name('load');
        Route::post('/update', [App\Http\Controllers\ProfileController::class, 'profileUpdate'])->name('update');
    });

    //password
    Route::prefix('/password')->name('password.')->group(function () {
        Route::get('/add', [App\Http\Controllers\ProfileController::class, 'passwordLoad'])->name('load');
        Route::post('/user/update', [App\Http\Controllers\ProfileController::class, 'passwordUpdate'])->name('user.update');
    });

    //team genealogy
    Route::prefix('/team')->name('team.')->group(function () {
        Route::get('/index/{id?}', [App\Http\Controllers\TeamController::class, 'index'])->name('list');
    });

    // order
    Route::prefix('/order')->name('order.')->group(function () {
        Route::get('/', [App\Http\Controllers\OrderController::class, 'index'])->name('index');
        Route::get('/detail/{id}/{type}', [App\Http\Controllers\OrderController::class, 'detail'])->name('detail');
        Route::get('/print/pdf/{id}/{type}', [App\Http\Controllers\OrderController::class, 'printPDF'])->name('print.pdf');
        Route::get('/change/{status}/{id}/{type}', [App\Http\Controllers\OrderController::class, 'changeStatus'])->name('change');
    });

    // report
    Route::prefix('/report')->name('report.')->group(function () {
        Route::get('/monthly/commission', [App\Http\Controllers\ReportController::class, 'monthlyComission'])->name('monthly.commission');
    });

    Route::prefix('/buy')->name('buy.')->group(function () {
        Route::get('/rgcode', [App\Http\Controllers\BuyEpinController::class, 'epinList'])->name('rgcode.list');
        Route::get('/rgcode/add', [App\Http\Controllers\BuyEpinController::class, 'epinAdd'])->name('rgcode.add');
        Route::post('/rgcode/insert', [App\Http\Controllers\BuyEpinController::class, 'epinInsert'])->name('rgcode.insert');
    });

    //vendor
    Route::prefix('/vendor')->name('vendor.')->group(function () {

        // request
        Route::prefix('/request')->name('request.')->group(function () {

            Route::middleware(['isvendorallow'])->group(function () {
                Route::get('/', [App\Http\Controllers\Vendor\VendorRequest::class, 'vendorRequestload'])->name('load');
                Route::post('/insert', [App\Http\Controllers\Vendor\VendorRequest::class, 'vendorRequestsave'])->name('save');
            });

            Route::prefix('/payment')->name('payment.')->middleware(['isvendorpayment'])->group(function () {
                Route::get('/', [App\Http\Controllers\Vendor\VendorRequest::class, 'paymentLoad'])->name('load');
                Route::post('/save', [App\Http\Controllers\Vendor\VendorRequest::class, 'paymentSave'])->name('save');
            });
        });

        Route::get('/terms/{slug}', function ($slug) {
            return view("user.vendor.term", compact('slug'));
        })->name('terms');

        Route::middleware(['isvendor', 'isvendorblocked'])->group(function () {
            Route::get('/dashboard', [App\Http\Controllers\Vendor\VendorController::class, 'dashboard'])->name('dashboard');
            Route::get('/revenue', [App\Http\Controllers\Vendor\VendorController::class, 'revenue'])->name('revenue');

            // product
            Route::prefix('/product')->name('product.')->group(function () {
                Route::get('/', [App\Http\Controllers\Vendor\VendorProduct::class, 'index'])->name('list');
                Route::get('/add', [App\Http\Controllers\Vendor\VendorProduct::class, 'add'])->name('add');
                Route::post('/insert', [App\Http\Controllers\Vendor\VendorProduct::class, 'insert'])->name('insert');
                Route::get('/edit/{id}', [App\Http\Controllers\Vendor\VendorProduct::class, 'edit'])->name('edit');
                Route::put('/update/{id}', [App\Http\Controllers\Vendor\VendorProduct::class, 'update'])->name('update');
                Route::delete('/delete/{id}', [App\Http\Controllers\Vendor\VendorProduct::class, 'delete'])->name('delete');
                Route::delete('/delete/media/{productid}/{mediaid}', [App\Http\Controllers\Vendor\VendorProduct::class, 'deleteMedia'])->name('delete.media');
            });

            // setting
            Route::prefix('/setting')->name('setting.')->group(function () {
                Route::get('/business', [App\Http\Controllers\Vendor\VendorSetting::class, 'businessLoad'])->name('business.load');
                Route::post('/business', [App\Http\Controllers\Vendor\VendorSetting::class, 'businessUpate'])->name('business.update');
            });

            //order
            Route::prefix('/order')->name('order.')->group(function () {
                Route::get('/', [App\Http\Controllers\Vendor\VendorOrder::class, 'index'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Vendor\VendorOrder::class, 'detail'])->name('detail');
                Route::get('/print/pdf/{id}', [App\Http\Controllers\Vendor\VendorOrder::class, 'printPDF'])->name('print.pdf');
                Route::get('/change/{status}/{id}', [App\Http\Controllers\Vendor\VendorOrder::class, 'changeStatus'])->name('change.status');
                Route::post('/approve', [App\Http\Controllers\Vendor\VendorOrder::class, 'orderApprove'])->name('insert');
                Route::delete('/delete/{id}', [App\Http\Controllers\Vendor\VendorOrder::class, 'delete'])->name('delete');
                Route::post('/change/delivery', [App\Http\Controllers\Vendor\VendorOrder::class, 'changeDelivery'])->name('change.delivery');
            });

            // notification
            Route::prefix('/notification')->name('notification.')->group(function () {
                Route::get('/', [App\Http\Controllers\Vendor\VendorController::class, 'allNotifications'])->name('list');
                Route::get('/unread', [App\Http\Controllers\Vendor\VendorController::class, 'unreadNotifications'])->name('unread');
                Route::post('/mark/read', [App\Http\Controllers\Vendor\VendorController::class, 'readNotifications'])->name('read');
            });

            //balance
            Route::prefix('/balance')->name('balance.')->group(function () {
                Route::get('/history', [App\Http\Controllers\Vendor\VendorBalance::class, 'history'])->name('history');
                Route::get('/add', [App\Http\Controllers\Vendor\VendorBalance::class, 'add'])->name('add');
                Route::post('/insert', [App\Http\Controllers\Vendor\VendorBalance::class, 'insert'])->name('insert');
                Route::get('/detail/{id}', [App\Http\Controllers\Vendor\VendorBalance::class, 'detail'])->name('detail');
            });

            //withdraw
            Route::prefix('/withdraw')->name('withdraw.')->group(function () {
                Route::get('/history', [App\Http\Controllers\Vendor\VendorWithdraw::class, 'history'])->name('history');
                Route::get('/add', [App\Http\Controllers\Vendor\VendorWithdraw::class, 'add'])->name('add');
                Route::post('/insert', [App\Http\Controllers\Vendor\VendorWithdraw::class, 'insert'])->name('insert');
                Route::get('/detail/{id}', [App\Http\Controllers\Vendor\VendorWithdraw::class, 'detail'])->name('detail');
            });

            // wallet
            Route::prefix('/wallet')->name('wallet.')->group(function () {
                Route::get('/', [App\Http\Controllers\Vendor\VendorWallet::class, 'index'])->name('list');
            });

            // ticket
            Route::prefix('/ticket')->name('ticket.')->group(function () {
                Route::get('/', [App\Http\Controllers\Vendor\VendorTicketController::class, 'index'])->name('list');
                Route::get('/add', [App\Http\Controllers\Vendor\VendorTicketController::class, 'add'])->name('add');
                Route::post('/insert', [App\Http\Controllers\Vendor\VendorTicketController::class, 'insert'])->name('insert');
                Route::delete('/delete/{id}', [App\Http\Controllers\Vendor\VendorTicketController::class, 'delete'])->name('delete');
                Route::get('/detail/{id}', [App\Http\Controllers\Vendor\VendorTicketController::class, 'detail'])->name('detail');
                Route::get('/reply/{id}', [App\Http\Controllers\Vendor\VendorTicketController::class, 'reply'])->name('reply');
                Route::post('/reply/insert/{id}', [App\Http\Controllers\Vendor\VendorTicketController::class, 'replyInsert'])->name('reply.insert');
            });
        });
    });
});

//admin
Route::prefix('/admin')->name('admin.')->group(function () {

    Route::get('/password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    Route::get('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showAdminLoginForm'])->name('login.view');
    Route::post('/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'adminLogin'])->name('login.submit');

    // logout
    Route::post('/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'adminLogout'])->name('logout');

    Route::middleware(['auth:admin'])->group(function () {


        //vendor
        Route::prefix('/vendor')->name('vendor.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\VendorController::class, 'list'])->name('list');
            Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorController::class, 'detail'])->name('detail');
            Route::post('/status', [App\Http\Controllers\Admin\VendorController::class, 'status'])->name('status');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorController::class, 'delete'])->name('delete');

            //vendor order
            Route::prefix('/order')->name('order.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\VendorOrder::class, 'index'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorOrder::class, 'detail'])->name('detail');
                Route::get('/print/pdf/{id}', [App\Http\Controllers\Admin\VendorOrder::class, 'printPDF'])->name('print.pdf');
                Route::get('/change/{status}/{id}', [App\Http\Controllers\Admin\VendorOrder::class, 'changeStatus'])->name('change.status');
                Route::post('/approve', [App\Http\Controllers\Admin\VendorOrder::class, 'orderApprove'])->name('insert');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorOrder::class, 'delete'])->name('delete');
                Route::post('/change/delivery', [App\Http\Controllers\Admin\VendorOrder::class, 'changeDelivery'])->name('change.delivery');
            });

            // products
            Route::prefix('/product')->name('product.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\VendorProduct::class, 'index'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorProduct::class, 'detail'])->name('detail');
                Route::post('/status', [App\Http\Controllers\Admin\VendorProduct::class, 'status'])->name('status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorProduct::class, 'delete'])->name('delete');
            });

            Route::prefix('/other')->name('other.')->group(function () {
                Route::get('/terms-condition', [App\Http\Controllers\Admin\VendorOther::class, 'termsCondtion'])->name('terms-condition.load');
                Route::post('/terms-condition/save', [App\Http\Controllers\Admin\VendorOther::class, 'saveTermscondtion'])->name('terms-condition.save');

                Route::get('/privacy-policy', [App\Http\Controllers\Admin\VendorOther::class, 'privacyPolicy'])->name('privacy-policy.load');
                Route::post('/privacy-policy/save', [App\Http\Controllers\Admin\VendorOther::class, 'savePrivacypolicy'])->name('privacy-policy.save');

                Route::get('/notes', [App\Http\Controllers\Admin\VendorOther::class, 'notes'])->name('notes.load');
                Route::post('/notes/save', [App\Http\Controllers\Admin\VendorOther::class, 'saveNotes'])->name('notes.save');
            });

            Route::prefix('/category')->name('category.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\VendorOther::class, 'category'])->name('load');
                Route::post('/save', [App\Http\Controllers\Admin\VendorOther::class, 'saveCategory'])->name('save');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorOther::class, 'deleteCategory'])->name('delete');
            });

            Route::prefix('/subcategory')->name('subcategory.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\VendorOther::class, 'subCategory'])->name('load');
                Route::post('/save', [App\Http\Controllers\Admin\VendorOther::class, 'saveSubcategory'])->name('save');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorOther::class, 'deletesubCategory'])->name('delete');
            });

            Route::prefix('/request')->name('request.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\VendorRequest::class, 'index'])->name('load');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'detail'])->name('detail');
                Route::post('/status', [App\Http\Controllers\Admin\VendorRequest::class, 'status'])->name('status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'delete'])->name('delete');

                Route::prefix('/balance')->name('balance.')->group(function () {
                    Route::get('/', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceList'])->name('list');
                    Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceDetail'])->name('detail');
                    Route::post('/change/status', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceChangeStatus'])->name('change.status');
                    Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceDelete'])->name('delete');
                    Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceRemark'])->name('remark');
                });

                Route::prefix('/withdraw')->name('withdraw.')->group(function () {
                    Route::get('/', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawList'])->name('list');
                    Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawDetail'])->name('detail');
                    Route::post('/change/status', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawChangeStatus'])->name('change.status');
                    Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawDelete'])->name('delete');
                    Route::post('/approve', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawApprove'])->name('approve');
                    Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawRemark'])->name('remark');
                });
            });

            Route::prefix('/request')->name('request.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\VendorRequest::class, 'index'])->name('load');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'detail'])->name('detail');
                Route::post('/status', [App\Http\Controllers\Admin\VendorRequest::class, 'status'])->name('status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'delete'])->name('delete');

                Route::prefix('/balance')->name('balance.')->group(function () {
                    Route::get('/', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceList'])->name('list');
                    Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceDetail'])->name('detail');
                    Route::post('/change/status', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceChangeStatus'])->name('change.status');
                    Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceDelete'])->name('delete');
                    Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'balanceRemark'])->name('remark');
                });

                Route::prefix('/withdraw')->name('withdraw.')->group(function () {
                    Route::get('/', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawList'])->name('list');
                    Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawDetail'])->name('detail');
                    Route::post('/change/status', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawChangeStatus'])->name('change.status');
                    Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawDelete'])->name('delete');
                    Route::post('/approve', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawApprove'])->name('approve');
                    Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\VendorRequest::class, 'withdrawRemark'])->name('remark');
                });
            });

            // payment ledger
            Route::prefix('/paymentledger')->name('paymentledger.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\VendorPaymentLedger::class, 'index'])->name('list');
                Route::get('/add', [App\Http\Controllers\Admin\VendorPaymentLedger::class, 'add'])->name('add');
                Route::get('/get/account/information/{id}', [App\Http\Controllers\Admin\VendorPaymentLedger::class, 'getVendorPaymentInformationWithoutstandingAmount'])->name('get.payment.info');
                Route::post('/insert', [App\Http\Controllers\Admin\VendorPaymentLedger::class, 'insert'])->name('insert');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\VendorPaymentLedger::class, 'detail'])->name('detail');
                // Route::put('/update/{id}', [App\Http\Controllers\Admin\VendorPaymentLedger::class, 'update'])->name('update');
                // Route::delete('/delete/{id}', [App\Http\Controllers\Admin\VendorPaymentLedger::class, 'delete'])->name('delete');
            });
        });

        //profile
        Route::get('/profile', [App\Http\Controllers\Admin\AdminController::class, 'profile'])->name('profile');
        Route::post('/profile/update', [App\Http\Controllers\Admin\AdminController::class, 'profileUpdate'])->name('profile.update');

        Route::get('/password', [App\Http\Controllers\Admin\AdminController::class, 'passwordLoad'])->name('password');
        Route::post('/password/updates', [App\Http\Controllers\Admin\AdminController::class, 'passwordUpdate'])->name('password.updates');

        Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('home');
        Route::get('/dashboard', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');

        //notification
        Route::prefix('/notification')->name('notification.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\HomeController::class, 'allNotifications'])->name('list');
            Route::get('/unread', [App\Http\Controllers\Admin\HomeController::class, 'unreadNotifications'])->name('unread');
            Route::post('/mark/read', [App\Http\Controllers\Admin\HomeController::class, 'readNotifications'])->name('read');
        });

        // ticket
        Route::prefix('/ticket')->name('ticket.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\TicketController::class, 'index'])->name('list');
            Route::get('/status/{id}', [App\Http\Controllers\Admin\TicketController::class, 'changeStatus'])->name('status');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\TicketController::class, 'delete'])->name('delete');
            Route::get('/detail/{id}', [App\Http\Controllers\Admin\TicketController::class, 'detail'])->name('detail');
            Route::get('/reply/{id}', [App\Http\Controllers\Admin\TicketController::class, 'reply'])->name('reply');
            Route::post('/reply/insert/{id}', [App\Http\Controllers\Admin\TicketController::class, 'replyInsert'])->name('reply.insert');
        });

        // user
        Route::prefix('/client')->name('client.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('list');
            Route::get('/log', [App\Http\Controllers\Admin\UserController::class, 'log'])->name('log');
            Route::get('/add', [App\Http\Controllers\Admin\UserController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\UserController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\UserController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('update');
            Route::get('/detail/{id}', [App\Http\Controllers\Admin\UserController::class, 'detail'])->name('detail');
            Route::get('/block/{id}/{staus}', [App\Http\Controllers\Admin\UserController::class, 'block'])->name('block');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\UserController::class, 'delete'])->name('delete');
            Route::post('/add/point', [App\Http\Controllers\Admin\UserController::class, 'insertPoint'])->name('insert.point');

            //
            Route::get('/vendor/allowed/{id}', [App\Http\Controllers\Admin\UserController::class, 'allowVendor'])->name('vendor.allowed');
        });

        // commission
        Route::prefix('/commission')->name('commission.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CommissionController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\CommissionController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\CommissionController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\CommissionController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\CommissionController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\CommissionController::class, 'delete'])->name('delete');
        });

        // category
        Route::prefix('/category')->name('category.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\CategoryController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\CategoryController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'delete'])->name('delete');
        });

        // blog
        Route::prefix('/blog')->name('blog.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BlogController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BlogController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BlogController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BlogController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BlogController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BlogController::class, 'delete'])->name('delete');
        });

        // brand
        Route::prefix('/brand')->name('brand.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BrandController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BrandController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BrandController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BrandController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BrandController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BrandController::class, 'delete'])->name('delete');
        });

        // product
        Route::prefix('/product')->name('product.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\ProductController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\ProductController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\ProductController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\ProductController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\ProductController::class, 'delete'])->name('delete');
            Route::delete('/delete/media/{productid}/{mediaid}', [App\Http\Controllers\Admin\ProductController::class, 'deleteMedia'])->name('delete.media');
        });

        // order
        Route::prefix('/order')->name('order.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('index');
            Route::get('/detail/{id}', [App\Http\Controllers\Admin\OrderController::class, 'detail'])->name('detail');
            Route::get('/print/pdf/{id}', [App\Http\Controllers\Admin\OrderController::class, 'printPDF'])->name('print.pdf');
            Route::get('/change/{status}/{id}', [App\Http\Controllers\Admin\OrderController::class, 'changeStatus'])->name('change.status');
            Route::post('/approve', [App\Http\Controllers\Admin\OrderController::class, 'orderApprove'])->name('insert');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\OrderController::class, 'delete'])->name('delete');
            Route::post('/change/delivery', [App\Http\Controllers\Admin\OrderController::class, 'changeDelivery'])->name('change.delivery');
        });

        //bank
        Route::prefix('/bank')->name('bank.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BankController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BankController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BankController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BankController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BankController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BankController::class, 'delete'])->name('delete');
        });

        //business account
        Route::prefix('/business/account')->name('business.account.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\BusinessAccountController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\BusinessAccountController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\BusinessAccountController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\BusinessAccountController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\BusinessAccountController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\BusinessAccountController::class, 'delete'])->name('delete');
        });

        //Team reward
        Route::prefix('/reward')->name('reward.')->group(function () {
            Route::prefix('/referred')->name('referred.')->group(function () {
                Route::get('/add', [App\Http\Controllers\Admin\RewardController::class, 'add'])->name('add');
                Route::post('/insert', [App\Http\Controllers\Admin\RewardController::class, 'insert'])->name('insert');
            });

            // Reward
            Route::prefix('/team')->name('team.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RewardTeamController::class, 'index'])->name('list');
                Route::get('/add', [App\Http\Controllers\Admin\RewardTeamController::class, 'add'])->name('add');
                Route::post('/insert', [App\Http\Controllers\Admin\RewardTeamController::class, 'insert'])->name('insert');
                Route::get('/edit/{id}', [App\Http\Controllers\Admin\RewardTeamController::class, 'edit'])->name('edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\RewardTeamController::class, 'update'])->name('update');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RewardTeamController::class, 'delete'])->name('delete');
            });

            // psp
            Route::prefix('/psp')->name('psp.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RewardPSPController::class, 'index'])->name('list');
                Route::get('/add', [App\Http\Controllers\Admin\RewardPSPController::class, 'add'])->name('add');
                Route::post('/insert', [App\Http\Controllers\Admin\RewardPSPController::class, 'insert'])->name('insert');
                Route::get('/edit/{id}', [App\Http\Controllers\Admin\RewardPSPController::class, 'edit'])->name('edit');
                Route::put('/update/{id}', [App\Http\Controllers\Admin\RewardPSPController::class, 'update'])->name('update');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RewardPSPController::class, 'delete'])->name('delete');
            });
        });

        //success
        Route::prefix('/success/story')->name('success.story.')->group(function () {
            Route::get('/', [App\Http\Controllers\Admin\SuccessStoryController::class, 'index'])->name('list');
            Route::get('/add', [App\Http\Controllers\Admin\SuccessStoryController::class, 'add'])->name('add');
            Route::post('/insert', [App\Http\Controllers\Admin\SuccessStoryController::class, 'insert'])->name('insert');
            Route::get('/edit/{id}', [App\Http\Controllers\Admin\SuccessStoryController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [App\Http\Controllers\Admin\SuccessStoryController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [App\Http\Controllers\Admin\SuccessStoryController::class, 'delete'])->name('delete');
        });

        //setting
        Route::prefix('/setting')->name('setting.')->group(function () {

            Route::get('/site', [App\Http\Controllers\Admin\SettingController::class, 'loadSiteSetting'])->name('site');
            Route::post('/site/save', [App\Http\Controllers\Admin\SettingController::class, 'saveSiteSetting'])->name('site.save');

            Route::get('/charges', [App\Http\Controllers\Admin\SettingController::class, 'loadChargesSetting'])->name('charges');
            Route::post('/charges/save', [App\Http\Controllers\Admin\SettingController::class, 'saveChargesSetting'])->name('charges.save');

            Route::get('/banner', [App\Http\Controllers\Admin\SettingController::class, 'bannerSetting'])->name('banner');
            Route::post('/banner/save', [App\Http\Controllers\Admin\SettingController::class, 'saveBannerSetting'])->name('banner.save');
            Route::post('/other/banner/save', [App\Http\Controllers\Admin\SettingController::class, 'saveOtherBannerSetting'])->name('other.banner.save');
            Route::post('/banner/dashboard/save', [App\Http\Controllers\Admin\SettingController::class, 'saveDashboardBannerSetting'])->name('banner.dashboard.save');
            Route::delete('/banner/delete/media/{productid}', [App\Http\Controllers\Admin\SettingController::class, 'deleteMedia'])->name('delete.media');

            Route::post('vendor/banner/save', [App\Http\Controllers\Admin\SettingController::class, 'saveVendorBannerSetting'])->name('vendor.banner.save');
        });

        //request
        Route::prefix('/request')->name('request.')->group(function () {

            Route::prefix('/epin')->name('epin.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RequestController::class, 'epinList'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\RequestController::class, 'epinDetail'])->name('detail');
                Route::post('/change/status', [App\Http\Controllers\Admin\RequestController::class, 'epinChangeStatus'])->name('change.status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RequestController::class, 'epinDelete'])->name('delete');
            });

            Route::prefix('/balance')->name('balance.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RequestController::class, 'balanceList'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\RequestController::class, 'balanceDetail'])->name('detail');
                Route::post('/change/status', [App\Http\Controllers\Admin\RequestController::class, 'balanceChangeStatus'])->name('change.status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RequestController::class, 'balanceDelete'])->name('delete');
                Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\RequestController::class, 'balanceRemark'])->name('remark');
            });

            Route::prefix('/withdraw')->name('withdraw.')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\RequestController::class, 'withdrawList'])->name('list');
                Route::get('/detail/{id}', [App\Http\Controllers\Admin\RequestController::class, 'withdrawDetail'])->name('detail');
                Route::post('/change/status', [App\Http\Controllers\Admin\RequestController::class, 'withdrawChangeStatus'])->name('change.status');
                Route::delete('/delete/{id}', [App\Http\Controllers\Admin\RequestController::class, 'withdrawDelete'])->name('delete');
                Route::post('/approve', [App\Http\Controllers\Admin\RequestController::class, 'withdrawApprove'])->name('approve');
                Route::post('/remark/update/{id}', [App\Http\Controllers\Admin\RequestController::class, 'withdrawRemark'])->name('remark');
            });

            Route::prefix('/get')->name('get.')->group(function () {
                Route::get('/user/payment/information/{id}', [App\Http\Controllers\Admin\RequestController::class, 'getUserPaymentInformation'])->name('user.payment.information');
            });
        });
    });
});

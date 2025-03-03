<?php

namespace App\Http\Controllers;

use App\Helpers\CartHelper;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\City;
use App\Models\Product;
use App\Models\SuccesStory;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorCategory;
use App\Models\VendorProduct;
use App\Models\VendorProductComment;
use App\Models\VendorSubCategory;
use App\Notifications\VendorNotification;
use App\Rules\UserOrderGift;
use App\Rules\UserOrderWallet;
use Auth;
use Illuminate\Http\Request;
// use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VendorOrderNotification;

class FrontController extends Controller
{
    public function index()
    {
        $featureproduct = Product::where('is_feature', 1)
            ->where('is_active', 1)
            ->get();
        $banner = Banner::where('type', 1)->get();
        $newproduct = Product::orderBy('id', 'DESC')
            ->skip(0)
            ->take(8)
            ->where('is_active', 1)
            ->get();

        return view('welcome', [
            'featureproduct' => $featureproduct,
            'banner' => $banner,
            'newproduct' => $newproduct,
        ]);
    }

    public function checkout(Request $request)
    {
        $city = City::all();
        return view('checkout', compact('city'));
    }

    public function contactUs()
    {
        return view('contact');
    }

    public function aboutUs()
    {
        return view('about');
    }

    public function successStories()
    {
        $story = SuccesStory::all();
        return view('success_story', compact('story'));
    }

    public function checkoutProcess(Request $request)
    {

        $this->validate($request, [
            'name' => ['required'],
            'email' => ['required'],
            'phone' => ['required'],
            'city' => ['required'],
            'street' => ['required'],
            'address' => ['required'],
            'shipping_address' => ['required'],
            'discount' => ['required'],
        ]);

        if ($request->payment_by == '1') {
            //if user selected payment by wallet
            $this->validate($request, [
                'balance' => ['required', new UserOrderWallet($request->totalpay)],
            ]);
        }

        if ($request->payment_by == '2') {
            //if user selected payment by cashback
            $this->validate($request, [
                'giftbalance' => ['required', new UserOrderGift($request->totalpay)],
            ]);
        }

        if (count(\Cart::session('normal')->getContent())) {
            $response = CartHelper::checkOutForCart('normal', $request);
            \Cart::session('normal')->clear();
        }

        if (count(\Cart::session('vendor')->getContent())) {
            $response = CartHelper::checkOutForCart('vendor', $request);
            \Cart::session('vendor')->clear();
        }

        if ($response) {
            return redirect()
                ->route('order.index')
                ->with('success', 'Order Placed Successfully');
        } else {
            return back()->with('error', 'Something went wrong');
        }
    }

    public function privacyPolicy()
    {
        return view('privacy_policy');
    }

    public function termCondition()
    {
        return view('term_condition');
    }

    public function productDetail($id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::findOrFail($id);
        return view('product_detail', [
            'product' => $product,
        ]);
    }

    public function vendorProductDetail($id)
    {
        $id = Crypt::decrypt($id);
        $product = VendorProduct::findOrFail($id);
        return view('product_detail', [
            'product' => $product,
        ]);
    }

    public function vendorProductComment(Request $request)
    {
        $rating = $request->rating;
        $content = $request->content;
        $productid = $request->productid;

        $result = VendorProductComment::whereNotNull('rating')
            ->where('vendor_product_id', $productid)
            ->where('user_id', Auth::guard('web')->user()->id)
            ->first();

        if ($result) {
            return redirect()->back()->with("error", "Already made rating on this product");
        }

        DB::beginTransaction();

        $vendorproductcomment = new VendorProductComment();
        $vendorproductcomment->vendor_product_id = $productid;
        $vendorproductcomment->rating = $rating;
        $vendorproductcomment->content = $content;
        $user = Auth::guard('web')->user()->id;
        $vendorproductcomment->user_id = $user;
        $response =  $vendorproductcomment->save();

        // give product rating
        $allproductcomment = VendorProductComment::where("vendor_product_id", $productid)->where("rating", "!=", NULL)->get();
        $productCommentRating = $counter = 0;
        foreach ($allproductcomment as $value) {
            $productCommentRating = $productCommentRating  + $value->rating;
            $counter++;
        }
        $product = VendorProduct::findOrFail($productid);
        $product->rating =  ($productCommentRating) / $counter;
        $responseproduct =  $product->save();

        //profile rating
        $allproduct = VendorProduct::where('vendor_id', $product->vendor_id)->where("rating", "!=", 0.00)->with('comments')->get();
        $productRating = $counter = 0;
        foreach ($allproduct as $value) {
            $productRating = $productRating  + $value->rating;
            $counter++;
        }

        $vendor = Vendor::find($product->vendor_id);
        $vendor->rating = $productRating / $counter;
        $vendorresponse = $vendor->save();

        if ($response &&  $responseproduct && $vendorresponse) {

            $msg =  Auth::guard('web')->user()->name . ' has make a comment on your product';
            $detail = Auth::guard('web')->user()->name . " commented: " . $content;
            $link = 'vendor/product/detail/' . encrypt($product->vendor_id);
            $user = User::find($vendor->user_id);
            $type = 4;
            $vendornotification = new VendorOrderNotification($msg, $type, $link, $detail);
            Notification::send($user, $vendornotification);

            DB::commit();
            $type = 'success';
            $msg = "Comment is saved successfully";
        } else {
            DB::rollback();
            $type = 'error';
            $msg = "Something went wrong";
        }
        return redirect()->back()->with($type, $msg);
    }

    public function vendorProductCommentreply(Request $request)
    {
        if (isset($request->commentid)) {
            $vendorproductcomment = VendorProductComment::find($request->commentid);
        } else {
            $vendorproductcomment = new VendorProductComment();
            $user = Auth::guard('web')->user()->id;
            $vendorproductcomment->user_id = $user;
            $productid = $request->productid;
            $vendorproductcomment->vendor_product_id = $productid;
            $parentid = $request->parentid;
            $vendorproductcomment->parent_id = $parentid;
        }
        $content = $request->content;
        $vendorproductcomment->content = $content;
        $vendorproductcomment->save();
        $product = VendorProduct::find($request->productid);

        return view('include.partials.comments', [
            'product' => $product,
        ])->render();
    }

    public function vendorProductCommentdelete(Request $request)
    {
        $vendorproductcomment = VendorProductComment::find($request->id);
        $this->deleteChildcomments($request->id);
        if ($vendorproductcomment->delete()) {
            $json = ['type' => 1, 'msg' => 'Comment is delete'];
        } else {
            $json = ['type' => 0, 'msg' => 'Something went wrong'];
        }
        return response()->json($json);
    }

    public function vendorProfileDetail($id)
    {
        $id = Crypt::decrypt($id);
        $vendor = Vendor::findOrFail($id);
        $category = VendorCategory::get();
        $product = VendorProduct::where('is_active', 1)->where('user_id',  $vendor->user_id)
            ->paginate(24);

        return view('vendor_profile_detail', [
            'vendor' => $vendor,
            'category' => $category,
            'product' => $product,
        ]);
    }

    public function deleteChildcomments($id)
    {
        $comment = VendorProductComment::where('parent_id', $id)->get();
        foreach ($comment as $row) {
            $this->deleteChildcomments($row->id);
            $row->delete();
        }
    }

    public function ajaxProductDetail($id)
    {
        $id = Crypt::decrypt($id);
        $product = Product::findOrFail($id);
        return response()->json([
            'product' => $product,
            'productmedia' => $product->getMedia('images'),
        ]);
    }

    public function otherBrand()
    {
        $category = Category::where('is_active', 1)->where('page_type', 1)->get();
        $product = Product::where('is_other', 1)
            ->where('is_active', 1)
            ->paginate(24);
        return view('other-brand', [
            'category' => $category,
            'product' => $product,
        ]);
    }

    public function otherBrandSearch(Request $request)
    {
        if ($request->ajax()) {
            $productquery = Product::query()->where('is_active', 1);
            if ($request->category) {
                $productquery->where('category_id', $request->category);
            }

            if ($request->product) {
                $productquery->where('product', 'LIKE', "%$request->product%");
            }

            if ($request->has('price')) {
                $request->price ? $productquery->orderBy('price', 'DESC') : $productquery->orderBy('price', 'ASC');
            }
            if ($request->has('sort')) {
                $request->sort ? $productquery->orderBy('product', 'ASC') : $productquery->orderBy('price', 'ASC');
            }
            $productquery->where('is_other', '1');

            $product = $productquery->orderBy('id', 'DESC')->paginate(24);
            $category = Category::where('is_active', 1)->get();
            return view('include.shop', [
                'category' => $category,
                'product' => $product,
            ])->render();
        }
    }

    public function customize()
    {
        $category = Category::where('is_active', 1)->where('page_type', 2)->get();
        $product = Product::where('is_other', 2)
            ->where('is_active', 1)
            ->paginate(24);
        return view('customize', [
            'category' => $category,
            'product' => $product,
        ]);
    }

    public function customizeSearch(Request $request)
    {
        if ($request->ajax()) {
            $productquery = Product::query()->where('is_active', 1);
            if ($request->category) {
                $productquery->where('category_id', $request->category);
            }


            if ($request->product) {
                $productquery->where('product', 'LIKE', "%$request->product%");
            }

            if ($request->has('price')) {
                $request->price ? $productquery->orderBy('price', 'DESC') : $productquery->orderBy('price', 'ASC');
            }
            if ($request->has('sort')) {
                $request->sort ? $productquery->orderBy('product', 'ASC') : $productquery->orderBy('price', 'ASC');
            }
            $productquery->where('is_other', '2');

            $product = $productquery->orderBy('id', 'DESC')->paginate(24);
            $category = Category::where('is_active', 1)->get();
            return view('include.shop', [
                'category' => $category,
                'product' => $product,
            ])->render();
        }
    }

    public function shop()
    {
        $category = Category::where('is_active', 1)->where('page_type', 0)->get();
        $product = Product::where('is_other', 0)
            ->where('is_active', 1)
            ->paginate(24);
        return view('shop', [
            'category' => $category,
            'product' => $product,
        ]);
    }

    public function shopSearch(Request $request)
    {
        if ($request->ajax()) {
            $productquery = Product::query()->where('is_active', 1);
            if ($request->category) {
                $productquery->where('category_id', $request->category);
            }

            if ($request->product) {
                $productquery->where('product', 'LIKE', "%$request->product%");
            }

            if ($request->has('price')) {
                $request->price ? $productquery->orderBy('price', 'DESC') : $productquery->orderBy('price', 'ASC');
            }
            if ($request->has('sort')) {
                $request->sort ? $productquery->orderBy('product', 'ASC') : $productquery->orderBy('price', 'ASC');
            }
            $productquery->where('is_other', '0');

            $product = $productquery->orderBy('id', 'DESC')->paginate(24);
            $category = Category::where('is_active', 1)->get();
            return view('include.shop', [
                'category' => $category,
                'product' => $product,
            ])->render();
        }
    }

    public function vendorStore()
    {
        $banner = Banner::where('type', 2)->get();
        $category = VendorCategory::get();
        $subcategory = [];
        $product = VendorProduct::where('is_active', 1)
            ->paginate(24);
        return view('vendor-store', [
            'category' => $category,
            'subcategory' => $subcategory,
            'product' => $product,
            'banner' => $banner,
        ]);
    }

    public function vendorSubcategorysearch($id)
    {
        $subcategory = VendorSubCategory::where('vendor_category_id', $id)->get();
        $output = '<select class="subcategory form-control" name="subcategory" id="subcategory" style="width:100%" required><option value="">Select Option</option>';
        foreach ($subcategory as $row) {
            $output .= '<option value="' . $row->id . '" >' . $row->sub_category . '</option>';
        }
        $output .= "</select>";
        echo $output;
    }

    public function vendorstoreSearch(Request $request)
    {
        if ($request->ajax()) {
            $productquery = VendorProduct::query()->where('is_active', 1);
            $subcategory = [];
            if ($request->category) {
                $productquery->where('vendor_category_id', $request->category);
                $subcategory = VendorSubCategory::where('vendor_category_id', $request->category)->get();
            }

            if ($request->subcategory) {
                $productquery->where('vendor_sub_category_id', $request->subcategory);
            }

            if ($request->product) {
                $productquery->where('product', 'LIKE', "%$request->product%");
            }

            if ($request->vendorid) {
                $productquery->where('vendor_id', $request->vendorid);
            }

            if ($request->has('price')) {
                $request->price ? $productquery->orderBy('price', 'DESC') : $productquery->orderBy('price', 'ASC');
            }

            if ($request->has('sort')) {
                $request->sort ? $productquery->orderBy('product', 'ASC') : $productquery->orderBy('price', 'ASC');
            }

            $product = $productquery->orderBy('id', 'DESC')->paginate(24);
            $category = VendorCategory::get();
            return view('include.shop', [
                'category' => $category,
                'subcategory' =>  $subcategory,
                'product' => $product,
            ])->render();
        }
    }


    public function blogs()
    {
        $blog = Blog::where('is_active', 1)
            ->orderBy('id', 'DESC')
            ->paginate(9);
        return view('blogs', [
            'blog' => $blog,
        ]);
    }

    public function blogSingle($id)
    {
        $blog = Blog::findOrFail($id);
        $blogs = Blog::orderBy('id', 'ASC')
            ->offset(0)
            ->limit(3)
            ->get();
        return view('blog', [
            'blog' => $blog,
            'blogs' => $blogs,
        ]);
    }
}

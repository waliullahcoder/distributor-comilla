<?php

namespace App\Http\Controllers\Admin;

use App\HelperClass;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\CategoryVendor;
use App\Models\LiftingProduct;
use App\Models\ProductPrice;
use App\Models\ProductVendor;
use App\Models\Vendor;
use App\Services\ActionButtons\ActionButtons;
use App\Services\ProductStockService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    protected $productStockService;

    public function __construct(ProductStockService $productStockService)
    {
        $this->productStockService = $productStockService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Product::with(['company', 'orderProducts', 'price', 'vendors', 'attribute'])
                ->orderBy('id', 'desc');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    $lifting = LiftingProduct::where('product_id', $row->id)->first();
                    if (is_null($lifting)) {
                        $checkbox = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input ';
                        if (!empty(request('type')) && request('type') == "trash") {
                            $checkbox .= 'trash_multi_checkbox';
                        } else {
                            $checkbox .= 'multi_checkbox';
                        }
                        $checkbox .= '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                        return $checkbox;
                    }
                })
                ->addColumn('image', function ($row) {
                    return file_exists($row->thumbnail) ? '<img src="' . asset($row->thumbnail) . '" height="50" alt="' . $row->name . '">' : 'No Image';
                })
                ->addColumn('vendors', function ($row) {
                    return @$row->vendors->pluck('vendor.name')->toArray();
                })
                ->addColumn('lifting_price', function ($row) {
                    return number_format(@$row->price->lifting_price);
                })
                ->addColumn('attribute_name', function ($row) {
                    return @$row->attribute->name;
                })
                ->addColumn('sale_price', function ($row) {
                    return number_format(@$row->price->sale_price);
                })
                ->addColumn('online_price', function ($row) {
                    return number_format(@$row->price->online_price);
                })
                ->addColumn('discount', function ($row) {
                    return number_format(@$row->price->discount_tk);
                })
                ->addColumn('category', function ($row) {
                    if ($row->category) {
                        return $row->category->name;
                    }
                })
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">
                    <input class="form-check-input change-status c-pointer" data-url="' . Route('admin.product.edit', $row->id) . '" type="checkbox" name="status" ' . ($row->status == 1 ? 'checked' : '') . '>
                    </div>';
                    return $status;
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];

                    $lifting = LiftingProduct::where('product_id', $row->id)->first();
                    $delete = 'yes';
                    if (!is_null($lifting)) {
                        $delete = 'no';
                    }
                    $additionalBtn = '<a href="' . Route('admin.product.attributes', $row->id) . '" class="btn btn-sm btn-secondary border-0 px-10px tt" data-bs-toggle="tooltip" data-bs-placement="top" title="View Attributes"><i class="fas fa-eye"></i></a>';
                    return ActionButtons::actions($data, $additionalBtn, $delete);
                })
                ->rawColumns(['checkbox', 'image', 'status', 'actions'])
                ->make(true);
        }
        $title = 'Product Setup';
        return view('admin.product.index', compact('title'));
    }

    public function attributes(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $back_link = '';
        return view('admin.product.product_attributes', compact('product', 'back_link'));
    }

    public function attributesUpdate(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update([
            'status' => $request->has('status') ? 1 : 0,
            'trending' => $request->has('trending') ? 1 : 0,
            'featured' => $request->has('featured') ? 1 : 0,
            'top_rated' => $request->has('top_rated') ? 1 : 0,
            'best_selling' => $request->has('best_selling') ? 1 : 0,
        ]);
        return redirect()->route('admin.product.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax() && $request->has('choices')) {
            $attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();
            $html = '';
            foreach ($attribute_values as $row) {
                $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
            }
            return json_encode($html);
        }

        if ($request->ajax() && $request->has('get_sku')) {
            $options = array();
            if ($request->choice_no) {
                foreach ($request->choice_no as $key => $no) {
                    $name = 'choice_options_' . $no;
                    $data = array();
                    if ($request[$name] != '') {
                        foreach ($request[$name] as $key => $item) {
                            array_push($data, $item);
                        }
                    }
                    if (count($data) == 0) {
                        continue;
                    }
                    array_push($options, $data);
                }
            }
            $combinations = $this->makeCombinations($options);
            return view('admin.product.sku_combinations', compact('combinations'));
        }

        if ($request->ajax()) {
            $child_categories = Category::where('parent_id', $request->id)->where('status', 1)->get();
            $vendors = CategoryVendor::where('category_id', $request->id)->get();
            $html = '';
            foreach ($vendors as $vendor) {
                $html .= "<option value='{$vendor->vendor_id}'>{$vendor->vendor->name}</option>";
            }
            return response()->json(['status' => 'success', 'child_categories' => $child_categories, 'html' => $html]);
        }

        $title = 'Add New Product';
        $categories =  Category::root()->where('status', 1)->get();
        $attributes = Attribute::where('status', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.product.create', compact('title', 'categories', 'attributes', 'vendors'));
    }

    public function sku_combination(Request $request)
    {
        $options = array();

        $unit_price = $request->sale_price;
        $product_name = $request->name;

        if ($request->choice_no) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                if ($request[$name] != '') {
                    foreach ($request[$name] as $key => $item) {
                        array_push($data, $item);
                    }
                }
                if (count($data) == 0) {
                    continue;
                }
                array_push($options, $data);
            }
        }
        $combinations = $this->makeCombinations($options);
        return view('admin.product.sku_combinations', compact('combinations', 'unit_price', 'product_name'));
    }

    public static function makeCombinations($arrays)
    {
        $result = array(array());
        foreach ($arrays as $property => $property_values) {
            $tmp = array();
            foreach ($result as $result_item) {
                foreach ($property_values as $property_value) {
                    $tmp[] = array_merge($result_item, array($property => $property_value));
                }
            }
            $result = $tmp;
        }
        return $result;
    }

    public function add_more_choice_option(Request $request)
    {
        $all_attribute_values = AttributeValue::with('attribute')->where('attribute_id', $request->attribute_id)->get();
        $html = '';
        foreach ($all_attribute_values as $row) {
            $html .= '<option value="' . $row->value . '">' . $row->value . '</option>';
        }
        echo json_encode($html);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required',
                // 'thumbnail' => 'image|required',
                'category_id' => 'required',
            ],
            [
                'video.max' => 'Can not Upload video larget than 15MB'
            ]
        );

        if ($request->product_type == 'Consumer') {
            foreach ($request->vendor_id as $key => $vendor_id) {
                $duplicate_check = Product::withTrashed()->where('name', $request->name)->where('code', $request->code)->where('vendor_id', $vendor_id)->first();
                if (!is_null($duplicate_check)) {
                    return redirect()->back()->withErrors('Product Already Exist!');
                }
            }
        }

        DB::transaction(function () use ($request) {

            // Product Video
            $video = $request->file('video');
            $video_path_name = NULL;
            if (isset($video)) {
                $video_name = 'product-video-' . uniqid() . '.' . $video->getClientOriginalExtension();
                $upload_path = 'media/video/';
                $video_path_name = $upload_path . $video_name;
                $video->move($upload_path, $video_name);
            }

            // Product Other Images
            $more_images = $request->file('more_images');
            if (isset($more_images)) {
                foreach ($more_images as $key => $more_image) {
                    $response = HelperClass::storeImage($more_image, 800, 'media/product/');
                    if ($response['status'] == 'success') {
                        $img_arr[$key] = $response['path_name'];
                    }
                }
                $more_images_path_names = trim(implode('|', $img_arr), '|');
            } else {
                $more_images_path_names = NULL;
            }

            if ($request->product_type == 'Consumer' || is_null($request->product_type)) {
                $slug = Str::slug($request->name);
                $same_slug_count = Product::withTrashed()->where('slug', 'LIKE', $slug . '%')->count();
                $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
                $slug .= $slug_suffix;

                // Store Product
                $product = Product::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'product_type' => $request->product_type ?? 'Consumer',
                    'category_id' => $request->category_id,
                    'attribute_id' => $request->attribute_id,
                    'code' => $request->code,
                    'name' => $request->name,
                    'type' => $request->type,
                    'trade_offer' => $request->trade_offer,
                    'do_ratio' => $request->do_ratio,
                    'slug' => $slug,
                    'thumbnail' => isset($request->thumbnail) ? HelperClass::saveImage($request->thumbnail, 800, 'media/product/') : NULL,
                    'more_images' => $more_images_path_names,
                    'short_description' => $request->short_description,
                    'description' => $request->description,
                    'additional_info' => $request->additional_info,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keyword' => $request->meta_keyword,
                    'alert_quantity' => $request->alert_quantity,
                    'min_order' => $request->min_order,
                    'show_on_website' => $request->show_on_website,
                    'video' => $video_path_name,
                    'video_id' => $request->video_id,
                    'ctn_size' => @$request->ctn_size[$key] ?? 0,
                    'allowed_investor' => !is_null($request->allowed_investor) ? $request->allowed_investor : 1,
                    'shared_profit' => $request->shared_profit,
                    'choice_options' => NULL,
                    'attributes' => '[]',
                    'trending' => $request->trending,
                    'serial' => $request->serial ?? 0,
                    'created_by' => Auth::user()->id,
                ]);

                // Add Product Price
                ProductPrice::create([
                    'product_id' => $product->id,
                    'lifting_price' => $request->lifting_price,
                    'sale_price' => $request->sale_price,
                    'online_price' => $request->online_price,
                    'discount' => isset($request->discount) ? $request->discount : 0,
                    'discount_tk' => isset($request->discount_tk) ? $request->discount_tk : 0,
                ]);

                foreach ($request->vendor_id as $key => $vendor_id) {
                    ProductVendor::create(['product_id' => $product->id, 'vendor_id' => $vendor_id]);
                }
            }

            if ($request->product_type == 'Fashion') {
                $slug = Str::slug($request->name);
                $same_slug_count = Product::withTrashed()->where('slug', 'LIKE', $slug . '%')->count();
                $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
                $slug .= $slug_suffix;

                $collection = collect($request->all());
                $choice_options = array();
                $attributes = [];

                if (isset($collection['choice_no']) && $collection['choice_no']) {
                    $str = '';
                    $item = array();
                    foreach ($collection['choice_no'] as $key => $no) {
                        $str = 'choice_options_' . $no;
                        $item['attribute_id'] = $no;
                        $attribute_data = array();

                        if (isset($collection[$str])) {
                            foreach ($collection[$str] as $key => $eachValue) {
                                array_push($attribute_data, $eachValue);
                            }
                            $attributes[] = $no;
                        }
                        if (count($attribute_data) == 0) {
                            continue;
                        }
                        $item['values'] = $attribute_data;
                        array_push($choice_options, $item);
                    }
                }
                $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

                // Store Product
                $product = Product::create([
                    'company_id' => Auth::user()->company_id ?? 1,
                    'product_type' => $request->product_type ?? 'Consumer',
                    'category_id' => $request->category_id,
                    'code' => $request->code,
                    'name' => $request->name,
                    'type' => $request->type,
                    'trade_offer' => $request->trade_offer,
                    'do_ratio' => $request->do_ratio,
                    'slug' => $slug,
                    'thumbnail' => isset($request->thumbnail) ? HelperClass::saveImage($request->thumbnail, 800, 'media/product/') : NULL,
                    'more_images' => $more_images_path_names,
                    'short_description' => $request->short_description,
                    'description' => $request->description,
                    'additional_info' => $request->additional_info,
                    'meta_title' => $request->meta_title,
                    'meta_description' => $request->meta_description,
                    'meta_keyword' => $request->meta_keyword,
                    'alert_quantity' => $request->alert_quantity,
                    'min_order' => $request->min_order,
                    'show_on_website' => $request->show_on_website,
                    'video' => $video_path_name,
                    'video_id' => $request->video_id,
                    'ctn_size' => @$request->ctn_size[$key] ?? 0,
                    'choice_options' =>  $choice_options,
                    'attributes' => json_encode($attributes),
                    'trending' => $request->trending,
                    'serial' => $request->serial ?? 0,
                    'allowed_investor' => !is_null($request->allowed_investor) ? $request->allowed_investor : 1,
                    'shared_profit' => $request->shared_profit,
                    'created_by' => Auth::user()->id,
                ]);

                $this->productStockService->store($request->only(['choice_no']), $product);
            }
        });

        return redirect()->route('admin.product.index')->withSuccessMessage('Added Successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    public function sku_combination_edit(Request $request)
    {
        $options = array();
        $unit_price = $request->sale_price;
        $product_name = $request->name;

        if ($request->choice_no) {
            foreach ($request->choice_no as $key => $no) {
                $name = 'choice_options_' . $no;
                $data = array();
                if ($request[$name] != '') {
                    foreach ($request[$name] as $key => $item) {
                        array_push($data, $item);
                    }
                }
                if (count($data) == 0) {
                    continue;
                }
                array_push($options, $data);
            }
        }
        $combinations = $this->makeCombinations($options);
        return view('admin.product.sku_combinations', compact('combinations', 'unit_price', 'product_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (request()->ajax() && request()->has('status')) {
            $data = Product::findOrFail($id);
            $data->update(['status' => !$data->status]);
            return response()->json(['status' => 'success']);
        }

        if (request()->ajax() && request()->has('getCategories')) {
            $child_categories = Category::where('parent_id', request('id'))->where('status', 1)->get();
            $vendors = CategoryVendor::where('category_id', request('id'))->get();
            $html = '';
            foreach ($vendors as $vendor) {
                $html .= "<option value='{$vendor->vendor_id}'>{$vendor->vendor->name}</option>";
            }
            return response()->json(['status' => 'success', 'child_categories' => $child_categories, 'html' => $html]);
        }

        $data = Product::findOrFail($id);
        $link = Route('admin.product.update', $data->id);
        $parent_id = NULL;
        $child_id = NULL;
        $subchild_id = NULL;

        $product_category = $data->category;
        if (@$product_category->parent_id) {
            $check_parent = Category::findOrFail($product_category->parent_id);
            if ($check_parent->parent_id) {
                $parent_id = $check_parent->parent_id;
                $child_id = $check_parent->id;
                $subchild_id = $data->category->id;
            } else {
                $parent_id = $check_parent->id;
                $child_id = $data->category->id;
            }
        } else {
            $parent_id = @$data->category->id;
        }

        $parent_categories =  Category::root()->where('status', 1)->get();
        $child_categories = Category::where('parent_id', $parent_id)->get();
        $subchild_categories = Category::where('parent_id', $child_id)->get();
        $title = 'Update Product';
        $attributes = Attribute::where('status', 1)->orderBy('name', 'asc')->get();
        $vendors = Vendor::where('status', 1)->get();
        return view('admin.product.edit', compact('title', 'attributes', 'data', 'link', 'parent_id', 'child_id', 'subchild_id', 'parent_categories', 'child_categories', 'subchild_categories', 'vendors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
                'name' => 'required',
            ]);

        $duplicate_check = Product::withTrashed()->where('id', '!=', $id)->where('name', $request->name)->where('code', $request->code)->where('vendor_id', $request->vendors)->first();
        if (!is_null($duplicate_check)) {
            return redirect()->back()->withErrors('Product Already Exist!');
        }

        DB::transaction(function () use ($request, $id) {
            $product = Product::findOrFail($id);

            // Product Video
            $video = $request->file('video');
            $video_path_name = $product->video;
            if (isset($video)) {
                $video_name = 'product-video-' . uniqid() . '.' . $video->getClientOriginalExtension();
                $upload_path = 'media/video/';
                $video_path_name = $upload_path . $video_name;
                $video->move($upload_path, $video_name);
                if (file_exists($product->video)) {
                    unlink($product->video);
                }
            }

            // Product Other Images
            $more_images = $request->file('more_images');
            if (isset($more_images)) {
                foreach ($more_images as $key => $more_image) {
                    $response = HelperClass::storeImage($more_image, 800, 'media/product/');
                    if ($response['status'] == 'success') {
                        $img_arr[$key] = $response['path_name'];
                    }
                }
                $more_images_path_names = trim(implode('|', $img_arr), '|');

                $old_more_images = explode('|', $product->more_images);
                foreach ($old_more_images as $key => $image) {
                    if (file_exists($image)) {
                        unlink($image);
                    }
                }
            } else {
                $more_images_path_names = $product->more_images;
            }

            if ($request->name == $product->name) {
                $slug = $product->slug;
            } else {
                $slug = Str::slug($request->name);
                $same_slug_count = Product::withTrashed()->where('slug', 'LIKE', $slug . '%')->whereNotIn('id', [$id])->count();
                $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
                $slug .= $slug_suffix;
            }

            $collection = collect($request->all());
            $choice_options = array();
            $attributes = [];

            if (isset($collection['choice_no']) && $collection['choice_no']) {
                $str = '';
                $item = array();
                foreach ($collection['choice_no'] as $key => $no) {
                    $str = 'choice_options_' . $no;
                    $item['attribute_id'] = $no;
                    $attribute_data = array();

                    if (isset($collection[$str])) {
                        foreach ($collection[$str] as $key => $eachValue) {
                            array_push($attribute_data, $eachValue);
                        }
                        $attributes[] = $no;
                    }
                    if (count($attribute_data) == 0) {
                        continue;
                    }
                    $item['values'] = $attribute_data;
                    array_push($choice_options, $item);
                }
            }
            $choice_options = json_encode($choice_options, JSON_UNESCAPED_UNICODE);

            // Update Product
            $product->update([
                'company_id' => Auth::user()->company_id ?? 1,
                'product_type' => $request->product_type ?? 'Consumer',
                'attribute_id' => $request->product_type == 'Consumer' || is_null($request->product_type) ? $request->attribute_id : NULL,
                'category_id' => isset($request->category_id) ? $request->category_id : $product->category_id,
                'name' => $request->name,
                'type' => $request->type,
                'trade_offer' => $request->trade_offer,
                'do_ratio' => $request->do_ratio,
                'code' => $request->code,
                'slug' => $slug,
                'thumbnail' => isset($request->thumbnail) ? HelperClass::saveImage($request->thumbnail, 800, 'media/product/', $product->thumbnail) : $product->thumbnail,
                'more_images' => $more_images_path_names,
                'short_description' => $request->short_description,
                'description' => $request->description,
                'additional_info' => $request->additional_info,
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,
                'meta_keyword' => $request->meta_keyword,
                'alert_quantity' => $request->alert_quantity,
                'min_order' => $request->min_order,
                'show_on_website' => $request->show_on_website,
                'video' => $video_path_name,
                'video_id' => $request->video_id,
                'ctn_size' => $request->ctn_size ?? 0,
                'choice_options' => $choice_options,
                'attributes' => json_encode($attributes),
                'trending' => $request->trending,
                'serial' => $request->serial ?? 0,
                'allowed_investor' => !is_null($request->allowed_investor) ? $request->allowed_investor : 1,
                'shared_profit' => $request->shared_profit,
                'updated_by' => Auth::user()->id,
            ]);

            ProductPrice::where('product_id', $id)->delete();
            if ($request->product_type == 'Consumer' || is_null($request->product_type)) {
                ProductPrice::create([
                    'product_id' => $id,
                    'lifting_price' => $request->lifting_price,
                    'sale_price' => $request->sale_price,
                    'online_price' => $request->online_price,
                    'discount' => isset($request->discount) ? $request->discount : 0,
                    'discount_tk' => isset($request->discount_tk) ? $request->discount_tk : 0,
                ]);
            }

            ProductVendor::where('product_id', $id)->delete();
            foreach ($request->vendor_id as $key => $vendor_id) {
                ProductVendor::create(['product_id' => $product->id, 'vendor_id' => $vendor_id]);
            }

            foreach ($product->sku as $key => $sku) {
                if (file_exists($sku->image)) {
                    unlink($sku->image);
                }
            }

            $usedIds = LiftingProduct::where('product_id', $id)->pluck('variant_id')->toArray();
            foreach ($product->sku->whereNotIn('id', $usedIds) as $key => $sku) {
                $sku->delete();
            }

            if ($request->product_type == 'Fashion') {
                $this->productStockService->update($request->only(['choice_no']), $product);
            }
        });

        return redirect()->route('admin.product.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            $data = Product::onlyTrashed()->findOrFail($id);
            $data->restore();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items Permanent
        if (request()->has('id') && request()->has('parmanent') && request('parmanent') == 'true') {
            foreach (request('id') as $id) {
                $data = Product::onlyTrashed()->findOrFail($id);
                if (count($data->orderProducts) == 0) {
                    if (file_exists($data->thumbnail)) {
                        unlink($data->thumbnail);
                    }
                    $old_more_images = explode('|', $data->more_images);
                    foreach ($old_more_images as $key => $image) {
                        if (file_exists($image)) {
                            unlink($image);
                        }
                    }
                    if (file_exists($data->video)) {
                        unlink($data->video);
                    }
                    ProductPrice::where('product_id', $id)->delete();
                    $data->forceDelete();
                } else {
                    return response()->json(['status' => 'error']);
                }
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item Permanent
        if (request()->has('parmanent') && request('parmanent') == 'true') {
            $data = Product::onlyTrashed()->findOrFail($id);
            if (count($data->orderProducts) == 0) {
                if (file_exists($data->thumbnail)) {
                    unlink($data->thumbnail);
                }
                $old_more_images = explode('|', $data->more_images);
                foreach ($old_more_images as $key => $image) {
                    if (file_exists($image)) {
                        unlink($image);
                    }
                }
                if (file_exists($data->video)) {
                    unlink($data->video);
                }
                ProductPrice::where('product_id', $id)->delete();
                $data->forceDelete();
            } else {
                return response()->json(['status' => 'error']);
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items
        if (request()->has('id')) {
            foreach (request('id') as $id) {
                $data = Product::findOrFail($id);
                if (count($data->orderProducts) == 0) {
                    $data->update(['deleted_by' => Auth::user()->id]);
                    $data->delete();
                } else {
                    return response()->json(['status' => 'error']);
                }
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item
        $data = Product::findOrFail($id);
        if (count($data->orderProducts) == 0) {
            $data->update(['deleted_by' => Auth::user()->id]);
            $data->delete();
        } else {
            return response()->json(['status' => 'error']);
        }
        return response()->json(['status' => 'success']);
    }
}

<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Helper\ResponseHelper;
use App\Models\Category;
use App\Models\Product;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateProduct;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {       
       
        if($request->ajax()) {
            $products = Product::anyTrash($request->trash)->get();
            return DataTables::of($products)
                        ->addColumn('name_my', function($product) {
                            $name_str = json_decode($product->name);
                            return $name_str->my;
                        })
                        ->addColumn('name_en', function ($product) {
                            $name_str = json_decode($product->name);
                            return $name_str->en;
                        })
                        ->editColumn('category_id', function($product) {
                            if($product->category_id) {
                                $encoded = json_decode($product->parent->name);
                                return $encoded->my . ' (' . $encoded->en . ')';
                            }
                            return '-';
                        })
                        ->editColumn('main_photo', function($product) {
                            return '<img src="http://radiant-brook-65745.herokuapp.com/public/storage'.Storage::url($product->main_photo).'" border="0" width="40" class="img-rounded" align="center" />';
                      
                        })
                        ->editColumn('description', function($product) {
                            return Str::limit($product->description, 30);
                      
                        })
                        ->addColumn('plus-icon', function() {
                            return null;
                        })
                        ->addColumn('action', function ($product) use ($request) {
                            $detail_btn = '';
                            $restore_btn = '';
                            $edit_btn = '<a class="edit text text-primary mr-2" href="' . route('admin.products.edit', ['product' => $product->id]) . '"><i class="far fa-edit fa-lg"></i></a>';

                            if ($request->trash == 1) {
                                $restore_btn = '<a class="restore text text-warning mr-2" href="#" data-id="' . $product->id . '"><i class="fa fa-trash-restore fa-lg"></i></a>';
                                $trash_or_delete_btn = '<a class="destroy text text-danger mr-2" href="#" data-id="' . $product->id . '"><i class="fa fa-minus-circle fa-lg"></i></a>';
                            } else {
                                $trash_or_delete_btn = '<a class="trash text text-danger mr-2" href="#" data-id="' . $product->id . '"><i class="fas fa-trash fa-lg"></i></a>';
                            }

                            return "${detail_btn} ${edit_btn} ${restore_btn} ${trash_or_delete_btn}";
                        })
                        ->rawColumns(['plus-icon', 'name_my', 'name_en', 'action','main_photo','description'])
                        ->make(true);
        }
        return view('backend.admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $active_categories = Category::noTrash();
        $main_categories = $active_categories->main()->get();
        $active_products = Product::noTrash();
        $suggest_rank = $active_products->max('rank') + 1;
        return view('backend.admin.products.create', compact('main_categories', 'suggest_rank'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
       
        $name_str = json_encode(['my' => $request->name_my, 'en' => $request->name_en]);
        $slug = Str::slug($request->slug ?? $request->name_en);
        $parent_id = $request->parent_id;
        $found = false;
        
        $check_slug = Category::where('slug', $slug)->first();
        if($check_slug) {
            return back()->withErrors(['msg' => 'Link is already exist.'])->withInput();
        }

        if(! empty($parent_id)) {
            $found = Category::find($parent_id);
        }

        if ($request->hasFile('main_photo')) {
            $photo = $request->file('main_photo')->store(config('tour_packages.paths.products'), 'public');
        } 
        if($request->hasfile('feature_photo'))
         {

            foreach($request->file('feature_photo') as $image)
            {
                $image = $image->store(config('tour_packages.paths.products'), 'public');

                $data[] = $image;  
            }
         }

        Product::create([
            'name' => $name_str,
            'slug' => $slug,
            'rank' => abs($request->rank) ?? (Category::maxRank() + 1),
            'category_id' => ($found) ? $parent_id : 0,
            'main_photo' => $photo,
            'feature_photo'=>json_encode($data),
            'prices'=>$request->prices,
            'description'=> $request->description
        ]);


        return redirect()->route('admin.products.index')->with('success', 'New Product Successfully Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $main_categories = Category::noTrash()->main()->get();
        return view('backend.admin.products.edit', compact('product', 'main_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProduct $request, Product $product)
    {
        $data=[];
        $name_str = json_encode(['my' => $request->name_my, 'en' => $request->name_en]);
        $slug = Str::slug($request->slug ?? $request->name_en);
        $parent_id = $request->parent_id;
        $found = false;

        $check_slug = Product::whereNotIn('id', [$product->id])->where('slug', $slug)->first();
        if ($check_slug) {
            return back()->withErrors(['msg' => 'Link is already exist.'])->withInput();
        }

        if (!empty($parent_id)) {
            $found = Category::find($parent_id);
        }

        if ($request->hasFile('main_photo')) {
            $photo = $request->file('main_photo')->store(config('tour_packages.paths.products'), 'public');
        } 
        if($request->hasfile('feature_photo'))
         {

            foreach($request->file('feature_photo') as $image)
            {
                $image = $image->store(config('tour_packages.paths.products'), 'public');

                $data = $image;  
            }
         }

        $product->update([
            'name' => $name_str,
            'slug' => $slug,
            'rank' => abs($request->rank) ?? (Category::maxRank() + 1),
            'category_id' => ($found) ? $parent_id : 0,
            'main_photo' => ($photo) ? $photo :$product->main_photo,
            'feature_photo'=>($data)?json_encode($data) : $product->feature_photo,
            'prices'=>$request->prices,
            'description'=> $request->description
        ]);


        return redirect()->route('admin.products.index')->with('success', 'Successfully Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

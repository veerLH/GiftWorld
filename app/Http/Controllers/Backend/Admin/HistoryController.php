<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Models\{History,Category};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Storage;
use QrCode;
class HistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $histories = History::anyTrash($request->trash)->get();
            return DataTables::of($histories)
                        ->addColumn('name_my', function($history) {
                            $name_str = json_decode($history->name);
                            return Str::limit($name_str->my, 30);
                         
                        })
                        ->addColumn('name_en', function ($history) {
                            $name_str = json_decode($history->name);
                            return Str::limit($name_str->en, 30);
                        })
                        ->addColumn('name_jp', function ($history) {
                            $name_str = json_decode($history->name);
                            return Str::limit($name_str->jp, 30);
                        })
                        ->editColumn('parent_id', function($category) {
                            if($category->parent_id) {
                                $encoded = json_decode($category->parent->name);
                                return $encoded->my . ' (' . $encoded->en . ')';
                            }
                            return '-';
                        })
                        ->editColumn('image', function($history) {
                            return '<img src="'.Storage::url($history->image).'" border="0" width="40" class="img-rounded" align="center" />';
                      
                        })
                        ->addColumn('plus-icon', function() {
                            return null;
                        })
                        ->addColumn('action', function ($history) use ($request) {
                            $detail_btn = '';
                            $restore_btn = '';
                            $edit_btn = '<a class="edit text text-primary mr-2" href="' . route('admin.histories.edit', ['history' => $history->id]) . '"><i class="far fa-edit fa-lg"></i></a>';
                            $show_btn = '<a class="edit text text-primary mr-2" href="' . route('admin.histories.show', ['history' => $history->id]) . '"><i class="far fa-eye fa-lg"></i></a>';
                            if ($request->trash == 1) {
                                $restore_btn = '<a class="restore text text-warning mr-2" href="#" data-id="' . $history->id . '"><i class="fa fa-trash-restore fa-lg"></i></a>';
                                $trash_or_delete_btn = '<a class="destroy text text-danger mr-2" href="#" data-id="' . $history->id . '"><i class="fa fa-minus-circle fa-lg"></i></a>';
                            } else {
                                $trash_or_delete_btn = '<a class="trash text text-danger mr-2" href="#" data-id="' . $history->id . '"><i class="fas fa-trash fa-lg"></i></a>';
                            }

                            return "${detail_btn} ${edit_btn} ${restore_btn} ${trash_or_delete_btn} ${show_btn}";
                        })
                        ->rawColumns(['plus-icon', 'name_my', 'name_en', 'action','image'])
                        ->make(true);
        }
        return view('backend.admin.history.index');
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
        $active_products = History::noTrash();
        $suggest_rank = $active_products->max('rank') + 1;
        return view('backend.admin.history.create', compact('main_categories', 'suggest_rank'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name_str = json_encode(['my' => $request->name_my, 'en' => $request->name_en, 'jp' => $request->name_jp]);
        $slug = Str::slug($request->slug ?? $request->title);
        $parent_id = $request->parent_id;
        $found = false;
        
        $check_slug = Category::where('slug', $slug)->first();
        if($check_slug) {
            return back()->withErrors(['msg' => 'Link is already exist.'])->withInput();
        }

        if(! empty($parent_id)) {
            $found = Category::find($parent_id);
        }

        if ($request->hasFile('image')) {
            $photo = $request->file('image')->store(config('tour_packages.paths.products'), 'public');
        } 
    

        History::create([
            'name' => $name_str,
            'slug' => $slug,
            'rank' => abs($request->rank) ?? (History::maxRank() + 1),
            'category_id' => ($found) ? $parent_id : 0,
            'image' => $photo,
            'title'=> $request->title
        ]);


        return redirect()->route('admin.histories.index')->with('success', 'New Product Successfully Created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        QrCode::size(500)
            ->format('png')
            ->generate('http://127.0.0.1:8000/api/history/'.$id, public_path('images/qr.png'));

        return view('backend.admin.history.show',compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

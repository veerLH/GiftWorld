@extends('backend.admin.layouts.app')

@section('meta_title', 'Products')
@section('page_title', 'Products')
@section('page_title_icon')
<i class="pe-7s-menu icon-gradient bg-ripe-malin"></i>
@endsection

@section('page_title_buttons')
<div class="d-flex justify-content-end">
    <div class="custom-control custom-switch p-2 mr-3">
        <input type="checkbox" class="custom-control-input trashswitch" id="trashswitch">
        <label class="custom-control-label" for="trashswitch"><strong>Trash</strong></label>
    </div>

    {{-- @can('add_category') --}}
    <a href="{{route('admin.products.create')}}" title="Add Category" class="btn btn-primary action-btn">Add Products</a>
    {{-- @endcan --}}
</div>
@endsection

@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="align-middle mb-0 table table-hover data-table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name (Burmese) & (Eng)</th>
                                <th>Rank</th>
                                <th>Link (Slug)</th>
                                <th>Category</th>
                                <th>Main Photo</th>                                
                                <th>Prices</th>
                                <th>Description</th>
                                <th class="no-sort action">Action</th>
                                <th class="d-none hidden">Updated at</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
var route_model_name = "products";
var app_table;
$(function() {
    app_table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: `${PREFIX_URL}/admin/${route_model_name}?trash=0`,
        columns: [
            {data: "plus-icon", name: "plus-icon", defaultContent: null},
            {data: 'name_en', name: 'name_en', defaultContent: "-", class: ""},
            {data: 'rank', name: 'rank', defaultContent: "-", class: ""},
            {data: 'slug', name: 'slug', defaultContent: "-", class: ""},
            {data: 'category_id', name: 'category_id', defaultContent: "-", class: ""},
            {data: 'main_photo', name: 'main_photo',  orderable: false, searchable: false,},
            {data: 'prices', name: 'prices', defaultContent: "-", class: ""},
            {data: 'description', name: 'description', defaultContent: "-", class: ""},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'updated_at', name: 'updated_at', defaultContent: null}
        ],
        order: [
            [4, 'asc']
        ],
        responsive: {
            details: {type: "column", target: 0}
        },
        columnDefs: [
            {targets: "no-sort", orderable: false},
            {className: "control", orderable: false, targets: 0},
            {targets: "hidden", visible: false}
        ],
        pagingType: "simple_numbers",
        language: {
            paginate: {previous: "«", next: "»"},
            processing: `<div class="processing_data">
                <div class="spinner-border text-info" role="status">
                    <span class="sr-only">Loading...</span>
                </div></div>`
        }
    });
});
</script>
@include('backend.admin.layouts.assets.trash_script')
@endsection
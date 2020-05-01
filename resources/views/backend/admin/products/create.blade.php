@extends('backend.admin.layouts.app')

@section('meta_title', 'Add Products')
@section('page_title', 'Add Products')
@section('page_title_icon')
<i class="pe-7s-menu icon-gradient bg-ripe-malin"></i>
@endsection

@section('content')
@include('layouts.errors_alert')
<div class="row">
    <div class="col-md-12">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form action="{{ route('admin.products.store') }}" method="post" id="form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name_my">Name (Burmese)</label>
                                <input type="text" name="name_my" id="name_my" class="form-control" value="{{old('name_my')}}" autofocus>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name_en">Name (English)</label>
                                <input type="text" name="name_en" id="name_en" class="form-control" value="{{old('name_en')}}">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="slug">Link (Optional)</label>
                                <input type="text" name="slug" id="slug" class="form-control">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rank">Rank</label>
                                <input type="number" name="rank" id="rank" class="form-control" value="{{$suggest_rank}}" min="0">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="parent_id">Main Category</label>
                                <select class="form-control select2" name="parent_id" id="parent_id">
                                    <option value=""></option>
                                    @foreach($main_categories as $each_category)
                                        @php
                                        $category_decoded = json_decode($each_category->name);
                                        @endphp
                                        <option value="{{$each_category->id}}">{{$category_decoded->my}} ({{$category_decoded->en}})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="main_photo">Main Photo </label>
                                <input type="file" name="main_photo" id="main_photo" class="form-control-file">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="feature_photo">Feature Photo (max 4 photo)</label>
                                <input type="file" name="feature_photo[]" id="feature_photo" class="form-control-file" multiple>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="prices">Price</label>
                                <input name="prices" id="prices" class="form-control" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <Textarea name="description" id="description" class="form-control"></Textarea>
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-danger mr-5">Cancel</a>
                            <input type="submit" value="Add" class="btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
{!! JsValidator::formRequest('App\Http\Requests\ProductRequest', '#form') !!}
@endsection
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
                <form action="{{ route('admin.histories.store') }}" method="post" id="form" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" id="title" class="form-control" value="{{old('title')}}" autofocus>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name_my">Name (Burmese)</label>
                                <Textarea name="name_my" id="name_my" class="form-control">{{old('name_my')}}</Textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name_en">Name (English)</label>
                                <Textarea name="name_en" id="name_en" class="form-control">{{old('name_en')}}</Textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name_jp">Name (Japnese)</label>
                                <Textarea name="name_jp" id="name_jp" class="form-control">{{old('name_jp')}}</Textarea>
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
                                <label for="main_photo">Image </label>
                                <input type="file" name="image" id="image" class="form-control-file">
                            </div>
                        </div>
                        
                        
                       
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12 text-center">
                            <a href="{{ route('admin.histories.index') }}" class="btn btn-danger mr-5">Cancel</a>
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
{!! JsValidator::formRequest('App\Http\Requests\HistoryRequest', '#form') !!}
@endsection
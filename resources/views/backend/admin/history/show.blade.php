@extends('backend.admin.layouts.app')

@section('meta_title', 'Add Category')
@section('page_title', 'Add Category')
@section('page_title_icon')
<i class="pe-7s-menu icon-gradient bg-ripe-malin"></i>
@endsection

@section('content')
@include('layouts.errors_alert')
<div class="container">
    <div class="row">
        <div class="col-8 mx-auto">
            {!! QrCode::size(250)->generate('http://127.0.0.1:8000/api/history/'.$id); !!}
        </div>
    </div>
</div>
    
@endsection
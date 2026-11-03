@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{url('master-items/form/new')}}" class="btn btn-secondary">+ Master Items Baru</a>
                    <!-- <a href="{{url('kategori-items/form/new')}}" class="btn btn-secondary">+ Kategori Items Baru</a> -->
                    <a href="{{url('kategori-items')}}" class="btn btn-info text-white">Lihat List Kategori</a>
                </div>
            </div>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Daftar Master Items</span>
                    <div>
                        <!-- <a href="{{url('kategori-items')}}" class="btn btn-info btn-sm text-white me-2">List Kategori</a> -->
                        <a href="{{url('master-items/export-excel')}}" class="btn btn-success btn-sm" id="btn-export-excel-header">
                            Export Excel
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @include('master_items.index.filter')
                    @include('master_items.index.table')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@include('master_items.index.js')
@endsection

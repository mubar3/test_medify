@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex gap-2 mb-2">
                <a href="{{url('kategori-items')}}" class="btn btn-secondary">&larr; Kembali ke Daftar Kategori</a>
                <a href="{{url('master-items')}}" class="btn btn-outline-secondary">Ke Daftar Item</a>
            </div>
            <div class="card">

                @if($method == 'new')
                <div class="card-header">Buat Kategori Item Baru</div>
                @else
                <div class="card-header">Edit Kategori Item</div>
                @endif

                <div class="card-body">
                    @include('master_items.form_kategori.form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection

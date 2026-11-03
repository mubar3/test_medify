@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="form-group mb-2">
                <a href="{{url('master-items')}}" class="btn btn-secondary">Kembali ke Daftar Item</a>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Master Item</span>
                    <a href="{{url('master-items/export-pdf/'.$data->kode)}}" target="_blank" class="btn btn-danger btn-sm">
                        Export PDF
                    </a>
                </div>

                <div class="card-body">
                    <!-- <div class="mb-3">
                        <a href="{{url('master-items/export-pdf/'.$data->kode)}}" target="_blank" class="btn btn-danger">
                            Export PDF
                        </a>
                    </div> -->
                    <table class="table table-bordered mb-3">
                        <tr>
                            <th style="width: 200px;">Nama</th>
                            <td style="width: 20px;">:</td>
                            <td>{{$data->nama}}</td>
                        </tr>
                        <tr>
                            <th>Harga Beli</th>
                            <td>:</td>
                            <td>{{$data->harga_beli}}</td>
                        </tr>
                        <tr>
                            <th>Laba</th>
                            <td>:</td>
                            <td>{{$data->laba}}%</td>
                        </tr>
                        <tr>
                            <th>Harga Jual</th>
                            <td>:</td>
                            <td>{{$data->harga_beli + $data->harga_beli * $data->laba / 100 }}</td>
                        </tr>
                        <tr>
                            <th>Supplier</th>
                            <td>:</td>
                            <td>{{$data->supplier}}</td>
                        </tr>
                        <tr>
                            <th>Jenis</th>
                            <td>:</td>
                            <td>{{$data->jenis}}</td>
                        </tr>
                        <tr>
                            <th>Kategori</th>
                            <td>:</td>
                            <td>{{$data->kategori?->kode}} | {{$data->kategori?->nama}}</td>
                        </tr>
                    </table>
                    <div class="mt-3">
                        <!-- <a class="btn btn-danger" href="{{url('master-items/export-pdf/'.$data->kode)}}" target="_blank">Export PDF</a> -->
                        <a class="btn btn-info" href="{{url('master-items/form/edit')}}/{{$data->id}}">Edit</a>
                        <a class="btn btn-danger" href="{{url('master-items/delete')}}/{{$data->id}}" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                    </div>
                </div>
            </div>

            @if(isset($item_serupa) && $item_serupa->count() > 0)
            <div class="card mt-4">
                <div class="card-header">
                    Item Serupa dengan Kategori Sama ({{ $data->kategori?->nama ?? '-' }})
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Jenis</th>
                                    <th>Harga Beli</th>
                                    <th>Laba</th>
                                    <th>Harga Jual</th>
                                    <th>Supplier</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($item_serupa as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ $item->jenis }}</td>
                                    <td>{{ $item->harga_beli }}</td>
                                    <td>{{ $item->laba }}%</td>
                                    <td>{{ $item->harga_beli + ($item->harga_beli * $item->laba / 100) }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>
                                        <a href="{{ url('master-items/view/' . $item->kode) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
@section('js')
@endsection

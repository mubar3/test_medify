@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex gap-2">
                    <a href="{{url('master-items')}}" class="btn btn-secondary">
                        &larr; Kembali ke Daftar Item
                    </a>
                    <a href="{{url('kategori-items/form/new')}}" class="btn btn-primary">
                        + Kategori Items Baru
                    </a>
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
                    <span>Daftar Kategori Items</span>
                    <span class="badge bg-primary">{{ count($kategori) }} Kategori</span>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 50px;" class="text-center">No</th>
                                    <th style="width: 150px;">Kode Kategori</th>
                                    <th>Nama Kategori</th>
                                    <th style="width: 130px;" class="text-center">Jumlah Item</th>
                                    <th style="width: 160px;" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kategori as $index => $item)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->kode }}</span>
                                    </td>
                                    <td><strong>{{ $item->nama }}</strong></td>
                                    <td class="text-center">
                                        <span class="badge bg-info text-dark">
                                            {{ $item->items_count ?? 0 }} item
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{url('kategori-items/form/edit/'.$item->id)}}" class="btn btn-sm btn-info text-white">
                                                Edit
                                            </a>
                                            <a href="{{url('kategori-items/delete/'.$item->id)}}" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori \'{{ $item->nama }}\'?');">
                                                Delete
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">
                                        Belum ada data kategori. Silakan buat baru.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


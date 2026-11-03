<div id="filter-container" class="mb-4">
    <h4>Filter</h4>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-2">
                <label class="form-label">Kode</label>
                <input type="text" class="form-control" id="filter-kode" placeholder="Kode item">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-2">
                <label class="form-label">Nama</label>
                <input type="text" class="form-control" id="filter-nama" placeholder="Nama item">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group mb-2">
                <label class="form-label">Kategori (Nama / Kode)</label>
                <input type="text" class="form-control" id="filter-kategori" list="kategori-list" placeholder="Nama atau kode kategori">
                @if(isset($kategori) && count($kategori) > 0)
                <datalist id="kategori-list">
                    @foreach($kategori as $kat)
                        <option value="{{ $kat->nama }}">{{ $kat->kode }} - {{ $kat->nama }}</option>
                        <option value="{{ $kat->kode }}">{{ $kat->kode }} - {{ $kat->nama }}</option>
                    @endforeach
                </datalist>
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label class="form-label">Harga Min</label>
                <input type="number" class="form-control" id="filter-harga-min" placeholder="Harga minimum">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group mb-2">
                <label class="form-label">Harga Max</label>
                <input type="number" class="form-control" id="filter-harga-max" placeholder="Harga maksimum">
            </div>
        </div>
    </div>
    <div class="mt-2">
        <button class="btn btn-primary btn-get-data">Filter</button>
        <!-- <button class="btn btn-success ms-2 btn-export-excel" id="btn-export-excel">Export Excel</button> -->
        <span id="loading-filter" class="ms-2" style="display: none;">Loading...</span>
    </div>
</div>

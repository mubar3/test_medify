<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Master Item - {{ $data->nama }}</title>
    <style>
        @page {
            margin: 20mm 15mm 20mm 15mm;
        }

        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 10pt;
            color: #333333;
            line-height: 1.4;
        }

        header {
            position: fixed;
            top: -15mm;
            left: 0;
            right: 0;
            height: 35px;
            border-bottom: 2px solid #0d6efd;
            padding-bottom: 5px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-title {
            font-size: 13pt;
            font-weight: bold;
            color: #0d6efd;
            text-transform: uppercase;
        }

        .header-subtitle {
            font-size: 9pt;
            color: #6c757d;
            text-align: right;
        }

        footer {
            position: fixed;
            bottom: -12mm;
            left: 0;
            right: 0;
            height: 25px;
            border-top: 1px solid #dee2e6;
            padding-top: 5px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            font-size: 8.5pt;
            color: #6c757d;
        }

        .section-title {
            font-size: 11pt;
            font-weight: bold;
            color: #212529;
            margin-top: 15px;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 1.5px solid #0d6efd;
        }

        /* Detail Table */
        .table-detail {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table-detail tr th,
        .table-detail tr td {
            padding: 5px 8px;
            vertical-align: top;
        }

        .table-detail th {
            width: 130px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            background-color: #f8f9fa;
            border-bottom: 1px solid #edf2f7;
        }

        .table-detail .colon {
            width: 15px;
            text-align: center;
            font-weight: bold;
            color: #495057;
            background-color: #f8f9fa;
            border-bottom: 1px solid #edf2f7;
        }

        .table-detail td.value {
            color: #212529;
            border-bottom: 1px solid #edf2f7;
        }

        /* Items Table */
        .table-items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        .table-items th {
            background-color: #f1f4f9;
            color: #1e293b;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            font-size: 9pt;
            text-align: left;
        }

        .table-items td {
            border: 1px solid #e2e8f0;
            padding: 6px 8px;
            font-size: 8.5pt;
        }

        .table-items tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge-kategori {
            background-color: #e0f2fe;
            color: #0369a1;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <header>
        <table class="header-table">
            <tr>
                <td class="header-title">Detail Master Item</td>
                <td class="header-subtitle">Kode: <strong>{{ $data->kode }}</strong></td>
            </tr>
        </table>
    </header>

    <footer>
        <table class="footer-table">
            <tr>
                <td style="text-align: left;">
                    Dicetak pada: {{ $timestamp }}
                </td>
                <td style="text-align: right;">
                    {{ config('app.name', 'Laravel') }}
                </td>
            </tr>
        </table>
    </footer>

    <main>
        <div class="section-title">Detail Item</div>
        <table class="table-detail">
            <tr>
                <th>Nama</th>
                <td class="colon">:</td>
                <td class="value"><strong>{{ $data->nama }}</strong></td>
            </tr>
            <tr>
                <th>Harga Beli</th>
                <td class="colon">:</td>
                <td class="value">Rp {{ number_format($data->harga_beli, 0, ',', '.') }} ({{ $data->harga_beli }})</td>
            </tr>
            <tr>
                <th>Laba</th>
                <td class="colon">:</td>
                <td class="value">{{ $data->laba }}%</td>
            </tr>
            <tr>
                <th>Harga Jual</th>
                <td class="colon">:</td>
                <td class="value">
                    @php
                        $harga_jual = $data->harga_beli + ($data->harga_beli * $data->laba / 100);
                    @endphp
                    Rp {{ number_format($harga_jual, 0, ',', '.') }} ({{ $harga_jual }})
                </td>
            </tr>
            <tr>
                <th>Supplier</th>
                <td class="colon">:</td>
                <td class="value">{{ $data->supplier }}</td>
            </tr>
            <tr>
                <th>Jenis</th>
                <td class="colon">:</td>
                <td class="value">{{ $data->jenis }}</td>
            </tr>
            <tr>
                <th>Kategori</th>
                <td class="colon">:</td>
                <td class="value">
                    <span class="badge-kategori">{{ $data->kategori?->kode }} | {{ $data->kategori?->nama }}</span>
                </td>
            </tr>
        </table>

        <div class="section-title">
            Item Serupa dengan Kategori yang Sama 
            @if($data->kategori)
                ({{ $data->kategori->nama }})
            @endif
        </div>

        <table class="table-items">
            <thead>
                <tr>
                    <th class="text-center" style="width: 5%;">No</th>
                    <th style="width: 12%;">Kode</th>
                    <th style="width: 25%;">Nama Item</th>
                    <th style="width: 12%;">Jenis</th>
                    <th class="text-right" style="width: 15%;">Harga Beli</th>
                    <th class="text-center" style="width: 8%;">Laba</th>
                    <th class="text-right" style="width: 15%;">Harga Jual</th>
                    <th style="width: 18%;">Supplier</th>
                </tr>
            </thead>
            <tbody>
                @forelse($item_serupa as $index => $item)
                    @php
                        $item_harga_jual = $item->harga_beli + ($item->harga_beli * $item->laba / 100);
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item->kode }}</td>
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->jenis }}</td>
                        <td class="text-right">Rp {{ number_format($item->harga_beli, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $item->laba }}%</td>
                        <td class="text-right">Rp {{ number_format($item_harga_jual, 0, ',', '.') }}</td>
                        <td>{{ $item->supplier }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 15px; color: #6c757d;">
                            Tidak ada item serupa lainnya dengan kategori yang sama.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>


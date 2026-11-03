<?php

namespace App\Http\Controllers;

use App\Models\MasterItem;
use App\Models\KategoriItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SimpleXlsxExporter;

class MasterItemsController extends Controller
{
    public function index()
    {
        $kategori = KategoriItem::all();
        return view('master_items.index.index', compact('kategori'));
    }

    public function search(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;
        $kategori = $request->kategori;

        $data_search = MasterItem::query();

        if (!empty($kode)) $data_search = $data_search->where('kode', $kode);
        if (!empty($nama)) $data_search = $data_search->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $data_search = $data_search->where('harga_beli', '>=', $hargamin);
        if (!empty($hargamax)) $data_search = $data_search->where('harga_beli', '<=', $hargamax);
        if (!empty($kategori)) {
            $kategoriList = array_filter(array_map('trim', explode(',', $kategori)));
            $data_search = $data_search->whereHas('kategori', function ($q) use ($kategoriList) {
                $q->where(function ($sub) use ($kategoriList) {
                    foreach ($kategoriList as $kat) {
                        $sub->orWhere('nama', 'LIKE', '%' . $kat . '%')
                            ->orWhere('kode', 'LIKE', '%' . $kat . '%');
                    }
                });
            });
        }

        $data_search = $data_search->select('kode', 'nama', 'jenis', 'harga_beli', 'laba', 'supplier')->orderBy('id')->get();

        return json_encode([
            'status' => 200,
            'data' => $data_search
        ]);
    }

    public function exportExcel(Request $request)
    {
        $kode = $request->kode;
        $nama = $request->nama;
        $hargamin = $request->hargamin;
        $hargamax = $request->hargamax;
        $kategori = $request->kategori;

        $query = MasterItem::with('kategori');

        if (!empty($kode)) $query = $query->where('kode', $kode);
        if (!empty($nama)) $query = $query->where('nama', 'LIKE', '%' . $nama . '%');
        if (!empty($hargamin)) $query = $query->where('harga_beli', '>=', $hargamin);
        if (!empty($hargamax)) $query = $query->where('harga_beli', '<=', $hargamax);
        if (!empty($kategori)) {
            $kategoriList = array_filter(array_map('trim', explode(',', $kategori)));
            $query = $query->whereHas('kategori', function ($q) use ($kategoriList) {
                $q->where(function ($sub) use ($kategoriList) {
                    foreach ($kategoriList as $kat) {
                        $sub->orWhere('nama', 'LIKE', '%' . $kat . '%')
                            ->orWhere('kode', 'LIKE', '%' . $kat . '%');
                    }
                });
            });
        }

        $items = $query->orderBy('id')->get();

        $headers = [
            'No',
            'Nama kategori',
            'Nama items',
            'Nama supplier',
            'Harga',
            'Laba',
            'Hargajual'
        ];

        $rows = [];
        foreach ($items as $idx => $item) {
            $kategoriNama = '-';
            if ($item->kategori instanceof \Illuminate\Support\Collection || is_array($item->kategori)) {
                $kategoriNama = $item->kategori->pluck('nama')->implode(', ');
            } elseif ($item->kategori) {
                $kategoriNama = $item->kategori->nama;
            }

            $harga = (int)$item->harga_beli;
            $laba = (int)$item->laba;
            $hargaJual = $harga + ($harga * $laba / 100);

            $rows[] = [
                $idx + 1,
                $kategoriNama,
                $item->nama,
                $item->supplier,
                $harga,
                $laba,
                $hargaJual
            ];
        }

        if ($request->get('format') === 'csv') {
            $csvFileName = 'master_items_' . date('Ymd_His') . '.csv';
            $callback = function () use ($headers, $rows) {
                $file = fopen('php://output', 'w');
                fputs($file, "\xEF\xBB\xBF");
                fputcsv($file, $headers);
                foreach ($rows as $row) {
                    fputcsv($file, $row);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, [
                "Content-type" => "text/csv; charset=UTF-8",
                "Content-Disposition" => "attachment; filename=$csvFileName",
                "Pragma" => "no-cache",
                "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
                "Expires" => "0"
            ]);
        }

        $filename = 'master_items_' . date('Ymd_His') . '.xlsx';
        $tempDir = storage_path('app');
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }
        $tempPath = $tempDir . '/' . $filename;

        SimpleXlsxExporter::create($headers, $rows, $tempPath);

        return response()->download($tempPath, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = [];
        } else {
            $item = MasterItem::find($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        $data['kategori'] = KategoriItem::all();
        return view('master_items.form.index', $data);
    }

    public function singleView($kode)
    {
        $item = MasterItem::where('kode', $kode)
            ->with('kategori')
            ->first();

        $item_serupa = ($item && $item->kategori_id)
            ? MasterItem::where('kategori_id', $item->kategori_id)
                ->where('id', '!=', $item->id)
                ->with('kategori')
                ->get()
            : collect();

        return view('master_items.single.index', [
            'data' => $item,
            'item_serupa' => $item_serupa
        ]);
    }

    public function exportPdf(Request $request, $kode)
    {
        $data = MasterItem::where('kode', $kode)
            ->with('kategori')
            ->firstOrFail();

        $item_serupa = $data->kategori_id
            ? MasterItem::where('kategori_id', $data->kategori_id)
                ->where('id', '!=', $data->id)
                ->with('kategori')
                ->get()
            : collect();

        $timestamp = Carbon::now()->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s \W\I\B');

        $pdf = Pdf::loadView('master_items.single.pdf', compact('data', 'item_serupa', 'timestamp'));
        $pdf->setPaper('a4', 'portrait');

        if ($request->has('download')) {
            return $pdf->download('master-item-' . $data->kode . '.pdf');
        }

        return $pdf->stream('master-item-' . $data->kode . '.pdf');
    }

    public function download_pdf_single($kode = null)
    {
        return $this->exportPdf(request(), $kode ?? request()->route('kode'));
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        $edit = false;
        if ($method == 'new') {
            $data_item = new MasterItem;
            $kode = MasterItem::count('id');
            $kode = $kode + 1;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);
            sleep(3);
        } else {
            $edit = true;
            $data_item = MasterItem::find($id);
            $kode = $data_item->kode;
        }

        $file_foto = null;
        if($request->hasFile('foto')){
            $file_foto = 'ft'.Carbon::now()->format('YmdHisu').Str::random(4).'.jpg';
            $request->file('foto')->storeAs('foto', $file_foto, 'public');
        }else{
            if($edit){
                $file_foto = $data_item->foto;
            }
        }

        $data_item->kategori_id = $request->kategori;
        $data_item->nama = $request->nama;
        $data_item->harga_beli = $request->harga_beli;
        $data_item->laba = $request->laba;
        $data_item->kode = $kode;
        $data_item->supplier = $request->supplier;
        $data_item->jenis = $request->jenis;
        $data_item->foto = $file_foto;
        $data_item->save();


        return redirect('master-items');
    }

    public function delete($id)
    {
        MasterItem::find($id)->delete();
        return redirect('master-items');
    }

    public function updateRandomData()
    {
        $data = MasterItem::get();
        foreach($data as $item)
        {
            $kode = $item->id;
            $kode = str_pad($kode, 5, '0', STR_PAD_LEFT);

            $item->harga_beli = rand(100,1000000);
            $item->laba = rand(10,99);
            $item->kode = $kode;
            $item->supplier = $this->getRandomSupplier();
            $item->jenis = $this->getRandomJenis();
            $item->save();
        }
    }

    private function getRandomSupplier()
    {
        $array = ['Tokopaedi','Bukulapuk','TokoBagas','E Commurz','Blublu'];
        $random = rand(0,4);
        return $array[$random];
    }

    private function getRandomJenis()
    {
        $array = ['Obat','Alkes','Matkes','Umum','ATK'];
        $random = rand(0,4);
        return $array[$random];
    }
}

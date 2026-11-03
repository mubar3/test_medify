<?php

namespace App\Http\Controllers;

use App\Models\KategoriItem;
use App\Models\MasterItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class KategoriItemsController extends Controller
{
    public function index()
    {
        $kategori = KategoriItem::withCount('items')->orderBy('id', 'desc')->get();
        return view('master_items.kategori.index', compact('kategori'));
    }

    public function formView($method, $id = 0)
    {
        if ($method == 'new') {
            $item = new KategoriItem();
        } else {
            $item = KategoriItem::findOrFail($id);
        }
        $data['item'] = $item;
        $data['method'] = $method;
        return view('master_items.form_kategori.index', $data);
    }

    public function singleView($kode)
    {
        $data['data'] = MasterItem::where('kode', $kode)->first();
        return view('master_items.single.index', $data);
    }

    public function formSubmit(Request $request, $method, $id = 0)
    {
        if ($method == 'new') {
            $data_item = new KategoriItem;
            $count = KategoriItem::withTrashed()->count();
            $kode = str_pad($count + 1, 5, '0', STR_PAD_LEFT);
            while (KategoriItem::withTrashed()->where('kode', $kode)->exists()) {
                $count++;
                $kode = str_pad($count + 1, 5, '0', STR_PAD_LEFT);
            }
        } else {
            $data_item = KategoriItem::findOrFail($id);
            $kode = $data_item->kode;
        }

        $data_item->nama = $request->nama;
        $data_item->kode = $kode;
        $data_item->save();

        return redirect('kategori-items')->with('success', 'Kategori ' . ($method == 'new' ? 'berhasil dibuat' : 'berhasil diupdate') . '.');
    }

    public function delete($id)
    {
        $kategori = KategoriItem::findOrFail($id);
        $kategori->delete();
        return redirect('kategori-items')->with('success', 'Kategori berhasil dihapus.');
    }
}

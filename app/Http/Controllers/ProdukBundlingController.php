<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk_bundling;
use App\Models\Master_product;
use Illuminate\Support\Facades\Validator;

class ProdukBundlingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('admin.auth')->only(['store', 'update', 'destroy']);
    }
    public function index(Request $request)
    {
        // Menentukan jumlah item per halaman, defaultnya 10 jika tidak ada parameter 'per_page'
        $perPage = $request->get('per_page', 10);

        // Mengambil data produk dengan pagination
        $produk_bundlings = Produk_bundling::paginate($perPage);

        // Mengembalikan respon JSON dengan data produk yang telah dipaginate
        return response()->json([
            'data' => $produk_bundlings->items(), // Data item pada halaman saat ini
            'current_page' => $produk_bundlings->currentPage(), // Halaman saat ini
            'last_page' => $produk_bundlings->lastPage(), // Halaman terakhir
            'total' => $produk_bundlings->total(), // Total item
            'per_page' => $produk_bundlings->perPage(), // Item per halaman
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_bundle' => 'required',
            'harga_bundle' => 'required',
            'detail_bundle' => 'required',
            'foto_bundle' => 'required|file|mimes:jpg,jpeg,png',
            'pilih_produk' => 'required|array',
            'redirect' => 'required|array',
        ]);
 
        if ($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        };

        // Validasi setiap ID produk dalam 'pilih_produk'
        $produkIds = $request->input('pilih_produk');
        $invalidProdukIds = array_diff($produkIds, Master_product::pluck('id')->toArray());

        if (!empty($invalidProdukIds)) {
            return response()->json([
                'error' => 'Beberapa produk ID tidak valid.',
                'invalid_ids' => $invalidProdukIds
            ], 422);
        }
 
        $input = $request->all();

        if ($request->has('foto_bundle')) {
            $gambar = $request->file('foto_bundle');
            // Buat nama file dengan format waktu dan angka acak
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            // Simpan file ke dalam direktori 'public/images'
            $path = $gambar->storeAs('public/images', $nama_gambar);
            // Simpan nama file ke dalam input untuk disimpan di database
            $input['foto_bundle'] = $nama_gambar;
        }

        $input['redirect'] = $request->input('redirect');

        $produk_bundling = Produk_bundling::create($input);
 
        return response()->json([
            'data' => $produk_bundling
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Produk_bundling  $produk_bundling
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $produk_bundling = Produk_bundling::find($id);

        if (!$produk_bundling) {
            return response()->json([
                'message' => 'Produk bundling tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'data' => $produk_bundling
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Produk_bundling  $produk_bundling
     * @return \Illuminate\Http\Response
     */
    public function edit(Produk_bundling $produk_bundling)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Produk_bundling  $produk_bundling
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produk_bundling $produk_bundling)
    {
        $validator = Validator::make($request->all(), [
            'nama_bundle' => 'required',
            'harga_bundle' => 'required',
            'detail_bundle' => 'required',
            'foto_bundle' => 'nullable|file|mimes:jpg,jpeg,png',
            'pilih_produk' => 'required|array',
            'redirect' => 'required',
        ]);
   
        if ($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        // Validasi setiap ID produk dalam 'pilih_produk'
        $produkIds = $request->input('pilih_produk');
        $invalidProdukIds = array_diff($produkIds, Master_product::pluck('id')->toArray());

        if (!empty($invalidProdukIds)) {
            return response()->json([
                'error' => 'Beberapa produk ID tidak valid.',
                'invalid_ids' => $invalidProdukIds
            ], 422);
        }
   
        $input = $request->all();
   
        if ($request->has('foto_bundle')) {
            $gambar = $request->file('foto_bundle');
            // Buat nama file dengan format waktu dan angka acak
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            // Simpan file ke dalam direktori 'public/images'
            $path = $gambar->storeAs('public/images', $nama_gambar);
            // Simpan nama file ke dalam input untuk disimpan di database
            $input['foto_bundle'] = $nama_gambar;
        }
        
        if (isset($input['redirect'])) {
            // Konversi redirect menjadi JSON jika ada
            $input['redirect'] = $request->input('redirect');
        }

        $produk_bundling->update($input);
   
        return response()->json([
            'message' => 'success',
            'data' => $produk_bundling
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Produk_bundling  $produk_bundling
     * @return \Illuminate\Http\Response
     */
    public function destroy(Produk_bundling $produk_bundling)
    {
        $produk_bundling->delete();
 
        return response()->json([
            'message' => 'success'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Master_product;
use Illuminate\Support\Facades\Validator;

class MasterProductController extends Controller
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
        $master_products = Master_product::paginate($perPage);

        // Mengembalikan respon JSON dengan data produk yang telah dipaginate
        return response()->json([
            'data' => $master_products->items(), // Data item pada halaman saat ini
            'current_page' => $master_products->currentPage(), // Halaman saat ini
            'last_page' => $master_products->lastPage(), // Halaman terakhir
            'total' => $master_products->total(), // Total item
            'per_page' => $master_products->perPage(), // Item per halaman
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
            'nama_produk' => 'required',
            'harga_produk' => 'required',
            'detail_produk' => 'required',
            'foto_produk' => 'required|file|mimes:jpg,jpeg,png',
            'bahan_produk' => 'required',
            'cara_pemakaian' => 'required',
            'kategori' => 'required|exists:kategoris,id',
            'redirect' => 'required|array' // Validasi sebagai array
        ]);
        
        if ($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        
        if ($request->has('foto_produk')) {
            $gambar = $request->file('foto_produk');
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            $path = $gambar->storeAs('public/images', $nama_gambar);
            $input['foto_produk'] = $nama_gambar;
        }

        // Konversi redirect menjadi JSON
        $input['redirect'] = $request->input('redirect');
        
        $master_product = Master_product::create($input);
        
        return response()->json([
            'data' => $master_product
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Master_product  $master_product
     * @return \Illuminate\Http\Response
     */
    public function show(Master_product $master_product)
    {
        return response()->json([
            'data' => $master_product
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Master_product  $master_product
     * @return \Illuminate\Http\Response
     */
    public function edit(Master_product $master_product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Master_product  $master_product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Master_product $master_product)
    {
        $validator = Validator::make($request->all(), [
            'nama_produk' => 'required',
            'harga_produk' => 'required',
            'detail_produk' => 'required',
            'foto_produk' => 'nullable|file|mimes:jpg,jpeg,png',
            'bahan_produk' => 'required',
            'cara_pemakaian' => 'required',
            'kategori' => 'required|exists:kategoris,id',
            'redirect' => 'nullable|array'
        ]);
        
        if ($validator->fails()){
            return response()->json(
                $validator->errors(),
                422
            );
        }

        $input = $request->all();
        
        if ($request->has('foto_produk')) {
            $gambar = $request->file('foto_produk');
            $nama_gambar = time() . rand(1,9) . '.' . $gambar->getClientOriginalExtension();
            $path = $gambar->storeAs('public/images', $nama_gambar);
            $input['foto_produk'] = $nama_gambar;
        }

        if (isset($input['redirect'])) {
            // Konversi redirect menjadi JSON jika ada
            $input['redirect'] = $request->input('redirect');
        }
        
        $master_product->update($input);
        
        return response()->json([
            'message' => 'success',
            'data' => $master_product
        ]);
    }


    public function destroy(Master_product $master_product)
    {
        $master_product->delete();
 
        return response()->json([
            'message' => 'success'
        ]);
    }
}

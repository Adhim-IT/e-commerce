<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class UserLandingController extends Controller
{


    public function index(Request $request)
    {
        $limit = 8; // Batas produk per halaman
        $offset = $request->input('offset', 0);

        $products = Product::query();

        // Filter berdasarkan nama produk
        if ($search = $request->input('search')) {
            $products->where('name', 'like', "%{$search}%");
        }

        // Filter berdasarkan kategori
        if ($category = $request->input('category')) {
            $products->where('category_id', $category);
        }

        // Sorting
        if ($sort = $request->input('sort')) {
            if ($sort === 'price_low') {
                $products->orderBy('price', 'asc');
            } elseif ($sort === 'price_high') {
                $products->orderBy('price', 'desc');
            } elseif ($sort === 'latest') {
                $products->orderBy('created_at', 'desc');
            }
        }

        // Terapkan limit dan offset
        $products = $products->skip($offset)->take($limit)->get();

        // Jika permintaan AJAX, kembalikan partial view produk
        if ($request->ajax()) {
            return view('landing.products', compact('products'))->render();
        }

        // Permintaan biasa
        $categories = Category::all();
        return view('landing.landing-page', compact('products', 'categories', 'limit'));
    }
}

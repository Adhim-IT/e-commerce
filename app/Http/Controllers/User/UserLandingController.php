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
        // Mengambil semua kategori
        $categories = Category::all();
    
        // Query untuk produk
        $query = Product::query()
            ->active()
            ->inStock();
    
        // Filter pencarian
        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%");
        }
    
        // Filter berdasarkan kategori
        if ($request->category) {
            $query->where('category_id', $request->category);
        }
    
        // Filter berdasarkan pengurutan
        if ($request->sort) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->latest();
                    break;
                default:
                    $query->oldest();
                    break;
            }
        } else {
            $query->latest();
        }
    
        // Mengambil data produk dengan pagination
        $products = $query->paginate(10);
    
        // Menyimpan parameter pencarian untuk pagination
        $products->appends($request->all());
    
        // Mengirim data ke view
        return view('landing.landing-page', [
            'products' => $products,
            'categories' => $categories,
            'search' => $request->search,
            'currentCategory' => $request->category,
            'currentSort' => $request->sort,
        ]);
    }
    
}

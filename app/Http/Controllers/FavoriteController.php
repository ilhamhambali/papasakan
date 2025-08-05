<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category; // <-- [BARU] Tambahkan ini
use Illuminate\Http\Request;
use Inertia\Inertia;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        // [BARU] Validasi input filter (opsional tapi bagus untuk keamanan)
        $request->validate([
            'category' => 'nullable|string|exists:categories,slug',
        ]);

        // [BARU] Ambil query untuk resep favorit
        $favoritesQuery = $request->user()->favoriteRecipes()->with('user', 'categories');

        // [BARU] Terapkan filter jika ada
        if ($request->filled('category')) {
            $favoritesQuery->whereHas('categories', function ($query) use ($request) {
                $query->where('slug', $request->category);
            });
        }

        // Eksekusi query dengan paginasi
        $favorites = $favoritesQuery->latest()->paginate(12)->withQueryString();

        return Inertia::render('Favorites/Index', [
            'recipes' => $favorites,
            'categories' => Category::all(), // Kirim semua kategori untuk dropdown
            'filters' => $request->only(['category']), // Kirim filter aktif ke view
        ]);
    }

    public function toggle(Request $request, Recipe $recipe)
    {
        $request->user()->favoriteRecipes()->toggle($recipe->id);
        return back();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use Illuminate\Support\Str;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class RecipeController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $recipes = Recipe::with('user', 'categories')
            ->latest()
            ->paginate(12);

        return Inertia::render('Home', [
            'recipes' => $recipes,
        ]);
    }

    public function show(Recipe $recipe)
    {
        $recipe->load('user', 'categories');

        // [BARU] Cek status favorit
        $isFavorited = auth()->check() ? auth()->user()->favoriteRecipes()->where('recipe_id', $recipe->id)->exists() : false;

        return Inertia::render('Recipes/Show', [
            'recipe' => $recipe,
            'isFavorited' => $isFavorited, // Kirim status ke frontend
        ]);
    }

    public function myRecipes(Request $request)
    {
        $myRecipes = $request->user()->recipes() // Ambil resep dari user yang login
            ->with('user', 'categories') // Eager load relasi user dan kategori
            ->latest()
            ->paginate(12);

        return Inertia::render('Recipes/MyRecipes', [
            'recipes' => $myRecipes,
        ]);
    }

    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Recipes/Create', [
            'categories' => $categories,
        ]);
    }

    public function store(StoreRecipeRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe = $request->user()->recipes()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
            'image' => $imagePath, // [DIUBAH] Simpan path gambar
        ]);

        $recipe->categories()->attach($request->categories);

        return redirect()->route('home')->with('success', 'Resep berhasil dibuat!');
    }


    public function edit(Recipe $recipe)
    {
        // Gunakan policy untuk otorisasi
        $this->authorize('update', $recipe);

        $categories = Category::all();
        $recipe->load('categories');

        return Inertia::render('Recipes/Edit', [
            'recipe' => $recipe,
            'categories' => $categories,
        ]);
    }

    public function update(UpdateRecipeRequest $request, Recipe $recipe)
    {
        $imagePath = $recipe->image;
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($recipe->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($recipe->image);
            }
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
            'image' => $imagePath, // [DIUBAH] Simpan path gambar baru
        ]);

        $recipe->categories()->sync($request->categories);

        return redirect()->route('recipes.show', $recipe->slug)->with('success', 'Resep berhasil diperbarui!');
    }
    public function destroy(Recipe $recipe)
    {
        // Gunakan policy untuk otorisasi
        $this->authorize('delete', $recipe);

        $recipe->delete();

        return redirect()->route('home')->with('success', 'Resep berhasil dihapus!');
    }
}

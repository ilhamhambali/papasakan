<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Requests\StoreArticleRequest;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateArticleRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Inertia\Inertia;

class ArticleController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        // Ambil semua artikel yang statusnya 'published'
        // Eager load relasi 'user' untuk menampilkan nama penulis
        $articles = Article::with('user')
            ->where('status', 'published')
            ->latest()
            ->paginate(10); // 10 artikel per halaman

        return Inertia::render('Articles/Index', [
            'articles' => $articles,
        ]);
    }

    public function show(Article $article)
    {
        // Pastikan hanya artikel yang sudah 'published' yang bisa diakses
        if ($article->status !== 'published' && (!auth()->check() || auth()->id() !== $article->user_id)) {
            abort(404);
        }

        $article->load('user');

        return Inertia::render('Articles/Show', [
            'article' => $article,
        ]);
    }
    public function myArticles(Request $request)
    {
        $myArticles = $request->user()->articles()
            ->with('user') // Eager load relasi user
            ->latest()
            ->paginate(12);

        // Arahkan ke view yang benar: Articles/MyArticles
        return Inertia::render('Articles/MyArticles', [
            'articles' => $myArticles,
        ]);
    }

    public function create()
    {
        $this->authorize('create', Article::class);

        return Inertia::render('Articles/Create');
    }

    public function store(StoreArticleRequest $request)
    {
        // Otorisasi sudah ditangani oleh StoreArticleRequest
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        $request->user()->articles()->create([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'body' => $request->body,
            'image' => $imagePath,
            'status' => 'published', // Atau 'draft' sesuai kebutuhan Anda
        ]);

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dibuat!');
    }


    public function edit(Article $article)
    {
        // Gunakan policy untuk otorisasi
        $this->authorize('update', $article);

        return Inertia::render('Articles/Edit', [
            'article' => $article
        ]);
    }

    public function update(UpdateArticleRequest $request, Article $article)
    {
        $imagePath = $article->image;
        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::disk('public')->delete($article->image);
            }
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        $article->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title) . '-' . uniqid(),
            'body' => $request->body,
            'image' => $imagePath, // [DIUBAH] Simpan path gambar baru
        ]);


        return redirect()->route('articles.show', $article->slug)->with('success', 'Artikel berhasil diperbarui!');
    }
    public function destroy(Article $article)
    {
        // Gunakan policy untuk otorisasi
        $this->authorize('delete', $article);

        $article->delete();

        return redirect()->route('articles.index')->with('success', 'Artikel berhasil dihapus!');
    }
}

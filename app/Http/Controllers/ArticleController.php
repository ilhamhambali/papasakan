<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
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
        if ($article->status !== 'published') {
            abort(404);
        }

        $article->load('user');

        return Inertia::render('Articles/Show', [
            'article' => $article,
        ]);
    }
}

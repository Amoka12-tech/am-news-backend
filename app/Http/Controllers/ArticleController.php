<?php

namespace App\Http\Controllers;

use App\Models\Preference;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'keyword' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'category' => 'nullable|string',
            'source' => 'nullable|string',
        ]);

        $query = Article::query();
        $user = $request->user();
        $preferences = null;
        if ($user) {
            $preferences = Preference::where('user_id', '=', $user->id)->first();
        }

        if ($request->filled('start_date')) {
            $start_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $query->whereDate('published_at', '>=', $start_date);
        }

        if ($request->filled('start_date')) {
            $end_date = Carbon::parse($request->start_date)->format('Y-m-d H:i:s');
            $query->whereDate('published_at', '<=', $end_date);
        }

        if ($request->filled('category')) {
            $query->where('category', "=",$request->category);
        } elseif ($preferences && $preferences->categories) {
            $query->whereIn('category', $preferences->categories);
        }

        if ($request->filled('source')) {
            $query->where('source', "=", $request->source);
        } elseif ($preferences && $preferences->sources) {
            $query->whereIn('source', $preferences->sources);
        }

        if ($preferences && $preferences->authors) {
            $query->whereIn('author', $preferences->authors);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }
        $query->orderBy('published_at', 'desc');

        $articles = $query->paginate(10);

        return response()->json($articles);
    }


    public function personalizedFeed(Request $request)
    {
        $user = $request->user();
        $query = Article::query();

        if (!$user || !$user->preference) {
            $articles = $query->paginate(10);

            return response()->json($articles);
        }

        $preferences = $user->preference;

        if ($preferences->categories) {
            $query->whereIn('category', $preferences->categories);
        }

        if ($preferences->sources) {
            $query->whereIn('source', $preferences->sources);
        }

        $articles = $query->paginate(10);

        return response()->json($articles);
    }

    public function getCategoriesAndSources(Request $request)
    {
        $categories = DB::table('articles')->distinct()->pluck('category');
        $sources = DB::table('articles')->distinct()->pluck('source');
        $authors = DB::table('articles')->distinct()->pluck('author');

        return response()->json([
            'categories' => $categories,
            'sources' => $sources,
            'authors' => $authors,
        ]);
    }


}


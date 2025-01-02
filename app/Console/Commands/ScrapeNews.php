<?php
namespace App\Console\Commands;

use DB;
use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Date;

class ScrapeNews extends Command {
    protected $signature = 'news:scrape';
    protected $description = 'Scrape news from APIs';
    public function handle() {
        $client = new Client();
        $sources = [
            [
                'name' => 'newsapi',
                'url' => 'https://newsapi.org/v2/top-headlines?language=en&apiKey='. config('app.newsapi.key'),
                'headers' => [
                    'Authorization' => 'Bearer ' . config('app.newsapi.key'),
                ],
            ],
            [
                'name' => 'guardian',
                'url' => 'https://content.guardianapis.com/search?api-key='. config('app.guardianapi.key'),
                'headers' => [
                    'Authorization' => 'Bearer ' . config('app.guardianapi.key'),
                ],
            ],
            [
                'name' => 'nyt',
                'url' => 'https://api.nytimes.com/svc/topstories/v2/home.json?api-key=' . config('app.nytimeapi.key'),
                'headers' => [],
            ],
        ];
        foreach ($sources as $source) {
            try {
                $response = $client->get($source['url'], [
                    'headers' => $source['headers'],
                ]);

                $articles = json_decode($response->getBody(), true);
                Log::info("{$source['name']} Articles: ", [$articles]);

                if (isset($articles['articles'])) {
                    $this->saveArticles($articles['articles'], $source['name']);
                } elseif (isset($articles['response']['results'])) {
                    $this->saveArticles($articles['response']['results'], $source['name']);
                } elseif (isset($articles['results'])) {
                    $this->saveArticles($articles['results'], $source['name']);
                } else {
                    $this->warn("No articles found for source: {$source['name']}");
                }
            } catch (\Exception $e) {
                // Log::info("Error: ", [$e->getResponse()->getBody()->getContents()]);
                $this->error("Failed to fetch news from {$source['name']}: " . $e->getMessage());
            }
        }
    }

    private function saveArticles(array $articles, string $source)
    {
        foreach ($articles as $article) {
            $publishedAt = Carbon::parse(
                $article['publishedAt'] ?? $article['published_date'] ?? $article['webPublicationDate'] ?? now()
            )->format('Y-m-d H:i:s');

            DB::table('articles')->updateOrInsert(
                ['title' => $article['title'] ?? $article['webTitle']],
                [
                    'source' => $source,
                    'title' => $article['title'] ?? $article['webTitle'],
                    'author' => $article['author'] ?? $article['byline'] ?? null,
                    'description' => $article['description'] ?? $article['abstract'] ?? "",
                    'category' => $article['section'] ?? $article['sectionId'] ?? 'others',
                    'content' => $article['content'] ?? null,
                    'url' => $article['url'] ?? $article['webUrl'],
                    'published_at' => $publishedAt,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->info("Saved articles from source: {$source}");
    }
}

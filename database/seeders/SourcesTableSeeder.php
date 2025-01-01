<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class SourcesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sources')->insert([
            ['name' => 'News API', 'api_identifier' => 'newsapi', 'description' => 'This API provides access to news articles from thousands of sources, including news publications, blogs, and magazines.', 'url' => 'https://newsapi.org/v2'],
            ['name' => 'The Guardian', 'api_identifier' => 'guardian', 'description' => 'This API allows developers to access articles from The Guardian newspaper, one of the most respected news sources in the world.', 'url' => 'https://content.guardianapis.com/search'],
            ['name' => 'New York Times', 'api_identifier' => 'nyt', 'description' => 'The New York Times', 'url' => 'https://api.nytimes.com/svc/topstories/v2/'],
        ]);
    }
}

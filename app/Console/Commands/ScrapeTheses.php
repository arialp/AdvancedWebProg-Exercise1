<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Classes\GraduateThesis;

class ScrapeTheses extends Command
{
    protected $signature = 'scrape:theses';

    protected $description = 'Scrape graduate theses from stup.ferit.hr and update local database';

    public function handle()
    {
        $this->info("Starting the parsing script for stup.ferit.hr...");
		

        if (!defined('MAX_FILE_SIZE')) {
            define('MAX_FILE_SIZE', 50000000);
        }


        if (!function_exists('str_get_html') && file_exists(app_path('Helpers/simple_html_dom.php'))) {
            require_once app_path('Helpers/simple_html_dom.php');
        }

        for ($number = 2; $number <= 6; $number++) {
            $url = "https://stup.ferit.hr/zavrsni-radovi/page/{$number}/";
            $this->line("Connecting to {$url} ...");


            $response = Http::withoutVerifying()->withUserAgent('Mozilla/5.0')->get($url);

            if ($response->successful()) {
                $html = str_get_html($response->body());
                
                if ($html) {
                    $articles = $html->find('article');
                    $this->info("Found " . count($articles) . " articles on page $number.");
                    
                    foreach ($articles as $article) {
                        $thesis = new GraduateThesis();
                        

                        $thesis->create($article->innertext);
                        
                        if (!empty($thesis->work_name) && !empty($thesis->work_link)) {
                            $thesis->save();
                            $this->line("> Saved: " . $thesis->work_name);
                        }
                    }
                } else {
                    $this->error("Failed to parse HTML structure on page $number.");
                }
            } else {
                $this->error("Failed to retrieve page $number (HTTP Code: {$response->status()}).");
            }
        }

        $this->info("Done parsing and saving theses.");
    }
}

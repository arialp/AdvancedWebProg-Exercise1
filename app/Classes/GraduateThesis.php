<?php

namespace App\Classes;

use App\Contracts\iRadio;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class GraduateThesis implements iRadio
{
    public $work_name;
    public $work_text;
    public $work_link;
    public $identification_number;

    public function create($data)
    {
        if (is_array($data)) {
            $this->work_name = $data['work_name'] ?? '';
            $this->work_text = $data['work_text'] ?? '';
            $this->work_link = $data['work_link'] ?? '';
            $this->identification_number = $data['identification_number'] ?? '';
        } elseif (is_string($data)) {

            if (!function_exists('str_get_html') && file_exists(app_path('Helpers/simple_html_dom.php'))) {
                require_once app_path('Helpers/simple_html_dom.php');
            }

            if (function_exists('str_get_html')) {
                $html = str_get_html($data);
                if ($html) {
                    $h2 = $html->find('h2 a', 0);
                    $this->work_name = $h2 ? trim($h2->plaintext) : '';
                    $this->work_link = $h2 ? $h2->href : '';
                    
                    $this->identification_number = '';
                    foreach($html->find('img') as $img) {
                        if (preg_match('/\/(\d+)\.(png|jpg|jpeg|gif)/i', $img->src, $matches)) {
                            $this->identification_number = $matches[1];
                            break;
                        }
                    }
                    
                    $this->work_text = '';
                    if (!empty($this->work_link)) {

                        $response = Http::withoutVerifying()->get($this->work_link);
                        if ($response->successful()) {
                            $inner_html = str_get_html($response->body());
                            if ($inner_html) {
                                $content = $inner_html->find('.post-content', 0);
                                $this->work_text = $content ? trim(strip_tags($content->innertext)) : '';
                            }
                        }
                    }

                    if (empty($this->work_text)) {
                        $content = $html->find('.post-content', 0);
                        $this->work_text = $content ? trim(strip_tags($content->innertext)) : '';
                    }
                }
            }
        }
    }

    public function save()
    {
        DB::table('graduate_theses')->insert([
            'work_name' => $this->work_name,
            'work_text' => $this->work_text,
            'work_link' => $this->work_link,
            'identification_number' => $this->identification_number,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function read()
    {

        return DB::table('graduate_theses')->get()->toArray();
    }
}

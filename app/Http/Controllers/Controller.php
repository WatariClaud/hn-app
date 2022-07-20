<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class Controller extends BaseController
{
    public function first25()
    {
        try {
            $url = 'https://hacker-news.firebaseio.com/v0/';
            $connected = @fsockopen('www.google.com', 80);
            if ($connected) {
                $data = Http::get($url . 'newstories.json')->collect();
                $responses = Http::pool(function (Pool $pool) use ($url, $data) {
                    return $data->map(fn ($value) => $pool->get($url . 'item/' . $value . '.json'));
                });
                $responses = array_filter($responses);
                $responses = array_slice($responses, 0, 25);
                $all_words = [];
                foreach($responses as $response) {
                    if($response->object()->type === 'story') {
                        array_push($all_words, $response->object()->title);
                    }
                }

                // breaks sometimes, presumably due to malformed characters
                $result = array_count_values(str_word_count(implode(" ", array_map('strtolower', $all_words)), 1));
                arsort($result);
                $most_words = array_slice($result, 0, 10);
                return response()->json(array(
                    'ten_most_occuring_words_in_25_latest_titles' => $most_words,
                    '25_latest_titles' => $all_words,
                ));
            } else {
                return response()->json(array(
                    'network_error' => 'Check your connection',
                ));
            }
        } catch (Exception $e) {
            echo 'Exception: ',  $e->getMessage(), "\n";
        }  
    }

    public function last_week()
    {
        try {
            $url = 'https://hacker-news.firebaseio.com/v0/';
            $connected = @fsockopen('www.google.com', 80);
            if ($connected) {
                $data = Http::get($url . 'newstories.json')->collect();
                $responses = Http::pool(function (Pool $pool) use ($url, $data) {
                    return $data->map(fn ($value) => $pool->get($url . 'item/' . $value . '.json'));
                });
                $responses = array_filter($responses);
                $all_content = [];
                $all_words = [];
                foreach($responses as $response) {
                    if($response->object()->type === 'story') {
                        array_push($all_content, $response->object());
                    }
                }
                $responses = array_filter($all_content, function($value) {
                    $previous_week = strtotime("-1 week +1 day");
                    $start_week = strtotime("last sunday midnight", $previous_week);
                    $end_week = strtotime("next saturday", $start_week);
                    $all_content_ = ($value->time >= $start_week);
                    return $all_content_;
                    
                });
                foreach($responses as $response) {
                    if($response->type === 'story') {
                        array_push($all_words, $response->title);
                    }
                }

                // breaks sometimes, presumably due to malformed characters
                $result = array_count_values(str_word_count(implode(" ", $all_words), 1));
                arsort($result);
                $most_words = array_slice($result, 0, 10);
                return response()->json(array(
                    'ten_most_occuring_words_in_last_week_titles' => $most_words,
                    'last_week_titles' => $responses,
                ));
            } else {
                return response()->json(array(
                    'network_error' => 'Check your connection',
                ));
            }
        } catch (Exception $e) {
            echo 'Exception: ',  $e->getMessage(), "\n";
        }        
    }

    public function last600()
    {
        try {
            $url = 'https://hacker-news.firebaseio.com/v0/';
            $connected = @fsockopen('www.google.com', 80);
            if ($connected) {
                return response()->json(array(
                    'message' => 'under construction',
                    'building' => true,
                ));
            } else {
                return response()->json(array(
                    'network_error' => 'Check your connection',
                ));
            }
        } catch (Exception $e) {
            echo 'Exception: ',  $e->getMessage(), "\n";
        }        
    }
}

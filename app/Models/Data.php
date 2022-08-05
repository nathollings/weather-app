<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class Data
{

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function Data()
    {
        return $this->data;
    }


    /**
     * Retrieve the data from the online source found in .env
     *
     * @return Collection<Data>
     */
    public static function getData()
    {

        $file = Http::get(config('weather.source'));

        $contents = $file->body();

        // split by new line... trim to remove tricky whitespace.
        $rows = explode("\n", trim($contents));

        $data = collect();


        $headers = [];

        foreach ($rows as $index => $row) {
            $row_split = preg_split('/\t+/', $row);
            if($index == 0) {
                $headers = self::parseHeaders($row_split);
                continue;
            }

            if($row[0] == '>') {
                break;
            }

            // actual data here.

            $line = [];
            foreach ($row_split as $item_index => $item) {
                if (isset($headers[$item_index]))
                    $line[$headers[$item_index]] = trim($item);
            }
            $line = collect($line);

            $data->push($line);
        }

        return new Data($data);
    }

    public static function parseHeaders($row)
    {
        foreach ($row as $header) {

            $header = explode('[', $header)[0]; // don't judge me.
            $headers[] = trim(strtolower($header));
        }

        return $headers;
    }
}

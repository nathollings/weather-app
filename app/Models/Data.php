<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class Data
{

    protected $data;

    protected $options;

    public function __construct($data, $options)
    {
        $this->data = $data;
        $this->options = $options;
    }

    public function Data()
    {
        return $this->data;
    }

    public function Options()
    {
        return $this->options;
    }


    /**
     * Retrieve the data from the online source found in .env
     *
     * @return Collection<Data>
     */
    public static function getData()
    {

        $file = Http::get(config('weather.source'));

        // trigged when we get to end of file.
        $optionsValues = false;

        $contents = $file->body();

        // split by new line... trim to remove tricky whitespace.
        $rows = explode("\n", trim($contents));

        $data = collect();


        $headers = [];
        $options = collect([]);

        foreach ($rows as $index => $row) {
            $row_split = preg_split('/\t+/', $row);
            if ($index == 0) {
                $headers = self::parseHeaders($row_split);
                continue;
            }

            if (isset($row[0]) && $row[0] == '>') {
                $optionsValues = true;
                continue;
            }

            // actual data here.

            $line = [];

            if ($optionsValues) {
                $line = self::parseOptions($row);
                if(!!$line) {
                    $options->push($line);
                }
                continue;
            }


            foreach ($row_split as $item_index => $item) {
                if (isset($headers[$item_index]))

                    if ($headers[$item_index]['header'] == 'time') {

                        $line[$headers[$item_index]['header']] = \Carbon\Carbon::parse(trim($item));
                    } else {

                        $line[$headers[$item_index]['header']] = [
                            'value' => trim($item),
                            'unit' => $headers[$item_index]['unit']
                        ];
                    }
            }
            $line = collect($line);

            $data->push($line);
        }

        return new Data($data, $options);
    }

    /**
     * parse data headers
     *
     * @param [array] $row
     * @return array string[]
     */
    public static function parseHeaders($row)
    {
        foreach ($row as $header) {

            $header = explode('[', $header); // don't judge me.
            $headers[] = [
                'header' => trim(strtolower($header[0])),
                'unit' => str_replace(']', '', trim(strtolower($header[1]))),
            ];
        }

        return $headers;
    }

    /**
     * Parse Options
     *
     * @param [array] $row
     * @return array string[]
     */
    public static function parseOptions($row)
    {
        $option = null;
        $item = explode(':', $row);
        if(count($item) == 2) {
            $option = [
                'code' => trim(strtolower($item[0])),
                'option' => trim(strtolower($item[1])),
            ];
        }
        return $option;
    }
}

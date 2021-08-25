<?php
namespace ChartBoy;

function linkStyle(string $location)
{
    // This function soley links to a charts.css file,
    // whether it be from a pre-done CDN, or a local file.
    // 'shorthands' for jsdelivr and unpkg are included
    // for simple deployment if that's what you would do anyways.
    $link;
    switch ($location) {
        case "jsdelivr":
            $link = "https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css";
            break;
        case "unpkg":
            $link = "https://unpkg.com/charts.css/dist/charts.min.css";
            break;
        default:
            $link = $location;
    }
    return "<link rel='stylesheet' href='{$link}'>";
}

class ChartBoy
{
    /* Variables */
    public $type;
    // Types: bar, column, area, line
    // TODO: radial, pie, radar, polar
    public $data; // Array of data in the ["label" => numericValue] where "label" is optional.
    public $caption; // The 'caption' of the chart. Optional.
    private $min;
    private $max; // The min/max of the chart is calculated automatically.

    // charts.css variables
    protected $hideData;

    public function __construct(array $data, string $type = "bar", $caption = null)
    {
        $this->data = $data;
        $this->type = $type;
        $this->caption = $caption;

        $this->min = min($data);
        $this->max = max($data);

        // charts.css
        $this->hideData = false;
    }

    // Logic

    private function makeScale($n)
    {
        // Calculate a percentage on a scale of 100.
        // This is to hide exact values from the public,
        // in case such information is sensitive.
        return round(($n / $this->max), 3);
    }

    /* Setters */

    public function setCaption($caption)
    {$this->caption = $caption;}

    public function setType($type)
    {$this->type = $type;}

    // charts.css
    public function hideData($bool = true)
    {$this->hideData = $bool;}

    public function inputData($array)
    {
        // Takes in a new input data array and updates calculations regarding it.
        $this->data = $array;
        $this->min = min($array);
        $this->max = max($array);
    }

    // Getters

    public function renderChart($startPoint = false)
    {
        print("\n\t<table class='charts-css {$this->type}'>");
        if (!empty($this->caption)) {print("\n\t<caption>{$this->caption}</caption>");}
        print("\n\t<tbody>\n");
        foreach ($this->data as $item => $value) {
            // Loop through each data item

            $current = $this->makeScale($value);
            $next = $this->makeScale(next($this->data));

            // Does this chart require a starting point?
            if ($startPoint) {
                $start = "--start:{$current};";
                $size = "--size:{$next}";
                if ($item === array_key_last($this->data)) {break;}
            } else {
                $start = null;
                $size = "--size:{$current}";
            }

            print("\t\t<tr><td style='{$start}{$size}'><span class='data'>{$item}</span></td></tr>\n");
        }
        print("\t</tbody>\n\t");
        print("\t</table>\n\t");
    }
}

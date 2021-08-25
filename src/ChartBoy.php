<?php
namespace ChartBoy;

function linkStyle (string $location)
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
    // Variables
    public $type;
    // Types: bar, column, area, line
    // TODO: radial, pie, radar, polar
    public $data;    // Array of data in the ["label" => numericValue] where "label" is optional.
    public $caption; // The 'caption' of the chart. Optional.
    private $min;
    private $max;    // The min/max of the chart is calculated automatically.

    public function __construct(array $data, string $type = "bar", $caption = null)
    {
		$this->data = $data;
        $this->type = $type;
		$this->caption = $caption;

		$this->min = min($data);
        $this->max = max($data);
    }

    // Setters

    public function setCaption($caption)
    {$this->caption = $caption;}

    public function setType($type)
    {$this->type = $type;}

    public function inputData($array)
    {
		// Takes in a new input data array and updates calculations regarding it.
        $this->data = $array;
        $this->min = min($array);
        $this->max = max($array);
    }

    // Getters

    public function renderChart($noStyle = false)
    {
        print("<table class='charts-css {$this->type}'>");
        foreach ($this->data as $item => $value) {
			// Loop through each data item

			// Calculate a percentage on a scale of 100.
			// This is to hide exact values from the public,
			// in case such information is sensitive.
            $percent = round(($value / $this->max) * 100, 2);
			$style = "--size:calc({$percent}/100)";

            print("<td style='{$style}'>{$item}</td>");
        }
        print("</table>");
    }
}

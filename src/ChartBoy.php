<?php
namespace ChartBoy;

function linkStyle (string $location)
{
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
    public $data;
    public $caption;
    private $min;
    private $max;

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
        $this->data = $array; // $item -> $value
        $this->min = min($array);
        $this->max = max($array);
    }

    // Getters

    public function renderChart($noStyle = false)
    {
        print("<table class='charts-css {$this->type}'>");
        foreach ($this->data as $item => $value) {
            $percent = round(($value / $this->max) * 100, 2);
			$style = "--size:calc({$percent}/100)";
            print("<td style='{$style}'>{$item}</td>");
        }
        print("</table>");
    }
}

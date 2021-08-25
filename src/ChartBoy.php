<?php
namespace ChartBoy;

/*
	ChartBoy 0.9.0 for charts.css 0.9.0

	wolfgang-degroot/chartboy
	by Wolfgang de Groot
*/

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
		// TO CONSIDER: 'npm' and 'yarn' presets for installations
        default:
            $link = $location;
    }
    return "<link rel='stylesheet' href='{$link}'>";
}

class ChartBoy
{
    /* Variables */
    public $type; // Valid types: bar, column, area, line. Future: radial, pie, radar, polar
    public $data; // Array of data in the ["label" => numericValue] where "label" is optional.
    public $caption; // The 'caption' of the chart. Optional.
    public $primaryAxis;
    public $dataAxis;
    private $min;
    private $max; // The min/max of the chart is calculated automatically.
    private $startPoint;
	private $colors; // Array of colors per-element.
    protected $setting;

    public function __construct(array $data, string $type = "bar", string $primaryAxis = null, string $dataAxis = null, $caption = null)
    {
        $this->inputData($data);
        $this->setType($type);
        $this->setCaption($caption);
        $this->setAxis($primaryAxis, $dataAxis);
    }

    /* Logic */

    private function makeScale($n)
    {
        // Calculate a percentage on a scale of 100.
        // This is to hide exact values from the public,
        // in case such information is sensitive.
        return round(($n / $this->max), 3);
    }

    private function compileSettings()
    {
        $html;
        foreach ($this->setting as $option => $value) {
            if (!$value) {continue;}
            // Replace * with proper number
            if (strpos($option, "*")) {
                $int = (is_numeric($value)) ? $value : 1;
                $option = str_replace("*", $int, $option);
            }
            $html .= " " . $option;
        }
        return $html;
    }

    /* Setters */

    public function setType($type)
    {
        $this->type = $type;
        // Special case handling
        switch ($type) {
            case "bar":
            case "column":
                $this->startPoint = false;
                break;
            case "area":
            case "line":
                $this->startPoint = true;
				// Below settings are not supported with the above data types.
				// See https://chartscss.org/development/supported-features/#classes
                $this->setting["datasets-spacing-n"] = false;
                $this->setting["reverse-datasets"] = false;
                $this->setting["stacked"] = false;
                break;
        }
    }

    public function setCaption($caption, $display = true)
    {
        if (!isset($caption)) {return;}
        $this->caption = $caption;
        // If someone sets the caption, assume they want it to be seen,
		// unless 'false' is specified as the second argument.
        $this->setting["show-heading"] = $display;
    }

    public function setAxis($primary = null, $data = null)
    {
        // Takes in a new input data array and updates calculations regarding it.
        $this->primaryAxis = $primary;
        $this->dataAxis = $data;
    }

    public function setColor($element, $color = null)
    {
        // Set a unique color for an individual element.
		// If no color is given, it will clear its color.
		if ($color === null) {
			unset($this->colors[$element]);
		} else {
			$this->colors[$element] = $color;
		}
    }

    public function changeSetting($key, $value = true)
    {
		// Change a current setting, defaulting to making it 'true'
		// if they do not specify what, as all settings are 'false' by default.
		$this->setting[$key] = $value;
	}

    public function inputData($array)
    {
        // Takes in a new input data array and updates calculations regarding it.
		// TODO: 'Multiple dataset' support
        $this->data = $array;
        $this->min = min($array);
        $this->max = max($array);
    }

    /* Getters */

    public function renderChart($idset = null)
    {
        $id = (isset($idset)) ? " id='$idset'" : null;
        $settings = $this->compileSettings();

        /* Begin Render */
        print("\n\t<table class='charts-css {$this->type}{$settings}'{$id}>");
        if (!empty($this->caption)) {print("\n\t<caption>{$this->caption}</caption>");}
        print("\n\t<tbody>\n");
        foreach ($this->data as $item => $value) {
            // Loop through each data item
            $current = $this->makeScale($value);
            $next = $this->makeScale(next($this->data));

			// Get key's color, if set.
			$color = (isset($this->colors[$item])) ? "--color:".$this->colors[$item] : null;

            // Does this chart require a starting point?
            if ($this->startPoint === true) {
                $start = "--start:{$current};";
                $size = "--size:{$next};";
                if ($item === array_key_last($this->data)) {break;}
            } else {
                $start = null;
                $size = "--size:{$current};";
            }

            print("\t\t<tr><td style='{$start}{$size}{$color}'><span class='data'>{$item}</span></td></tr>\n");
        }
        print("\t</tbody>\n\t");
        print("\t</table>\n\t");
        if (isset($this->primaryAxis)) {print("<div class='primary-axis'>{$this->primaryAxis}</div>\n");}
        if (isset($this->dataAxis)) {print("<div class='data-axis'>{$this->dataAxis}</div>\n");}
    }
}

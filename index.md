# ChartBoy DocumentationBoy

## Installation

TODO

### Linking to charts.css easily via ChartBoy
charts.css can be linked to via the function `ChartBoy\linkStyle(location);`, where 'location' is a string.
 - There are two 'shorthand' presets:
   - `jsdelivr` = https://cdn.jsdelivr.net/npm/charts.css/dist/charts.min.css
   - unpkg = https://unpkg.com/charts.css/dist/charts.min.css
   - _Anything other than this will be treated as a link, local or not._
      - e.g. `/css/charts.min.css` or `https://cdn.example.com/css/charts.min.css`
This function returns `<link rel='stylesheet' href='$link'>` where `$link` is the location of the charts.css file.

_Clarification: If you are using any other method or have already linked to charts.css elsewhere in your head, this function is redundant._

## Usage

### Creating a new chart
```php
$chart = new ChartBoy\ChartBoy($arrayOfData);
```

You may also optionally set the type if not bar, primary and data axis labels, as well as the caption while creating a new chart.

```php
$chart = new ChartBoy\ChartBoy($arrayOfData, "column", "Foo", "Bar", "How Much 'Foo' per 'Bar'?");
```

### Rendering your chart
Rendering is done through the `renderChart` method. While optional, you may use a string as an argument to give the chart an id in its HTML.

_Using a div as a wrapper:_
```php
<div><?=$chart->renderChart("optional-id");?></div>
```

### Modifying your chart
**Methods**

 - `setType(type)`: Sets the chart type - All supported as of 0.9.0:
   - bar
   - column
   - area
   - line

  - `setCaption(caption, display = true)`: Sets the caption, and enables the `show-heading` charts.css option to `true` unless the second parameter is specified to be `false`

  - `setAxis(primary = null, data = null)`: Sets the axis labels, either being optional. Running this method with no parameters clears them, although this would make little sense to need to do as they are null by default.

  - `setColor(element, color)`: Sets the color of an individual data point by its key. If no color is given, it will clear the key of its color.

  - `inputData(array)`: Replaces the current dataset with an entirely new one, then recalculates the min/max.
    - Calling this method will change no settings or other variables.

  - `changeSetting(key, value = true)`:
    - _See below_

**charts.css Settings**

To change a setting, use the `changeSetting(key, value = true)` method, where 'key' is the setting and 'value' is the value, e.g. true, false, or an integer.
A full list and data type compatability table can be found [here.](https://chartscss.org/development/supported-features/)
_For variable rules, the '*' is entered verbatim will be replaced with the value- If none is specified, defaults to 1._

**Custom charts.css-based Settings**

 - `hide-data-strict` _Enables `hide-data` and then masks the HTML to not include the value, even invisibly._

--------------------

##### by Wolfgang de Groot, 2021
##### MIT License

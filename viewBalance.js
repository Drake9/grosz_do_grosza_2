am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_moonrisekingdom);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.PieChart);

// Add data
chart.data = [ {
  "kategoria": "mieszkanie",
  "kwota": 1000.00
}, {
  "kategoria": "jedzenie",
  "kwota": 750.00
}, {
  "kategoria": "transport",
  "kwota": 500.00
}];

// Add and configure Series
var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "kwota";
pieSeries.dataFields.category = "kategoria";
pieSeries.slices.template.stroke = am4core.color("#fff");
//pieSeries.labels.template.stroke = am4core.color("#fff");
pieSeries.slices.template.strokeWidth = 2;
pieSeries.slices.template.strokeOpacity = 1;

// This creates initial animation
pieSeries.hiddenState.properties.opacity = 1;
pieSeries.hiddenState.properties.endAngle = -90;
pieSeries.hiddenState.properties.startAngle = -90;

}); // end am4core.ready()

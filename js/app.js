var jsonData;
var vAxis;
var graphType;
var chartType;

function drawChart() {
    var data
    if (chartType == "single") {
        data = new google.visualization.DataTable(jsonData);
    } else {
        data = google.visualization.arrayToDataTable(jsonData);
    }

    var options = {
        title: 'Chart',
        chartArea: {width: '70%'},
        hAxis: {
          title: 'Temperature',
          minValue: 0
        },
        vAxis: {
          title: vAxis
        }
    };
    
    var chart;

    if (graphType == 'line') {
        chart = new google.visualization.LineChart(document.getElementById('chart_div'));
    } else if (graphType == 'pie') {
        chart = new google.visualization.PieChart(document.getElementById('chart_div'));
    } else {
        chart = new google.visualization.BarChart(document.getElementById('chart_div'));
    }
    chart.draw(data, options);
}

$(function() {
    var request;

    $("#chartType").on('change', function() {

        if ($(this).val() == "multiple") {
            $("#graphType option[value='pie']").attr('disabled', true);
            $("#xaxis").removeAttr('disabled');
            $("#yaxis").removeAttr('disabled');
            $('#xaxis').attr("required", true);
            $('#yaxis').attr("required", true);
        } else {
            $("#graphType option[value='pie']").removeAttr('disabled');
            $('#xaxis').attr('disabled', true);
            $('#yaxis').attr('disabled', true);
            $('#xaxis').removeAttr("required");
            $('#yaxis').removeAttr("required");
            $('#xaxis').val("");
            $('#yaxis').val("");
        }
    });

    $("form").submit(function(event){
        event.preventDefault();

        if (request) {
            request.abort();
        }

        $("#chart_div").html("");

        var $form = $(this);

        var $inputs = $form.find("button, textarea");
        vAxis = $('#xaxis').val();
        graphType = $('#graphType').val();
        chartType = $('#chartType').val();

        var serializedData = $form.serialize();
        $inputs.prop("disabled", true);
        request = $.ajax({
            url: "domain/Query.php",
            type: "post",
            data: serializedData
        });

        request.done(function (response, textStatus, jqXHR){
            console.log(response);

            google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            jsonData = response;

        });

        request.fail(function (jqXHR, textStatus, errorThrown){
            console.error(
                "The following error occurred: "+
                textStatus, errorThrown
            );
            $("#chart_div").html("Error on Request");
        });

        request.always(function () {
            $inputs.prop("disabled", false);

        });
    });
});
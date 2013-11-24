formatter: function() {
    var s = '<b>'+ this.x +'</b>';
    $.each(this.points, function(i, point) {
        var dec = point.y.toString().indexOf('.') > 0 ? 2 : 0;
        s += '<br/>'+ point.series.name +': ' + Highcharts.numberFormat(point.y, dec);
    });
    return s;
},
shared: true
formatter: function() {
    var s = '<b>'+ this.x +'</b>';
    $.each(this.points, function(i, point) {
        s += '<br/>'+ point.series.name +': ' + Math.floor( point.y / 60 ) +'分钟';
    });
    return s;
},
shared: true
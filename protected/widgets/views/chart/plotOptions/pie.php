pie : {
    allowPointSelect: false,
    cursor: 'pointer',
    dataLabels: {
        enabled: false
    },
    events:{
    	click: function (e) {
    		if (typeof clickPie != 'undefined') clickPie(e);
    	}
    }
}, 

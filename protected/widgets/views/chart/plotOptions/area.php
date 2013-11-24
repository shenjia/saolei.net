area: {
    marker: {
        enabled: false,
        symbol: 'circle',
        radius: 2,
        states: {
            hover: {
                enabled: true
            }
        }
    },
    events:{
    	click: function (e) {
    		if (typeof clickArea != 'undefined') clickArea(e);
    	}
    }
}
return this.value == '?'
	? '<span style="font-size: 14px">?</span>'
	: '<span style="font-size: 14px; color: '+ app.grades[this.value.toString().substr(0,1)] + '">' + this.value + '</span>';
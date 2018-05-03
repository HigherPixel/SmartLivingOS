var dd = (num) => {
	return num < 10 ? "0" + num : num;
};

var getTime = () => {
	var a = new Date();
	var year = a.getUTCFullYear();
	var month = dd( a.getUTCMonth()+1 );
	var date = dd( a.getUTCDate() );
	var hour = dd( a.getUTCHours() );
	var min = dd( a.getUTCMinutes() );
	var sec = dd( a.getUTCSeconds() );

	return year + "-" + month + "-" + date + " " + hour + ":" + min + ":" + sec;
	console.log(a);
};

console.log(getTime());
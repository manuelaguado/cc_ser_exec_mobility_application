$(document).ready(function() {
	$('#fecha').datepicker({
		format: 'yyyy-mm-dd',
		autoclose: true
	});
});

/*sumar columnas*/
jQuery.fn.dataTable.Api.register( 'sum()', function ( ) {
	return this.flatten().reduce( function ( a, b ) {
		if ( typeof a === 'string' ) {
			a = a.replace(/[^\d.-]/g, '') * 1;
		}
		if ( typeof b === 'string' ) {
			b = b.replace(/[^\d.-]/g, '') * 1;
		}

		var sunab = a + b;
		return parseFloat(sunab).toFixed(2);
	}, 0 );
} );
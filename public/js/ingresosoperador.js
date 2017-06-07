function accion_operatorGroup(id_tabla){
	$(document).ready(function() {
		$('#'+ id_tabla).dataTable();
		$('#'+ id_tabla +' tbody').on('click', 'tr', function () {
			var id = $('td', this).eq(10).text();
			carga_archivo('contenedor_principal', url_app + 'ingresosoperador/viajes_operador/' + id);
		} );
	} );
}

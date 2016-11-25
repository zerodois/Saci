/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-24 12:21:18
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-25 02:19:32
*/

var app = angular.module('saci');
app.controller( 'HomeController', HomeController );

function HomeController( $resource, URL ) {

	var self = this;
	self.search = search;
	self.data = {};
	self.data.Aeroporto = [];
	self.data.Companhia = [];
	self.field      = '';
	self.createData = {};
	self.createData.escala = [];
	self.setVal    = setVal;
	self.setEscala = setEscala;
	self.remove    = remove;
	self.cadastrar = cadastrar;

	function remove( type, ix ) { 
		var el;
		if( ix===undefined ) { 
			el = document.getElementById( `list-${self.createData[ type ].codigo}` );
			self.createData[ type ] = null;
		} else {
			el = document.getElementById( `list-${self.createData.escala[ ix ].el.codigo}` );
			self.createData.escala.splice( ix, 1 );
		}
		el.style.display = 'block';
	};

	function setVal( value, key ) { if(key) self.createData[ key ] = value; };
	function setEscala( value, index ) { self.createData.escala.push( { ix:index, el:value } ); };
	
	function search( data, destiny ){

		let $Service = $resource( `${URL}/Controller/${destiny}.php` );
		let $promise   = $Service.get( data ).$promise;
		//Atribui o resultado da busca ao vetor aeroporto
		$promise.then( json=>{ self.data[ destiny ] = json.data; });
	};

	function cadastrar() {

		let data = self.form;
		data.cod_origem = self.createData.origem.codigo;
		data.cod_destino = self.createData.destino.codigo;
		data.escalas = self.createData.escala;
		data.companhia = self.createData.companhia;
		
		let $promise   = $.post( `${URL}/Controller/Voo.php`, data );
		//Atribui o resultado da busca ao vetor aeroporto
		$promise.then( json => { console.log(json) } );
	}

}
HomeController['$inject'] = [ '$resource', 'URL' ];
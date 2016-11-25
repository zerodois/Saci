/*
* @Author: Felipe J. L. Rita
* @Date:   2016-11-24 12:21:18
* @Last Modified by:   Felipe J. L. Rita
* @Last Modified time: 2016-11-24 23:13:17
*/

var app = angular.module('saci');
app.controller( 'CompanhiaController', CompanhiaController );

function CompanhiaController( $resource, URL ) {

	var self = this;
	self.search = search;
	self.aeroporto = [];
	self.field      = '';
	self.createData = {};
	self.createData.escala = [];
	self.setVal    = setVal;
	self.setEscala = setEscala;
	self.remove    = remove;

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
	function search( data ){
		let $Aeroporto = $resource( `${URL}/Controller/Aeroporto.php` );
		let $promise   = $Aeroporto.get( self.formData ).$promise;
		//Atribui o resultado da busca ao vetor aeroporto
		$promise.then( json=>{ self.aeroporto = json.data; });
	};



}
CompanhiaController['$inject'] = [ '$resource', 'URL' ];
<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
error_reporting(0);
class SSP {
	static function data_output ( $columns, $data )
	{
		$out = array();

		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				if ( isset( $column['formatter'] ) ) {
					$row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['db'] ], $data[$i] );
				}
				else {
					$row[ $column['dt'] ] = $data[$i]->$columns[$j]['db'];
				}
			}
			$out[] = $row;
		}
		return $out;
	}
	static function simple ( $request, $table, $primaryKey, $columns )
	{
		$db = SSP::sql_connect();
		$limit = SSP::limit( $request, $columns );
		$order = SSP::order( $request, $columns );
		$where = SSP::filter( $request, $columns);
		$psql = "SELECT ".implode(", ", SSP::pluck($columns, 'db')).", count(*) OVER() AS total_count
			 FROM $table
			 $where
			 $order
			 $limit";
		$data = SSP::sql_exec( $db,$psql);
		$recordsFiltered = $data[0]->total_count;
		$resTotalLength = SSP::sql_exec( $db,
			"SELECT COUNT({$primaryKey}) AS total_count
			 FROM   $table"
		);
		$recordsTotal = $resTotalLength[0]->total_count;
		return array(
			"draw"            => intval( $request['draw'] ),
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => SSP::data_output( $columns, $data ),
			"sql"			  => $psql
		);
	}
	static function limit ( $request, $columns )
	{
		$limit = '';
		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = "OFFSET ".intval($request['start'])." LIMIT ".intval($request['length']);
		}
		return $limit;
	}
	static function order ( $request, $columns )
	{
		$order = '';
		if ( isset($request['order']) && count($request['order']) ) {
			$orderBy = array();
			$dtColumns = SSP::pluck( $columns, 'dt' );

			for ( $i=0, $ien=count($request['order']) ; $i<$ien ; $i++ ) {
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];
				if ( $requestColumn['orderable'] == 'true' ) {
					$dir = $request['order'][$i]['dir'] === 'asc' ?
						'ASC' :
						'DESC';

					$orderBy[] = ''.$column['real'].' '.$dir;
				}
			}

			$order = 'ORDER BY '.implode(', ', $orderBy);
		}
		return $order;
	}
	static function simple_join ( $request, $table, $primaryKey, $columns, $inner )
	{
		$db = SSP::sql_connect();
		$limit = SSP::limit( $request, $columns );
		$order = SSP::order( $request, $columns );
		$where = SSP::filter_join( $request, $columns );
		$psql = "SELECT ".implode(", ", SSP::pluck($columns, 'db')).", count(*) OVER() AS total_count
			 FROM 
			 $table
			 $inner
			 $where
			 $order
			 $limit";
		$data = SSP::sql_exec( $db,$psql);
		$recordsFiltered = $data[0]->total_count;
		$query_count = "SELECT COUNT({$primaryKey}) AS total_count FROM   $table";
		$resTotalLength = SSP::sql_exec( $db,$query_count);
		$recordsTotal = $resTotalLength[0]->total_count;
		return array(
			"draw"            => intval( $request['draw'] ),
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => SSP::output_join( $columns, $data ),
			"sql"			  => $psql
		);
	}
	static function filter_join ( $request, $columns )
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = SSP::pluck( $columns, 'dt' );

		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['searchable'] == 'true' ) {
					if($column['typ'] == 'txt'){
						$globalSearch[] = "".$column['dbj']." ILIKE "."'%".$str."%'";
					}elseif($column['typ'] == 'int'){
						$globalSearch[] = "cast (".$column['dbj']." as text) ILIKE "."'%".$str."%'";
					}
				}
			}
		}
		for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
			$requestColumn = $request['columns'][$i];
			$columnIdx = array_search( $requestColumn['data'], $dtColumns );
			$column = $columns[ $columnIdx ];

			$str = $requestColumn['search']['value'];

			if ( $requestColumn['searchable'] == 'true' &&
			 $str != '' ) {
				if($column['typ'] == 'txt'){
					$columnSearch[] = "".$column['dbj']." ILIKE "."'%".$str."%'";
				}elseif($column['typ'] == 'int'){
					$columnSearch[] = "cast (".$column['dbj']." as text) ILIKE "."'%".$str."%'";
				}				
			}
		}
		$where = '';
		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}
		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}
		if ( $where !== '' ) {
			$where = 'WHERE '.$where;
		}
		return $where;
	}
	static function output_join ( $columns, $data )
	{
		$out = array();

		for ( $i=0, $ien=count($data) ; $i<$ien ; $i++ ) {
			$row = array();

			for ( $j=0, $jen=count($columns) ; $j<$jen ; $j++ ) {
				$column = $columns[$j];
				if ( isset( $column['formatter'] ) ) {
					$row[ $column['dt'] ] = $column['formatter']( $data[$i][ $column['real'] ], $data[$i] );
				}
				else {
					if($column['truncate'] != false){
						$row[ $column['dt'] ] = substr($data[$i]->$columns[$j]['real'], 0, $column['truncate']);
					}else{
						$row[ $column['dt'] ] = $data[$i]->$columns[$j]['real'];
					}
				}
			}
			$out[] = $row;
		}
		return $out;
	}
	static function filter ( $request, $columns )
	{
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = SSP::pluck( $columns, 'dt' );

		if ( isset($request['search']) && $request['search']['value'] != '' ) {
			$str = $request['search']['value'];

			for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search( $requestColumn['data'], $dtColumns );
				$column = $columns[ $columnIdx ];

				if ( $requestColumn['searchable'] == 'true' ) {
					$globalSearch[] = "".$column['db']." ILIKE "."'%".$str."%'";
				}
			}
		}
		for ( $i=0, $ien=count($request['columns']) ; $i<$ien ; $i++ ) {
			$requestColumn = $request['columns'][$i];
			$columnIdx = array_search( $requestColumn['data'], $dtColumns );
			$column = $columns[ $columnIdx ];

			$str = $requestColumn['search']['value'];

			if ( $requestColumn['searchable'] == 'true' &&
			 $str != '' ) {
				$columnSearch[] = "".$column['db']." ILIKE "."'%".$str."%'";
			}
		}
		$where = '';
		if ( count( $globalSearch ) ) {
			$where = '('.implode(' OR ', $globalSearch).')';
		}
		if ( count( $columnSearch ) ) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where .' AND '. implode(' AND ', $columnSearch);
		}
		if ( $where !== '' ) {
			$where = 'WHERE '.$where;
		}
		return $where;
	}
	static function sql_connect ()
	{
		try {
			$options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING );
			$db = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);
		}
		catch (PDOException $e) {
			SSP::fatal(
				"An error occurred while connecting to the database. ".
				"The error reported by the server was: ".$e->getMessage()
			);
		}
		return $db;
	}
	static function sql_exec ( $db, $sql=null )
	{
		$stmt = $db->prepare( $sql );
		try{
		$stmt->execute();
		}
		catch (PDOException $e) {
			SSP::fatal( "An SQL error occurred: ".$e->getMessage()." [with] '".$sql."'" );
		}
		return $stmt->fetchAll();
	}
	static function fatal ( $msg )
	{
		echo json_encode( array( 
			"error" => $msg
		) );

		exit(0);
	}
	static function pluck ( $a, $prop )
	{
		$out = array();

		for ( $i=0, $len=count($a) ; $i<$len ; $i++ ) {
			$out[] = $a[$i][$prop];
		}
		return $out;
	}
	static function complex ( $request, $table, $primaryKey, $columns, $whereResult=null, $whereAll=null , $inner )
	{
		$db = SSP::sql_connect();
		$limit = SSP::limit( $request, $columns );
		$order = SSP::order( $request, $columns );
		$where = SSP::filter_join( $request, $columns );
		$whereResult = self::_flatten( $whereResult );
		$whereAll = self::_flatten( $whereAll );
		if ( $whereResult ) {
			$where = $where ?
				$where .' AND '.$whereResult :
				'WHERE '.$whereResult;
		}
		if ( $whereAll ) {
			$where = $where ?
				$where .' AND '.$whereAll :
				'WHERE '.$whereAll;
			$whereAllSql = 'WHERE '.$whereAll;
		}		
		$psql = "SELECT ".implode(", ", SSP::pluck($columns, 'db')).", count(*) OVER() AS total_count
			 FROM 
			 $table
			 $inner
			 $where
			 $order
			 $limit";
		$data = SSP::sql_exec( $db,$psql);
		$recordsFiltered = $data[0]->total_count;
		$query_count = "SELECT COUNT({$primaryKey}) AS total_count FROM   $table";
		$resTotalLength = SSP::sql_exec( $db,$query_count);
		$recordsTotal = $resTotalLength[0]->total_count;
		return array(
			"draw"            => intval( $request['draw'] ),
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => SSP::output_join( $columns, $data ),
			"sql"			  => $psql
		);
	}
	static function _flatten ( $a, $join = ' AND ' )
	{
		if ( ! $a ) {
			return '';
		}
		else if ( $a && is_array($a) ) {
			return implode( $join, $a );
		}
		return $a;
	}
}
?>
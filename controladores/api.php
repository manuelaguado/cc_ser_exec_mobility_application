<?php if ( ! defined( 'URL_APP' ) ) { exit; } ?>
<?php
class Api extends Controlador
{
	
	private $id_server = "4b252a9b-920b-4a41-afd0-001ccc09f23f";
	private $id_volumen = "22806686-ac3b-456a-adf7-305cc678599d";
	private $base_url = "https://cp-ams1.scaleway.com/";
	private $token = "3bf7befe-0682-4200-a5f5-246f91e7f98c";
	private $url_account = "https://account.scaleway.com/";
	
	/*
	Resources
		servers
		volumes
		users
			/users/{user_id}
	*/
	function __construct(){
		if(DEVELOPMENT == false){exit();}
	}	
    public function index()
    {
		$this->se_requiere_logueo(true);
		include (URL_TEMPLATE.'404_full.php');
    }
	public function get($resource,$action = NULL){
		
		($action === NULL)?$out = self::body($resource):$out = self::body($resource,$action);

		echo self::prettyPrint($out);
	}
	private function body($resource,$action = NULL){
		ob_start();
		($action === NULL)?$out = self::access($resource):$out = self::access($resource,$action);
		return ob_get_clean();
	}
	private function access($resource,$action = NULL){
		$curl = curl_init();
		$headers = [
			'X-Auth-Token: ' . $this->token ,
			'Content-Type: application/json',
			'Referer: https://centralcar.mx/'
		];
		if($action === NULL){
			$url = $this->base_url . $resource . '/' . $id_server . '/actions';
		}else{
			$url = $this->base_url . $resource . '/' . $id_server . '/' . $action;
		}
		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_HTTPHEADER => $headers
		));
		
		curl_exec($curl);
		
		if(!curl_exec($curl)){
			die('Error: "' . curl_error($curl) . '" - Code: ' . curl_errno($curl));
		}
		
		curl_close($curl);
	}
	function prettyPrint( $json )
	{
		$result = '';
		$level = 0;
		$in_quotes = false;
		$in_escape = false;
		$ends_line_level = NULL;
		$json_length = strlen( $json );

		for( $i = 0; $i < $json_length; $i++ ) {
			$char = $json[$i];
			$new_line_level = NULL;
			$post = "";
			if( $ends_line_level !== NULL ) {
				$new_line_level = $ends_line_level;
				$ends_line_level = NULL;
			}
			if ( $in_escape ) {
				$in_escape = false;
			} else if( $char === '"' ) {
				$in_quotes = !$in_quotes;
			} else if( ! $in_quotes ) {
				switch( $char ) {
					case '}': case ']':
						$level--;
						$ends_line_level = NULL;
						$new_line_level = $level;
						break;

					case '{': case '[':
						$level++;
					case ',':
						$ends_line_level = $level;
						break;

					case ':':
						$post = " ";
						break;

					case " ": case "\t": case "\n": case "\r":
						$char = "";
						$ends_line_level = $new_line_level;
						$new_line_level = NULL;
						break;
				}
			} else if ( $char === '\\' ) {
				$in_escape = true;
			}
			if( $new_line_level !== NULL ) {
				$result .= "\n".str_repeat( "\t", $new_line_level );
			}
			$result .= $char.$post;
		}

		return '<pre>'.$result.'</pre>';
	}	
}
?>
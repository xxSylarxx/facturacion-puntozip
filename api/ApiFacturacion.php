<?php

namespace api;

use api\Signature;

class ApiFacturacion
{
	const SUNAT_SEND_API_ENDPOINT = 'https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/';
	const SUNAT_SEND_API_ENDPOINT_TEST = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/";
	const SUNAT_CONSULT_API_ENDPOINT = 'https://api-cpe.sunat.gob.pe/v1/contribuyente/gem/comprobantes/envios/';
	const SUNAT_CONSULT_API_ENDPOINT_TEST = "https://gre-test.nubefact.com/v1/contribuyente/gem/comprobantes/envios/";

	public $mensajeError;
	public $coderror;
	public $xml;
	public $xmlb64;
	public $cdrb64;
	public $codrespuesta;
	public $hash;
	public $ticketS;
	public $code;
	public $token;

	public function EnviarComprobanteElectronico($emisor, $nombre, $ruta_archivo_xml, $ruta_archivo_cdr, $rutacertificado = null)
	{
		if ($emisor['modo'] == 'n') {
			$usuario_sol = $emisor['usuario_prueba'];
			$clave_sol = $emisor['clave_prueba'];
			$certificado = $emisor['certificado_prueba'];
			$wsS = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
			$pass_certificado = 'ceti';
		}
		if ($emisor['modo'] == 's') {
			$usuario_sol = $emisor['usuario_sol'];
			$clave_sol = $emisor['clave_sol'];
			$certificado = $emisor['certificado'];
			$wsS = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl';
			// $wsS = 'https://e-factura.sunat.gob.pe:443/ol-ti-itcpfegem/billService';
			$pass_certificado = $emisor['clave_certificado'];
		}

		$objfirma = new Signature();
		$flg_firma = 0; //Posicion del XML: 0 para firma
		// $ruta_xml_firmar = $ruta . '.XML'; //es el archivo XML que se va a firmar
		$ruta = $ruta_archivo_xml . $nombre . '.XML';

		$ruta_firma = $rutacertificado . 'api/certificado/' . $certificado; //ruta del archivo del certicado para firmar
		$pass_firma = $pass_certificado;

		$resp = $objfirma->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);
		//firma----------------------------------------------------------------
		//print_r($this->hash = $resp);
		//echo '</br> XML FIRMADO';
		$this->xml = $nombre . '.XML';
		//FIRMAR XML - FIN

		//CONVERTIR A ZIP - INICIO
		$zip = new \ZipArchive();

		$nombrezip = $nombre . ".ZIP";
		$rutazip = $ruta_archivo_xml . $nombrezip;

		if ($zip->open($rutazip, \ZipArchive::CREATE) === TRUE) {
			$zip->addFile($ruta, $nombre . '.XML');
			$zip->close();
		}

		// echo '</br>XML ZIPEADO';

		//CONVERTIR A ZIP - FIN


		//ENVIAR EL ZIP A LOS WS DE SUNAT - INICIO
		$ws = $wsS; //ruta del servicio web de pruebad e SUNAT para enviar documentos

		$ruta_archivo = $rutazip;
		$nombre_archivo = $nombrezip;

		$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo)); //codificar y convertir en texto el .zip

		//echo '</br> '. $contenido_del_zip;
		//echo '</br> '. $contenido_del_zip;
		$xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
		 <soapenv:Header>
		 <wsse:Security>
			 <wsse:UsernameToken>
				 <wsse:Username>' . $emisor['ruc'] . $usuario_sol . '</wsse:Username>
				 <wsse:Password>' . $clave_sol . '</wsse:Password>
			 </wsse:UsernameToken>
		 </wsse:Security>
		 </soapenv:Header>
		 <soapenv:Body>
		 <ser:sendBill>
			 <fileName>' . $nombre_archivo . '</fileName>
			 <contentFile>' . $contenido_del_zip . '</contentFile>
		 </ser:sendBill>
		 </soapenv:Body>
	 </soapenv:Envelope>';

		$header = array(
			"Content-type: text/xml; charset=\"utf-8\"",
			"Accept: text/xml",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"SOAPAction: ",
			"Content-lenght: " . strlen($xml_envio)
		);

		$ch = curl_init(); //iniciar la llamada
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1); //
		curl_setopt($ch, CURLOPT_URL, $ws);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		//para ejecutar los procesos de forma local en windows
		//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem"); //solo en local, si estas en el servidor web con ssl comentar esta línea

		$response = curl_exec($ch); // ejecucion del llamado y respuesta del WS SUNAT.

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // objten el codigo de respuesta de la peticion al WS SUNAT
		$estadofe = "0"; //inicializo estado de operación interno
		// var_dump($response);
		if ($httpcode == 200) //200: La comunicacion fue satisfactoria
		{
			$doc = new \DOMDocument(); //clase que nos permite crear documentos XML
			$doc->loadXML($response); //cargar y crear el XML por medio de text-xml response

			if (isset($doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue)) // si en la etique de rpta hay valor entra
			{

				$cdr = $doc->getElementsByTagName('applicationResponse')->item(0)->nodeValue; //guadarmos la respuesta(text-xml) en la variable 

				$cdr = base64_decode($cdr); //decodificando el xml
				file_put_contents($ruta_archivo_cdr . 'R-' . $nombrezip, $cdr); //guardo el CDR zip en la carpeta cdr

				$this->cdrb64 = "R-" . $nombrezip;

				$zip = new \ZipArchive();
				if ($zip->open($ruta_archivo_cdr . 'R-' . $nombrezip) === true) //rpta es identica existe el archivo
				{
					$zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.XML');
					$zip->close();

					$this->xmlb64 = "R-" . $nombre . '.XML';
				}

				$xml_decode = file_get_contents($ruta_archivo_cdr . 'R-' . $nombre . '.XML') or die("Error: Cannot create object");

				// Obteniendo datos del archivo .XML
				$ResponseCode = "";
				$DOM = new \DOMDocument();
				$DOM->loadXML($xml_decode);
				$ResponseCode = $DOM->getElementsByTagName('ResponseCode')->item(0)->nodeValue;

				$description = $DOM->getElementsByTagName('Description')->item(0)->nodeValue;

				$fecha3 = $DOM->getElementsByTagName('ResponseDate')->item(0)->nodeValue;

				$hash_cdr = $DOM->getElementsByTagName('DigestValue')->item(0)->nodeValue;

				$pos = $ResponseCode;
				//=============hash CDR=================


				if ($pos == 0) {
					$estadofe = 1;
				} else {
					$estadofe = $pos;
				}

				if ($ResponseCode >= 2000 && $ResponseCode <= 3999) {


					$this->coderror = $ResponseCode;
					$this->mensajeError = $description;
					$this->codrespuesta = 2;
					$this->code = $ResponseCode;
				} else {
					echo  '<div class="btnsuccess">' . $description . ' por Sunat</div>';
				}

				// echo  '<div class="btnsuccess">' . $description . ' por Sunat</div>';

				$this->codrespuesta = $estadofe;
			} else {

				$estadofe = '2';
				if (isset($doc->getElementsByTagName('faultcode')->item(0)->nodeValue) || $doc->getElementsByTagName('faultcode')->item(0)->nodeValue != null) {

					$codigo = $doc->getElementsByTagName('faultcode')->item(0)->nodeValue;
					$mensaje = $doc->getElementsByTagName('faultstring')->item(0)->nodeValue;
					echo "error " . $codigo . ": " . $mensaje;
					$code = preg_replace('/[^0-9]/', '', $codigo);
					if ($code >= 2000 && $code <= 3999) {
						$this->coderror = $codigo;
						$this->mensajeError = $mensaje;
						$this->codrespuesta = $estadofe;
						$this->code = $code;
					} else {
						// echo 'Ocurrio un error con código: ' . $codigo . ' Msje:' . $mensaje;
						$this->coderror = '';
						$this->mensajeError = '';
						$this->codrespuesta = 3;
						$this->code = $code;
					}
				} else {
					echo $response;
				}
			}
		} else { //Problemas de comunicacion
			$estadofe = "3";
			if ($response) {
				$doc = new \DOMDocument();
				$doc->loadXML($response);
			}

			echo curl_error($ch);
			echo "<script>
			Swal.fire({
				title: 'Existe un problema de conexión',
				text: '¡OJO!',
				html: `<h4>El comprobante ya fue registrado y se encuentra en <a href='ventas'>Administrar ventas</a>, puede enviarlo cuando se restablezca la conexión</h4>
				$response
				<p>
				<button class='btn btn-primary' id='btncdrFB'>CDR</button>`,
				icon: 'warning',			
				showCancelButton: true,
				showConfirmButton: false,
				allowOutsideClick: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				cancelButtonText: 'Cerrar',
			})
			</script>";

			$this->codrespuesta = $estadofe;
		}

		curl_close($ch);

		//ENVIAR EL ZIP A LOS WS DE SUNAT - FIN

	}

	public static function ObtenerToken($emisor)
	{
		// MODO PRUEBAS===========================
		if ($emisor['modo'] == 'n') {
			$usuario_sol = $emisor['usuario_prueba'];
			$clave_sol = $emisor['clave_prueba'];
			$wsS = "https://gre-test.nubefact.com/v1/clientessol/test-85e5b0ae-255c-4891-a595-0b98c65c9854/oauth2/token";

			$datos_token = array(
				'grant_type' => 'password',
				'scope' => 'https://api-cpe.sunat.gob.pe',
				'client_id' => "test-85e5b0ae-255c-4891-a595-0b98c65c9854",
				'client_secret' => "test-Hty/M6QshYvPgItX2P0+Kw==",
				'username'    => $emisor['ruc'] . $usuario_sol,
				'password'    => $clave_sol
			);
		}

		// MODO PRODUCCIÓN===========================
		if ($emisor['modo'] == 's') {
			$usuario_sol = $emisor['usuario_sol'];
			$clave_sol = $emisor['clave_sol'];
			$wsS = 'https://api-seguridad.sunat.gob.pe/v1/clientessol/' . $emisor['client_id'] . '/oauth2/token/';

			$datos_token = array(
				'grant_type' => 'password',
				'scope' => 'https://api-cpe.sunat.gob.pe/',
				'client_id' => $emisor['client_id'],
				'client_secret' => $emisor['secret_id'],
				'username'    => $emisor['ruc'] . $usuario_sol,
				'password'    => $clave_sol
			);
		}



		$payload = http_build_query($datos_token);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => ($_SERVER['REQUEST_SCHEME'] == 'https') ? 1 : false,
			CURLOPT_URL => $wsS,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_ANY,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_POST => true,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $payload,
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/x-www-form-urlencoded'

			),

		));

		$response = curl_exec($curl);
		if (curl_errno($curl)) {
			echo 'Error de cURL: ' . curl_error($curl) . '<br>primer echo<br>';
		} else {
			// Verificar si la respuesta es un JSON válido
			$result = json_decode($response, true);
			if (json_last_error() !== JSON_ERROR_NONE) {
				echo 'Error al decodificar JSON: ' . json_last_error_msg() . '<br>segundo echo<br>';
				echo 'Respuesta de la API: ' . $response . '<br>tercer echo<br>';
			} else {
				// Aquí procesarías el resultado si la decodificación fue exitosa
				//var_dump($result);
			}
		}
		curl_close($curl);
		//var_dump($response);
		return json_decode($response);
		//echo $response;
	}
	public function EnviarGuiaRemision($emisor, $nombre, $ruta_archivo_xml, $ruta_archivo_cdr, $rutacertificado = null)
	{
		// MODO PRUEBAS===========================
		if ($emisor['modo'] == 'n') {
			$certificado = $emisor['certificado_prueba'];
			$wsS = self::SUNAT_SEND_API_ENDPOINT_TEST;
			$pass_certificado = 'ceti';
		}
		// MODO PRODUCCIÓN===========================
		if ($emisor['modo'] == 's') {
			$certificado = $emisor['certificado'];
			$wsS = self::SUNAT_SEND_API_ENDPOINT;
			$pass_certificado = $emisor['clave_certificado'];
		}

		$objfirma = new Signature();
		$flg_firma = 0; //Posicion del XML: 0 para firma
		// $ruta_xml_firmar = $ruta . '.XML'; //es el archivo XML que se va a firmar
		$ruta = $ruta_archivo_xml . $nombre . '.xml';

		$ruta_firma = $rutacertificado . 'api/certificado/' . $certificado; //ruta del archivo del certicado para firmar
		$pass_firma = $pass_certificado;

		$resp = $objfirma->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);
		//firma----------------------------------------------------------------
		// print_r($this->hash = $resp);
		//echo '</br> XML FIRMADO';
		$this->xml = $nombre . '.xml';
		//FIRMAR XML - FIN

		//CONVERTIR A ZIP - INICIO
		$zip = new \ZipArchive();

		$nombrezip = $nombre . ".zip";
		$rutazip = $ruta_archivo_xml . $nombre . ".zip";

		if ($zip->open($rutazip, \ZipArchive::CREATE) === TRUE) {
			$zip->addFile($ruta, $nombre . '.xml');
			$zip->close();
		}

		// echo '</br>XML ZIPEADO';

		//CONVERTIR A ZIP - FIN


		//ENVIAR EL ZIP A LOS WS DE SUNAT - INICIO
		$ws = $wsS; //ruta del servicio web de pruebad e SUNAT para enviar documentos

		$ruta_archivo = $rutazip;
		$nombre_archivo = $nombrezip;

		$binario = file_get_contents($ruta_archivo);
		$contenido_del_zip = base64_encode($binario); //codificar y convertir en texto el .zip
		$hashzip = hash_file('sha256', $ruta_archivo);
		$data = [
			'archivo' => [
				'nomArchivo' => $nombre_archivo,
				'arcGreZip' => $contenido_del_zip,
				'hashZip' => $hashzip,
			],
		];
		$dataS = json_encode($data);
		// var_dump($data);
		$token_result = ApiFacturacion::ObtenerToken($emisor);
		// var_dump($token_result);
		$token = $token_result->access_token;

		$this->token = $token;

		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_URL => $wsS . $nombre,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => $dataS,
			CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer " . $token,
				'Content-Type: application/json'
			),

		));

		//para ejecutar los procesos de forma local en windows
		//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		//  curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem"); //solo en local, si estas en el servidor web con ssl comentar esta línea
		if (curl_error($ch) === false) {
			echo "Error: " . curl_error($ch);
		} else {
			$response = curl_exec($ch);
		}

		// ejecucion del llamado y respuesta del WS SUNAT.

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // objten el codigo de respuesta de la peticion al WS SUNAT

		curl_close($ch);

		$obt_ticket = json_decode($response);
	
		if (is_object($obt_ticket)) {
			$this->ticketS = $obt_ticket->numTicket;
		} else {
			// Manejar el error
			error_log("Error: \$obt_ticket no es un objeto. Tipo de dato: " . gettype($obt_ticket));
			// Puedes asignar un valor predeterminado o lanzar una excepción
			//$this->ticketS = null;
		}
		//$this->ticketS = $obt_ticket->numTicket;


		// var_dump($response);
	}
	public function ConsultarTicketGuiaRemision($emisor, $ticket, $token, $nombre_archivo, $nombre, $ruta_archivo_cdr)
	{

		//echo '<pre>';
		//var_dump($emisor, $ticket, $token, $nombre_archivo, $nombre, $ruta_archivo_cdr); die();
		if ($emisor['modo'] == 'n') {

			$wsS = self::SUNAT_CONSULT_API_ENDPOINT_TEST;
		}
		if ($emisor['modo'] == 's') {

			$wsS = self::SUNAT_CONSULT_API_ENDPOINT;
		}

		$ch = curl_init();

		curl_setopt_array($ch, array(
			CURLOPT_SSL_VERIFYPEER => 1,
			CURLOPT_URL => $wsS . $ticket,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTPAUTH => CURLAUTH_ANY,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_POST => true,
			CURLOPT_CUSTOMREQUEST => 'GET',
			// CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $token,
				'Content-Type: application/json'
			),

		));

		//para ejecutar los procesos de forma local en windows
		//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		//curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem"); //solo en local, si estas en el servidor web con ssl comentar esta línea

		$response = curl_exec($ch);

		// var_dump($response);
		// ejecucion del llamado y respuesta del WS SUNAT.

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // objten el codigo de respuesta de la peticion al WS SUNAT
		//echo '<pre>';
		//var_dump($httpcode, $response); die();

		// var_dump($response);
		// exit();
		$cdrDecode = json_decode($response);

		echo '<pre>';
		print_r($cdrDecode);
		exit();

		if ($httpcode == 200) {



			if (isset($cdrDecode->arcCdr)) {

				@$cdr = base64_decode($cdrDecode->arcCdr);

				file_put_contents($ruta_archivo_cdr . "R-" . $nombre_archivo, $cdr);

				$this->cdrb64 = "R-" . $nombre . '.zip';

				$zip = new \ZipArchive;
				if ($zip->open($ruta_archivo_cdr . "R-" . $nombre_archivo) === true) {
					$zip->extractTo($ruta_archivo_cdr, 'R-' . $nombre . '.xml');
					$zip->close();
				}

				$this->xmlb64 = "R-" . $nombre . '.xml';

				$xml_decode = file_get_contents($ruta_archivo_cdr . 'R-' . $nombre . '.xml') or die("Error: Cannot create object");
				// Obteniendo datos del archivo .XML
				$aceptado = "";
				$DOM = new \DOMDocument();
				$DOM->loadXML($xml_decode);

				$ResponseCode = $DOM->getElementsByTagName('ResponseCode')->item(0)->nodeValue;

				$description = $DOM->getElementsByTagName('Description')->item(0)->nodeValue;

				$fecha3 = $DOM->getElementsByTagName('ResponseDate')->item(0)->nodeValue;

				$hash_cdr = $DOM->getElementsByTagName('DigestValue')->item(0)->nodeValue;

				$pos = $ResponseCode;



				if ($pos == 0) {
					$estadofe = '1';
				} else {
					$estadofe = $pos;
				}
				$this->codrespuesta = $estadofe;
				echo  '<div class="btnsuccess">' . $description . ' por Sunat</div>';
			} else {
				$estadofe = '';
				// $xml_decode = file_get_contents($ruta_archivo_cdr . 'R-' . $nombre . '.xml') or die("Error: Cannot create object");
				// // Obteniendo datos del archivo .XML
				// $aceptado = "";
				// $DOM = new \DOMDocument();
				// $DOM->loadXML($xml_decode);
				// $codigo = $DOM->getElementsByTagName("faultcode")->item(0)->nodeValue;
				// $mensaje = $DOM->getElementsByTagName("faultstring")->item(0)->nodeValue;
				// echo "error " . $codigo . ": " . $mensaje;
				// $this->coderror = $cdrDecode->error->numError;
				// $this->mensajeError = $cdrDecode->error->desError;
				// $this->codrespuesta = $estadofe;
			}
		} else {
			// var_dump($response);
			$estadofe = '3';
			echo curl_error($ch);
			echo "Problema de conexión, intentelo más tarde";
			$this->codrespuesta = $estadofe;
		}
		curl_close($ch);
	}

	public function EnviarResumenComprobantes($emisor, $nombrexml, $ruta_archivo_xml, $rutacertificado = null)
	{
		if ($emisor['modo'] == 'n') {
			$usuario_sol = $emisor['usuario_prueba'];
			$clave_sol = $emisor['clave_prueba'];
			$certificado = $emisor['certificado_prueba'];
			$wsS = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
			$pass_certificado = 'ceti';
		}
		if ($emisor['modo'] == 's') {
			$usuario_sol = $emisor['usuario_sol'];
			$clave_sol = $emisor['clave_sol'];
			$certificado = $emisor['certificado'];
			$wsS = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl';
			$pass_certificado = $emisor['clave_certificado'];
		}
		//firma del documento
		$objSignature = new Signature();

		$flg_firma = "0";
		//$ruta_archivo_xml = "xml/";
		$ruta = $ruta_archivo_xml . $nombrexml . '.XML';

		$ruta_firma = $rutacertificado . "api/certificado/" . $certificado;
		$pass_firma = $pass_certificado;

		$resp = $objSignature->signature_xml($flg_firma, $ruta, $ruta_firma, $pass_firma);

		//print_r($resp); //hash

		//Generar el .zip

		$zip = new \ZipArchive();

		$nombrezip = $nombrexml . ".ZIP";
		$rutazip = $ruta_archivo_xml . $nombrexml . ".ZIP";

		if ($zip->open($rutazip, \ZIPARCHIVE::CREATE) === true) {
			$zip->addFile($ruta, $nombrexml . '.XML');
			$zip->close();
		}


		//Enviamos el archivo a sunat

		$ws = $wsS;

		$ruta_archivo = $rutazip;
		$nombre_archivo = $nombrezip;
		// $ruta_archivo_cdr = "cdr/";

		$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo));


		$xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
				 <soapenv:Header>
				 	<wsse:Security>
				 		<wsse:UsernameToken>
				 			<wsse:Username>' . $emisor['ruc'] . $usuario_sol . '</wsse:Username>
				 			<wsse:Password>' . $clave_sol . '</wsse:Password>
				 		</wsse:UsernameToken>
				 	</wsse:Security>
				 </soapenv:Header>
				 <soapenv:Body>
				 	<ser:sendSummary>
				 		<fileName>' . $nombre_archivo . '</fileName>
				 		<contentFile>' . $contenido_del_zip . '</contentFile>
				 	</ser:sendSummary>
				 </soapenv:Body>
				</soapenv:Envelope>';


		$header = array(
			"Content-type: text/xml; charset=\"utf-8\"",
			"Accept: text/xml",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"SOAPAction: ",
			"Content-lenght: " . strlen($xml_envio)
		);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $ws);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//para ejecutar los procesos de forma local en windows
		//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");


		$response = curl_exec($ch);

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$estadofe = "0";
		// echo $httpcode;
		// var_dump($response);
		$ticket = "0";
		if ($httpcode == 200) {
			$doc = new \DOMDocument();
			$doc->loadXML($response);

			if (isset($doc->getElementsByTagName('ticket')->item(0)->nodeValue)) {
				$ticket = $doc->getElementsByTagName('ticket')->item(0)->nodeValue;
				echo "NÚMERO DE TICKET: " . $ticket;
				$this->ticketS = $ticket;
			} else {

				$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
				$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
				echo "error " . $codigo . ": " . $mensaje;
			}
		} else {
			echo curl_error($ch);
			echo "Problema de conexión";
		}

		curl_close($ch);
		return $ticket;
	}

	public function ConsultarTicket($emisor, $nombrexml, $ticket, $ruta_archivo_cdr)
	{
		if ($emisor['modo'] == 'n') {
			$usuario_sol = $emisor['usuario_prueba'];
			$clave_sol = $emisor['clave_prueba'];
			$certificado = $emisor['certificado_prueba'];
			$wsS = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
			$pass_certificado = 'ceti';
		}
		if ($emisor['modo'] == 's') {
			$usuario_sol = $emisor['usuario_sol'];
			$clave_sol = $emisor['clave_sol'];
			$certificado = $emisor['certificado'];
			$wsS = 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl';
			$pass_certificado = $emisor['clave_certificado'];
		}

		$ws = $wsS;

		$nombre_xml	= $nombrexml . ".XML";

		//===============================================================//

		$this->xml = $nombre_xml;
		//ALMACENAR EL ARCHIVO EN UN ZIP

		$nombrezip = $nombrexml . ".ZIP";
		//$ruta_archivo_cdr = "cdr/";

		//$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo.'.ZIP'));
		//FIN ZIP

		$xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
                <wsse:Security>
                    <wsse:UsernameToken>
                    <wsse:Username>' . $emisor['ruc'] . $usuario_sol . '</wsse:Username>
                    <wsse:Password>' . $clave_sol . '</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:getStatus>
                    <ticket>' . $ticket . '</ticket>
                </ser:getStatus>
            </soapenv:Body>
        </soapenv:Envelope>';


		$header = array(
			"Content-type: text/xml; charset=\"utf-8\"",
			"Accept: text/xml",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"SOAPAction: ",
			"Content-lenght: " . strlen($xml_envio)
		);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $ws);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//para ejecutar los procesos de forma local en windows
		//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// var_dump($response);
		// echo "codigo:" . $ticket;

		if ($httpcode == 200) {
			$doc = new \DOMDocument();
			$doc->loadXML($response);

			if (isset($doc->getElementsByTagName('content')->item(0)->nodeValue)) {
				$cdr = $doc->getElementsByTagName('content')->item(0)->nodeValue;
				$cdr = base64_decode($cdr);
				file_put_contents($ruta_archivo_cdr . "R-" . $nombrezip, $cdr);
				$this->cdrb64 = "R-" . $nombrezip;


				$zip = new \ZipArchive;
				if ($zip->open($ruta_archivo_cdr . "R-" . $nombrezip) === true) {
					$zip->extractTo($ruta_archivo_cdr, 'R-' . $nombrexml . '.XML');
					$zip->close();

					$this->xmlb64 = "R-" . $nombrexml . '.XML';
				}

				$xml_decode = file_get_contents($ruta_archivo_cdr . 'R-' . $nombrexml . '.XML') or die("Error: Cannot create object");
				// Obteniendo datos del archivo .XML
				$aceptado = "";
				$DOM = new \DOMDocument();
				$DOM->loadXML($xml_decode);

				$ResponseCode = $DOM->getElementsByTagName('ResponseCode')->item(0)->nodeValue;

				$description = $DOM->getElementsByTagName('Description')->item(0)->nodeValue;

				$fecha3 = $DOM->getElementsByTagName('ResponseDate')->item(0)->nodeValue;

				$hash_cdr = $DOM->getElementsByTagName('DigestValue')->item(0)->nodeValue;

				$pos = $ResponseCode;


				if ($pos == 0) {
					$estadofe = '1';
				} else {
					$estadofe = $pos;
				}
				echo  '<div class="btnsuccess">' . $description . ' por Sunat</div>';

				$this->codrespuesta = $estadofe;
			} else {
				$estadofe = '2';
				@$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
				@$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
				echo "error " . $codigo . ": " . $mensaje;

				$this->coderror = $codigo;
				$this->mensajeError = $mensaje;
				$this->codrespuesta = $estadofe;
			}
		} else {
			$estadofe = '3';
			echo curl_error($ch);
			echo "Problema de conexión";
			$this->codrespuesta = $estadofe;
		}

		curl_close($ch);
	}
	public function ObtenerCDR($emisor, $nombrexml, $comprobante,  $ruta_archivo_cdr)
	{
		if ($emisor['modo'] == 'n') {
			$usuario_sol = $emisor['usuario_prueba'];
			$clave_sol = $emisor['clave_prueba'];
			$certificado = $emisor['certificado_prueba'];
			$wsS = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
			$pass_certificado = 'ceti';
		}
		if ($emisor['modo'] == 's') {
			$usuario_sol = $emisor['usuario_sol'];
			$clave_sol = $emisor['clave_sol'];
			$certificado = $emisor['certificado'];
			$wsS = 'https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
			$pass_certificado = $emisor['clave_certificado'];
		}

		$ws = $wsS;

		$nombre_xml	= $nombrexml . ".XML";

		//===============================================================//

		$this->xml = $nombre_xml;
		//ALMACENAR EL ARCHIVO EN UN ZIP

		$nombrezip = $nombrexml . ".ZIP";
		//$ruta_archivo_cdr = "cdr/";

		//$contenido_del_zip = base64_encode(file_get_contents($ruta_archivo.'.ZIP'));
		//FIN ZIP

		$xml_envio = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
            <soapenv:Header>
                <wsse:Security>
                    <wsse:UsernameToken>
                    <wsse:Username>' . $emisor['ruc'] . $usuario_sol . '</wsse:Username>
                    <wsse:Password>' . $clave_sol . '</wsse:Password>
                    </wsse:UsernameToken>
                </wsse:Security>
            </soapenv:Header>
            <soapenv:Body>
                <ser:getStatusCdr>
					<rucComprobante>' . $emisor['ruc'] . '</rucComprobante>
					<tipoComprobante>' . $comprobante['tipoComprobante'] . '</tipoComprobante>
					<serieComprobante>' . $comprobante['serieComprobante'] . '</serieComprobante>
					<numeroComprobante>' . $comprobante['numeroComprobante'] . '</numeroComprobante>
 				</ser:getStatusCdr>

            </soapenv:Body>
        </soapenv:Envelope>';


		$header = array(
			"Content-type: text/xml; charset=\"utf-8\"",
			"Accept: text/xml",
			"Cache-Control: no-cache",
			"Pragma: no-cache",
			"SOAPAction: ",
			"Content-lenght: " . strlen($xml_envio)
		);


		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
		curl_setopt($ch, CURLOPT_URL, $ws);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($ch, CURLOPT_TIMEOUT, 120);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_envio);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		//para ejecutar los procesos de forma local en windows
		//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
		curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		// var_dump($response);
		// echo "codigo:" . $ticket;

		if ($httpcode == 200) {
			$doc = new \DOMDocument();
			$doc->loadXML($response);

			if (isset($doc->getElementsByTagName('content')->item(0)->nodeValue)) {
				$cdr = $doc->getElementsByTagName('content')->item(0)->nodeValue;
				$cdr = base64_decode($cdr);
				file_put_contents($ruta_archivo_cdr . "R-" . $nombrezip, $cdr);
				$this->cdrb64 = "R-" . $nombrezip;


				$zip = new \ZipArchive;
				if ($zip->open($ruta_archivo_cdr . "R-" . $nombrezip) === true) {
					$zip->extractTo($ruta_archivo_cdr, 'R-' . $nombrexml . '.XML');
					$zip->close();

					$this->xmlb64 = "R-" . $nombrexml . '.XML';
				}

				$xml_decode = file_get_contents($ruta_archivo_cdr . 'R-' . $nombrexml . '.XML') or die("Error: Cannot create object");
				// Obteniendo datos del archivo .XML
				$aceptado = "";
				$DOM = new \DOMDocument();
				$DOM->loadXML($xml_decode);

				$ResponseCode = $DOM->getElementsByTagName('ResponseCode')->item(0)->nodeValue;

				$description = $DOM->getElementsByTagName('Description')->item(0)->nodeValue;

				$fecha3 = $DOM->getElementsByTagName('ResponseDate')->item(0)->nodeValue;

				$hash_cdr = $DOM->getElementsByTagName('DigestValue')->item(0)->nodeValue;

				$pos = $ResponseCode;


				if ($pos == 0) {
					$estadofe = '1';
				} else {
					$estadofe = $pos;
				}
				echo  '<div class="btnsuccess">' . $description . ' por Sunat</div>';

				$this->codrespuesta = $estadofe;
			} else {
				$estadofe = '2';
				@$codigo = $doc->getElementsByTagName("faultcode")->item(0)->nodeValue;
				@$mensaje = $doc->getElementsByTagName("faultstring")->item(0)->nodeValue;
				echo "error " . $codigo . ": " . $mensaje;

				$this->coderror = $codigo;
				$this->mensajeError = $mensaje;
				$this->codrespuesta = $estadofe;
			}
		} else {
			$estadofe = '3';
			echo curl_error($ch);
			echo "Problema de conexión";
			$this->codrespuesta = $estadofe;
		}

		curl_close($ch);
	}
	public $codigoMsg;
	public $msgConsulta;
	function consultarComprobante($emisor, $comprobante)
	{

		try {
			if ($emisor['modo'] == 'n') {
				$usuario_sol = $emisor['usuario_prueba'];
				$clave_sol = $emisor['clave_prueba'];
				$certificado = $emisor['certificado_prueba'];
				$wsS = 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';
			}
			if ($emisor['modo'] == 's') {

				$usuario_sol = $emisor['usuario_sol'];
				$clave_sol = $emisor['clave_sol'];
				$certificado = $emisor['certificado'];
				// $wsS = 'https://ww1.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
				$wsS = 'https://e-factura.sunat.gob.pe/ol-it-wsconscpegem/billConsultService?wsdl';
			}
			$ws = $wsS;



			$xml_post_string = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" 
				xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://service.sunat.gob.pe" xmlns:wsse="http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd">
					<soapenv:Header>
						<wsse:Security>
							<wsse:UsernameToken>
								<wsse:Username>' . $emisor['ruc'] . $usuario_sol . '</wsse:Username>
								<wsse:Password>' . $clave_sol . '</wsse:Password>
							</wsse:UsernameToken>
						</wsse:Security>
					</soapenv:Header>
					<soapenv:Body>
						<ser:getStatus>
							<rucComprobante>' . $emisor['ruc'] . '</rucComprobante>
							<tipoComprobante>' . $comprobante['tipocomp'] . '</tipoComprobante>
							<serieComprobante>' . $comprobante['serie'] . '</serieComprobante>
							<numeroComprobante>' . $comprobante['correlativo'] . '</numeroComprobante>
						</ser:getStatus>
					</soapenv:Body>
				</soapenv:Envelope>';



			$headers = array(
				"Content-type: text/xml;charset=\"utf-8\"",
				"Accept: text/xml",
				"Cache-Control: no-cache",
				"Pragma: no-cache",
				"SOAPAction: ",
				"Content-length: " . strlen($xml_post_string),
			);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
			curl_setopt($ch, CURLOPT_URL, $ws);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

			//para ejecutar los procesos de forma local en windows
			//enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
			curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__) . "/cacert.pem");

			$response = curl_exec($ch);
			$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

			// echo $response;
			$doc = new \DOMDocument();
			$doc->loadXML($response);


			if (isset($doc->getElementsByTagName('statusMessage')->item(0)->nodeValue) && $doc->getElementsByTagName('statusMessage')->item(0)->nodeValue != null) {
				$mensaje = $doc->getElementsByTagName('statusMessage')->item(0)->nodeValue;
				$code = $doc->getElementsByTagName('statusCode')->item(0)->nodeValue;
				$this->codigoMsg = $code;
				$this->msgConsulta = $comprobante['serie'] . '-' . $comprobante['correlativo'] . '<br/>' . $mensaje;
			} else {

				$this->msgConsulta = 'SOLO SE PUEDE CONSULTAR EN PRODUCCIÓN';
			}

			// echo $response;
		} catch (\Exception $e) {
			$this->msgConsulta = "SUNAT ESTA FUERA DE SERVICIO: " . $e->getMessage();
		}
	}
}

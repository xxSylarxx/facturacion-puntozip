<?php

namespace api;

class GeneradorXML
{

   // const URL_API_FABO = 'http://localhost/apiTechMultiServ/apifactura';
   const URL_API_FABO = 'https://apitech.apifacturacion.com/v2/apifactura';
   const URL_API_NOTA_CREDITO =  'https://apitech.apifacturacion.com/v2/apinotac';
   const URL_API_NOTA_DEBITO =  'https://apitech.apifacturacion.com/v2/apinotad';
   const URL_API_RESUMEN =  'https://apitech.apifacturacion.com/v2/apiresumen';
   const URL_API_BAJA =  'https://apitech.apifacturacion.com/v2/apibaja';
   const URL_API_GUIA_RR =  'https://apitech.apifacturacion.com/v2/apiguia';

   function CrearXMLFactura($nombrexml, $emisor, $cliente, $comprobante, $detalle, $sucursal)
   {
      $archivo = '';
      $doc = new \DOMDocument(); //clase que permite crear documento archivos, xml
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';
      header("Access-Control-Allow-Origin: *");
      $licenciaFile = '../LICENCIA';
      if(file_exists($licenciaFile)){
         $archivo = file($licenciaFile);
      }else{
         echo "NO ELIMINAR NI MODIFICAR EL ARCHIVO DE LICENCIA";
         exit();
      }  
 
      $licencia = $archivo;
      $datos = array(
         "emisor" => $emisor,
         "sucursal" => $sucursal,
         "cliente" => $cliente,
         'detalle' => $detalle,
         "comprobante" => $comprobante,
         "licencia" => $licencia
      );

      //  var_dump($datos);
      //  exit();
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => self::URL_API_FABO,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS  => http_build_query($datos),
         CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         // CURLOPT_CAINFO => dirname(__FILE__) . "/../api/cacert.pem" //Comentar si sube a un hosting 
         //para ejecutar los procesos de forma local en windows
         //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
      ));

      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      $xml = json_decode($response, true);
      //  var_dump($response);
      if (!empty($xml)) { //200: La comunicacion fue satisfactoria {
         if (strlen($xml) > 70) {
            $doc->loadXML($xml); //crear el xml en base a un texto
            $doc->save($nombrexml . '.XML'); //guarda el xml generado
         } else {
            echo $xml;
            exit();
         }
      } else {
         echo "PROBLEMAS DE CONEXIÓN";
         exit();
      }
   }

   function CrearXMLNotaCredito($nombrexml, $emisor, $cliente, $comprobante, $detalle, $sucursal)
   {


      $doc = new \DOMDocument(); //clase que permite crear documento archivos, xml
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';
      header("Access-Control-Allow-Origin: *");


      $datos = array(
         "emisor" => $emisor,
         "sucursal" => $sucursal,
         "cliente" => $cliente,
         'detalle' => $detalle,
         "comprobante" => $comprobante,

      );
      // var_dump($detalles);
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => self::URL_API_NOTA_CREDITO,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS  => http_build_query($datos),
         CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         // CURLOPT_CAINFO => dirname(__FILE__) . "/../api/cacert.pem" //Comentar si sube a un hosting 
         //para ejecutar los procesos de forma local en windows
         //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
      ));

      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      $xml = json_decode($response, true);
      // var_dump($response);
      if (!empty($xml)) { //200: La comunicacion fue satisfactoria {
         if (strlen($xml) > 70) {
            $doc->loadXML($xml); //crear el xml en base a un texto
            $doc->save($nombrexml . '.XML'); //guarda el xml generado
         } else {
            echo $xml;
            exit();
         }
      } else {
         echo "PROBLEMAS DE CONEXIÓN";
         exit();
      }
   }

   function CrearXMLNotaDebito($nombrexml, $emisor, $cliente, $comprobante, $detalle, $sucursal)
   {

      $doc = new \DOMDocument(); //clase que permite crear documento archivos, xml
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';
      header("Access-Control-Allow-Origin: *");


      $datos = array(
         "emisor" => $emisor,
         "sucursal" => $sucursal,
         "cliente" => $cliente,
         'detalle' => $detalle,
         "comprobante" => $comprobante,

      );
      // var_dump($detalles);
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => self::URL_API_NOTA_DEBITO,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS  => http_build_query($datos),
         CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         // CURLOPT_CAINFO => dirname(__FILE__) . "/../api/cacert.pem" //Comentar si sube a un hosting 
         //para ejecutar los procesos de forma local en windows
         //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
      ));

      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      $xml = json_decode($response, true);
      // var_dump($response);
      if (!empty($xml)) { //200: La comunicacion fue satisfactoria {
         if (strlen($xml) > 70) {
            $doc->loadXML($xml); //crear el xml en base a un texto
            $doc->save($nombrexml . '.XML'); //guarda el xml generado
         } else {
            echo $xml;
            exit();
         }
      } else {
         echo "PROBLEMAS DE CONEXIÓN";
         exit();
      }
   }

   function CrearXMLResumenDocumentos($emisor, $cabecera, $detalle, $nombrexml)
   {
      // var_dump($emisor);

      $doc = new \DOMDocument(); //clase que permite crear documento archivos, xml
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';
      header("Access-Control-Allow-Origin: *");


      $datos = array(
         "emisor" => $emisor,
         'detalle' => $detalle,
         "cabecera" => $cabecera,

      );
      // var_dump($detalles);
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => self::URL_API_RESUMEN,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS  => http_build_query($datos),
         CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         // CURLOPT_CAINFO => dirname(__FILE__) . "/../api/cacert.pem" //Comentar si sube a un hosting 
         //para ejecutar los procesos de forma local en windows
         //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
      ));

      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      $xml = json_decode($response, true);
      //  var_dump($response);
      if (!empty($xml)) { //200: La comunicacion fue satisfactoria {
         if (strlen($xml) > 70) {
            $doc->loadXML($xml); //crear el xml en base a un texto
            $doc->save($nombrexml . '.XML'); //guarda el xml generado
         } else {
            echo $xml;
            exit();
         }
      } else {
         echo "PROBLEMAS DE CONEXIÓN";
         exit();
      }
   }

   function CrearXmlBajaDocumentos($emisor, $cabecera, $detalle, $nombrexml)
   {


      $doc = new \DOMDocument(); //clase que permite crear documento archivos, xml
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';
      header("Access-Control-Allow-Origin: *");


      $datos = array(
         "emisor" => $emisor,
         'detalle' => $detalle,
         "cabecera" => $cabecera,

      );
      // var_dump($detalles);
      $curl = curl_init();
      curl_setopt_array($curl, array(
         CURLOPT_URL => self::URL_API_BAJA,
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_POST => true,
         CURLOPT_POSTFIELDS  => http_build_query($datos),
         CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         // CURLOPT_CAINFO => dirname(__FILE__) . "/../api/cacert.pem" //Comentar si sube a un hosting 
         //para ejecutar los procesos de forma local en windows
         //enlace de descarga del cacert.pem https://curl.haxx.se/docs/caextract.html
      ));

      $response = curl_exec($curl);
      $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
      curl_close($curl);

      $xml = json_decode($response, true);
      // var_dump($response);
      if (!empty($xml)) { //200: La comunicacion fue satisfactoria {
         if (strlen($xml) > 70) {
            $doc->loadXML($xml); //crear el xml en base a un texto
            $doc->save($nombrexml . '.XML'); //guarda el xml generado
         } else {
            echo $xml;
            exit();
         }
      } else {
         echo "PROBLEMAS DE CONEXIÓN";
         exit();
      }
   }



   function CrearXMLGuiaRemision($nombrexml, $emisor, $datosGuia, $detalle)
   {
      $doc = new \DOMDocument();
      $doc->formatOutput = FALSE;
      $doc->preserveWhiteSpace = TRUE;
      $doc->encoding = 'utf-8';

      $xml = '<?xml version="1.0" encoding="UTF-8"?>
      <DespatchAdvice xmlns="urn:oasis:names:specification:ubl:schema:xsd:DespatchAdvice-2" xmlns:ds="http://www.w3.org/2000/09/xmldsig#" xmlns:cac="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2" xmlns:cbc="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2" xmlns:ext="urn:oasis:names:specification:ubl:schema:xsd:CommonExtensionComponents-2">
         <ext:UBLExtensions>
		<ext:UBLExtension>
			<ext:ExtensionContent/>
		</ext:UBLExtension>
	</ext:UBLExtensions>
   <cbc:UBLVersionID>2.1</cbc:UBLVersionID>
   <cbc:CustomizationID>2.0</cbc:CustomizationID>
	<cbc:ID>' . $datosGuia['guia']['serie'] . '-' . $datosGuia['guia']['correlativo'] . '</cbc:ID>
	<cbc:IssueDate>' . $datosGuia['guia']['fechaEmision'] . '</cbc:IssueDate>
	<cbc:IssueTime>' . $datosGuia['guia']['horaEmision'] . '</cbc:IssueTime>
   <cbc:DespatchAdviceTypeCode listAgencyName="PE:SUNAT" listName="Tipo de Documento" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo01">' . $datosGuia['guia']['tipoDoc'] . '</cbc:DespatchAdviceTypeCode>';

      if ($datosGuia['guia']['observacion'] != '') :
         $xml .= '<cbc:Note><![CDATA[' . $datosGuia['guia']['observacion'] . ']]></cbc:Note>';
      endif;


      if ($datosGuia['relDoc']['nroDoc'] != '') :
         $xml .= '<cac:AdditionalDocumentReference>
		<cbc:ID>' . $datosGuia['relDoc']['nroDoc'] . '</cbc:ID>
      <cbc:DocumentTypeCode listAgencyName="PE:SUNAT" listName="Documento relacionado al transporte" listURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo61">' . $datosGuia['relDoc']['tipoDoc'] . '</cbc:DocumentTypeCode>
      <cbc:DocumentType>Factura</cbc:DocumentType>
      <cac:IssuerParty>
            <cac:PartyIdentification>
            <cbc:ID schemeID="6" schemeAgencyName="PE:SUNAT" schemeName="Documento de Identidad" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
            </cac:PartyIdentification>
      </cac:IssuerParty>
	</cac:AdditionalDocumentReference>';
      endif;
      $xml .= '
<cac:Signature>
<cbc:ID>' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
<cac:SignatoryParty>
<cac:PartyIdentification>
<cbc:ID>' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
</cac:PartyIdentification>
<cac:PartyName>
<cbc:Name><![CDATA[' . $datosGuia['remitente']['razonsocial'] . ']]></cbc:Name>
</cac:PartyName>
</cac:SignatoryParty>
<cac:DigitalSignatureAttachment>
<cac:ExternalReference>
<cbc:URI>#GREENTER-SIGN</cbc:URI>
</cac:ExternalReference>
</cac:DigitalSignatureAttachment>
</cac:Signature>
';
      $xml .= '<cac:DespatchSupplierParty>
		
		<cac:Party>
      <cac:PartyIdentification>
         <cbc:ID schemeID="6" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $datosGuia['remitente']['ruc'] . '</cbc:ID>
         </cac:PartyIdentification>
         <cac:PartyLegalEntity>
         <cbc:RegistrationName><![CDATA[' . $datosGuia['remitente']['razonsocial'] . ']]></cbc:RegistrationName>
         </cac:PartyLegalEntity>
			
		</cac:Party>
	</cac:DespatchSupplierParty>
   
	<cac:DeliveryCustomerParty>
		
		<cac:Party>
      <cac:PartyIdentification>
<cbc:ID schemeID="' . $datosGuia['destinatario']['tipoDoc'] . '" schemeName="Documento de Identidad" schemeAgencyName="PE:SUNAT" schemeURI="urn:pe:gob:sunat:cpe:see:gem:catalogos:catalogo06">' . $datosGuia['destinatario']['numDoc'] . '</cbc:ID>
</cac:PartyIdentification>
			<cac:PartyLegalEntity>
				<cbc:RegistrationName><![CDATA[' . $datosGuia['destinatario']['nombreRazon'] . ']]></cbc:RegistrationName>
			</cac:PartyLegalEntity>
		</cac:Party>
	</cac:DeliveryCustomerParty>';


      $xml .= '<cac:Shipment>
		<cbc:ID>SUNAT_Envio</cbc:ID>
		<cbc:HandlingCode>' . $datosGuia['datosEnvio']['codTraslado'] . '</cbc:HandlingCode>';

      if ($datosGuia['datosEnvio']['descTraslado'] != '') :

         $xml .= '<cbc:HandlingInstructions>' . $datosGuia['datosEnvio']['descTraslado'] . '</cbc:HandlingInstructions>';
      endif;

      $xml .= '<cbc:GrossWeightMeasure unitCode="' . $datosGuia['datosEnvio']['uniPesoTotal'] . '">' . number_format($datosGuia['datosEnvio']['pesoTotal'], 2, '.', '') . '</cbc:GrossWeightMeasure>';


      if ($datosGuia['datosEnvio']['tipoVehiculo'] != 'otros') :

         $xml .= '<cbc:SpecialInstructions>SUNAT_Envio_IndicadorTrasladoVehiculoM1L</cbc:SpecialInstructions>';

      endif;
      // if ($datosGuia['datosEnvio']['numBultos'] > 0) :
      //    $xml .= '<cbc:TotalTransportHandlingUnitQuantity>' . $datosGuia['datosEnvio']['numBultos'] . '</cbc:TotalTransportHandlingUnitQuantity>';
      // endif;

      $xml .= '
		<cac:ShipmentStage>
			<cbc:TransportModeCode>' . $datosGuia['datosEnvio']['modTraslado'] . '</cbc:TransportModeCode>
			<cac:TransitPeriod>
				<cbc:StartDate>' . $datosGuia['datosEnvio']['fechaTraslado'] . '</cbc:StartDate>
			</cac:TransitPeriod>';

      if ($datosGuia['datosEnvio']['modTraslado'] == '01'  && $datosGuia['datosEnvio']['tipoVehiculo'] == '') :
         $xml .=   '<cac:CarrierParty>
				<cac:PartyIdentification>
					<cbc:ID schemeID="' . $datosGuia['transportista']['tipoDoc'] . '">' . $datosGuia['transportista']['numDoc'] . '</cbc:ID>
				</cac:PartyIdentification>
            <cac:PartyLegalEntity>
               <cbc:RegistrationName>
               <![CDATA[' . $datosGuia['transportista']['nombreRazon'] . ']]>
               </cbc:RegistrationName>
               <cbc:CompanyID>0001</cbc:CompanyID>
               </cac:PartyLegalEntity>
			</cac:CarrierParty>';
      endif;


      if ($datosGuia['datosEnvio']['modTraslado'] == '02' && $datosGuia['datosEnvio']['tipoVehiculo'] == 'otros') :

         $xml .= '<cac:DriverPerson>
				<cbc:ID schemeID="' . $datosGuia['transportista']['tipoDocChofer'] . '">' . $datosGuia['transportista']['numDocChofer'] . '</cbc:ID>
            <cbc:FirstName>' . $datosGuia['transportista']['nombreRazon'] . '</cbc:FirstName>
            <cbc:FamilyName>' . $datosGuia['transportista']['apellidosRazon'] . '</cbc:FamilyName>
            <cbc:JobTitle>Principal</cbc:JobTitle>
            <cac:IdentityDocumentReference>
            <cbc:ID>' . $datosGuia['transportista']['numBreveteChofer'] . '</cbc:ID>
            </cac:IdentityDocumentReference>
			</cac:DriverPerson>';
      endif;

      $xml .=   '</cac:ShipmentStage>
      <cac:Delivery>
			<cac:DeliveryAddress>
				<cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">' . $datosGuia['llegada']['ubigeo'] . '</cbc:ID>
				 <cac:AddressLine>
               <cbc:Line>' . $datosGuia['llegada']['direccion'] . '</cbc:Line>
               </cac:AddressLine>
			</cac:DeliveryAddress>
         <cac:Despatch>
            <cac:DespatchAddress>
            <cbc:ID schemeAgencyName="PE:INEI" schemeName="Ubigeos">' . $datosGuia['partida']['ubigeo'] . '</cbc:ID>
            <cac:AddressLine>
            <cbc:Line>' . $datosGuia['partida']['direccion'] . '</cbc:Line>
            </cac:AddressLine>
            </cac:DespatchAddress>
            </cac:Despatch>
		</cac:Delivery>';
      if ($datosGuia['datosEnvio']['modTraslado'] == '02' && $datosGuia['datosEnvio']['tipoVehiculo'] == 'otros') :
         $xml .= '
         <cac:TransportHandlingUnit>
            <cac:TransportEquipment>
            <cbc:ID>' . $datosGuia['transportista']['placa'] . '</cbc:ID>
            
            </cac:TransportEquipment>
         </cac:TransportHandlingUnit>';

      endif;
      // if ($datosGuia['contenedor']['numContenedor'] != '') :
      //    $xml .= '<cac:TransportHandlingUnit>
      // 	<cbc:ID>' . $datosGuia['contenedor']['numContenedor'] . '</cbc:ID>
      //    </cac:TransportHandlingUnit>';
      // endif;


      // if ($datosGuia['puerto']['codPuerto'] != '') :
      //    $xml .= '<cac:FirstArrivalPortLocation>
      // 	<cbc:ID>' . $datosGuia['puerto']['codPuerto'] . '</cbc:ID>
      //    </cac:FirstArrivalPortLocation>';
      // endif;
      $xml .= '</cac:Shipment>';

      foreach ($detalle as $k => $v) :
         $xml .= '<cac:DespatchLine>
		<cbc:ID>' . $v['index'] . '</cbc:ID>
		<cbc:DeliveredQuantity unitCode="' . $v['unidad'] . '">' . $v['cantidad'] . '</cbc:DeliveredQuantity>
		<cac:OrderLineReference>
			<cbc:LineID>' . $v['index'] . '</cbc:LineID>
		</cac:OrderLineReference>
		<cac:Item>
      <cbc:Description>' . $v['descripcion'] . '</cbc:Description>

			<cac:SellersItemIdentification>
				<cbc:ID>' . $v['codigo'] . '</cbc:ID>
			</cac:SellersItemIdentification>';

         if ($v['codProdSunat'] != '') :
            $xml .= '<cac:CommodityClassification>
					<cbc:ItemClassificationCode listID="UNSPSC" listAgencyName="GS1 US" listName="Item Classification">' . $v['codProdSunat'] . '</cbc:ItemClassificationCode>
				</cac:CommodityClassification>';
         endif;
         $xml .= '</cac:Item>
	</cac:DespatchLine>';
      endforeach;

      $xml .= '</DespatchAdvice>';

      $doc->loadXML($xml);
      $doc->save($nombrexml . '.xml');
   }
}

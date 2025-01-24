<?php

namespace Modelos;

use Conect\Conexion;
use PDO;

class ModeloGuiaRemision
{
    // MOSTRAR TIPOS DE DOCUMENTOS DE IDENTIDAD
    public static function mdlMostrar($tabla, $item, $valor)
    {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla ORDER BY fecha_emision DESC");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }
    }

    public static function mdlBuscar($valor)
    {

        $stmt = Conexion::conectar()->prepare("SELECT g.*, c.ruc as cliente_ruc, c.razon_social as cliente_razon_social, c.direccion as cliente_direccion, c.ubigeo as cliente_ubigeo FROM guia AS g LEFT JOIN clientes AS c ON c.id = g.id_cliente WHERE g.id = " . $valor);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function mdlVerificarCorrelativo($params)
    {

        $stmt = Conexion::conectar()->prepare("SELECT id FROM guia WHERE id_sucursal = :sucursal AND serie = :serie AND correlativo = :correlativo");
        $stmt->bindParam(":sucursal", $params['sucursal'], PDO::PARAM_INT);
        $stmt->bindParam(":serie", $params['serie'], PDO::PARAM_INT);
        $stmt->bindParam(":correlativo", $params['correlativo'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function mdlMostrarDetalles($tabla, $item, $valor)
    {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE $item = :$item");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }
    }
    public static function mdlBuscarSerieCorrelativo($tabla, $valor, $idsucursal)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE (id_sucursal=$idsucursal) AND  serie_correlativo LIKE :valor LIMIT 10");
        $parametros = array(':valor' => '%' . $valor . '%');

        $stmt->execute($parametros);
        return $stmt->fetchall();
    }

    public static function mdlMostrarTraslado($tabla, $item, $valor)
    {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY codigo DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }
    }

    public static function mdlMostrarTiposVehiculo($tabla, $item, $valor)
    {

        if ($item != null) {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla  WHERE $item = :$item ORDER BY codigo DESC");
            $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

            $stmt->execute();
            return $stmt->fetch();
        } else {
            $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
            //$stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);    
            $stmt->execute();
            return $stmt->fetchall();
        }
    }

    public static function mdlMostrarUbigeo($item, $valor)
    {
        $stmt = Conexion::conectar()->prepare("SELECT de.name, pro.nombre_provincia, dis.nombre_distrito, dis.id AS ubigeo FROM ubigeo_departamento de INNER JOIN ubigeo_provincia pro ON de.id = pro.department_id INNER JOIN ubigeo_distrito dis ON pro.id = dis.province_id WHERE $item LIKE :$item LIMIT 15");

        // $stmt->bindParam(":".$item, $valor, PDO::PARAM_STR);
        $parametros = array(':' . $item => '%' . $valor . '%');
        if ($stmt->execute($parametros)) {
            return $stmt->fetchall();
        } else {
            return 'error';
        }
    }
    public static function mdlMostrarUbigeoSolo($item, $valor)
    {
        $stmt = Conexion::conectar()->prepare("SELECT de.name, pro.nombre_provincia, dis.nombre_distrito, dis.id FROM ubigeo_departamento de INNER JOIN ubigeo_provincia pro ON de.id = pro.department_id INNER JOIN ubigeo_distrito dis ON pro.id = dis.province_id WHERE dis.id = :$item");

        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);

        $stmt->execute();
        return $stmt->fetch();
    }
    // OBTENER EL ULTIMO ID COMPROBANTE
    public static function mdlObtenerUltimoComprobanteIdGuia()
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM guia ORDER BY id DESC LIMIT 1");

        $stmt->execute();

        return $stmt->fetch();
    }
    // GUARDAR DETALLES VENTA CARRITO EN LA BD
    public static function mdlInsertarDetallesGuia($idGuia, $detalle)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO guia_detalle(id_guia, indexg, id_producto, codSunat, cantidad, peso, bultos, PO, color, partida, adicional, servicio, caracteristica)
        VALUES (:id_guia, :indexg, :id_producto, :codSunat, :cantidad, :peso, :bultos, :PO, :color, :partida, :adicional, :servicio, :caracteristica)");
        foreach ($detalle as $k => $v) {
            $stmt->bindParam(":id_guia", $idGuia, PDO::PARAM_INT);
            $stmt->bindParam(":indexg", $v['index'], PDO::PARAM_INT);
            $stmt->bindParam(":id_producto", $v['id_producto'], PDO::PARAM_INT);
            $stmt->bindParam(":codSunat", $v['codProdSunat'], PDO::PARAM_STR);
            $stmt->bindParam(":cantidad", $v['cantidad'], PDO::PARAM_STR);
            $stmt->bindParam(":peso", $v['peso'], PDO::PARAM_STR);
            $stmt->bindParam(":bultos", $v['bultos'], PDO::PARAM_STR);
            $stmt->bindParam(":PO", $v['PO'], PDO::PARAM_STR);
            $stmt->bindParam(":color", $v['color'], PDO::PARAM_STR);
            $stmt->bindParam(":partida", $v['partida'], PDO::PARAM_STR);
            $stmt->bindParam(":adicional", $v['adicional'], PDO::PARAM_STR);
            $stmt->bindParam(":servicio", $v['servicio'], PDO::PARAM_STR);
            $stmt->bindParam(":caracteristica", $v['caracteristica'], PDO::PARAM_STR);
            $stmt->execute();
        }
    }
    public static function mdlGuardarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket)
    {
        $transportistaNom = (string) $datosGuia['transportista']['nombreRazon'] . ' ' . $datosGuia['transportista']['apellidosRazon'];
        $stmt = Conexion::conectar()->prepare("INSERT INTO guia(id, id_sucursal, id_cliente, id_conductor, cli_tipodoc, tipodoc, serie, correlativo, fecha_emision, hora, comp_ref, baja_numdoc, baja_tipodoc, rel_numdoc, rel_tipodoc, terceros_tipodoc, terceros_numdoc, terceros_nombrerazon, cod_traslado, uniPeso, pesoTotal, numBultos, indTransbordo, modTraslado, fechaTraslado, transp_tipoDoc, transp_numDoc, transp_nombreRazon, transp_placa, tipoDocChofer, numDocChofer, observacion, ubigeoPartida, direccionPartida, ubigeoLlegada, direccionLlegada, feestado, fecodigoerror, femensajesunat, nombrexml, xmlbase64, cdrbase64, tipovehiculo, descripcion, ticket, series, borrador) VALUES (NULL, :id_sucursal, :id_cliente, :id_conductor, :cli_tipodoc, :tipodoc, :serie, :correlativo, :fecha_emision, :hora, :comp_ref, :baja_numdoc, :baja_tipodoc, :rel_numdoc, :rel_tipodoc, :terceros_tipodoc, :terceros_numdoc, :terceros_nombrerazon, :cod_traslado, :uniPeso, :pesoTotal, :numBultos, :indTransbordo, :modTraslado, :fechaTraslado, :transp_tipoDoc, :transp_numDoc, :transp_nombreRazon, :transp_placa, :tipoDocChofer, :numDocChofer, :observacion, :ubigeoPartida, :direccionPartida, :ubigeoLlegada, :direccionLlegada, :feestado, :fecodigoerror, :femensajesunat, :nombrexml, :xmlbase64, :cdrbase64, :tipovehiculo, :descripcion, :ticket, :series, :borrador)");
        $borrador = $datosGuia['borrador'];
        $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
        $stmt->bindParam(":id_cliente", $datosGuia['id_cliente'], PDO::PARAM_INT);
        $stmt->bindParam(":id_conductor", $datosGuia['id_conductor'], PDO::PARAM_INT);
        $stmt->bindParam(":cli_tipodoc", $datosGuia['destinatario']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":tipodoc", $datosGuia['guia']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":serie", $datosGuia['guia']['serie'], PDO::PARAM_STR);
        $stmt->bindParam(":correlativo", $datosGuia['guia']['correlativo'], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_emision", $datosGuia['guia']['fechaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha_emision", $datosGuia['guia']['fechaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":hora", $datosGuia['guia']['horaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":comp_ref", $datosGuia['comp_ref'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_numdoc", $datosGuia['docBaja']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_tipodoc", $datosGuia['docBaja']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_numdoc", $datosGuia['relDoc']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_tipodoc", $datosGuia['relDoc']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_tipodoc", $datosGuia['terceros']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_numdoc", $datosGuia['terceros']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_nombrerazon", $datosGuia['terceros']['nombreRazon'], PDO::PARAM_STR);
        $stmt->bindParam(":cod_traslado", $datosGuia['datosEnvio']['codTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":uniPeso", $datosGuia['datosEnvio']['uniPesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":pesoTotal", $datosGuia['datosEnvio']['pesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":numBultos", $datosGuia['datosEnvio']['numBultos'], PDO::PARAM_STR);
        $stmt->bindParam(":indTransbordo", $datosGuia['datosEnvio']['indTransbordo'], PDO::PARAM_STR);
        $stmt->bindParam(":modTraslado", $datosGuia['datosEnvio']['modTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":fechaTraslado", $datosGuia['datosEnvio']['fechaTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_tipoDoc", $datosGuia['transportista']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_numDoc", $datosGuia['transportista']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_nombreRazon", $transportistaNom, PDO::PARAM_STR);
        $stmt->bindParam(":transp_placa", $datosGuia['transportista']['placa'], PDO::PARAM_STR);
        $stmt->bindParam(":tipoDocChofer", $datosGuia['transportista']['tipoDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":numDocChofer", $datosGuia['transportista']['numDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoPartida", $datosGuia['partida']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionPartida", $datosGuia['partida']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoLlegada", $datosGuia['llegada']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionLlegada", $datosGuia['llegada']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datosGuia['guia']['observacion'], PDO::PARAM_STR);
        $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
        $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
        $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
        $stmt->bindParam(":nombrexml", $codigosSunat['nombrexml'], PDO::PARAM_STR);
        $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
        $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);
        $stmt->bindParam(":tipovehiculo", $datosGuia['datosEnvio']['tipoVehiculo'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datosGuia['guia']['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":ticket", $ticket, PDO::PARAM_STR);
        $stmt->bindParam(":series", $datosGuia['guia']['series'], PDO::PARAM_STR);
        $stmt->bindParam(":borrador", $borrador, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }

        $stmt->close();
        $stmt = null;
    }

    // COMPLETAR AXELL
    public static function mdlEditarGuia($id_sucursal, $datosGuia, $codigosSunat, $ticket, $idGuia)
    {
        $transportistaNom = (string) $datosGuia['transportista']['nombreRazon'] . ' ' . $datosGuia['transportista']['apellidosRazon'];
        $stmt = Conexion::conectar()->prepare("UPDATE guia SET id_sucursal = :id_sucursal, id_cliente = :id_cliente, id_conductor = :id_conductor, cli_tipodoc = :cli_tipodoc, tipodoc = :tipodoc, serie = :serie, correlativo = :correlativo, fecha_emision = :fecha_emision, hora = :hora, comp_ref = :comp_ref, baja_numdoc = :baja_numdoc, baja_tipodoc = :baja_tipodoc, rel_numdoc = :rel_numdoc, rel_tipodoc = :rel_tipodoc, terceros_tipodoc = :terceros_tipodoc, terceros_numdoc = :terceros_numdoc, terceros_nombrerazon = :terceros_nombrerazon, cod_traslado = :cod_traslado, uniPeso = :uniPeso, pesoTotal = :pesoTotal, numBultos = :numBultos, indTransbordo = :indTransbordo, modTraslado = :modTraslado, fechaTraslado = :fechaTraslado, transp_tipoDoc = :transp_tipoDoc, transp_numDoc = :transp_numDoc, transp_nombreRazon = :transp_nombreRazon, transp_placa = :transp_placa, tipoDocChofer = :tipoDocChofer, numDocChofer = :numDocChofer, observacion = :observacion, ubigeoPartida = :ubigeoPartida, direccionPartida = :direccionPartida, ubigeoLlegada = :ubigeoLlegada, direccionLlegada = :direccionLlegada, descripcion = :descripcion, series = :series, feestado = :feestado, fecodigoerror = :fecodigoerror, femensajesunat = :femensajesunat, nombrexml = :nombrexml, xmlbase64 = :xmlbase64, cdrbase64 = :cdrbase64, tipovehiculo = :tipovehiculo, ticket = :ticket, borrador = :borrador WHERE id = :id_guia");
        $borrador = $datosGuia['borrador'];
        $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
        $stmt->bindParam(":id_cliente", $datosGuia['id_cliente'], PDO::PARAM_INT);
        $stmt->bindParam(":id_conductor", $datosGuia['id_conductor'], PDO::PARAM_INT);
        $stmt->bindParam(":cli_tipodoc", $datosGuia['destinatario']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":tipodoc", $datosGuia['guia']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":serie", $datosGuia['guia']['serie'], PDO::PARAM_STR);
        $stmt->bindParam(":correlativo", $datosGuia['guia']['correlativo'], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_emision", $datosGuia['guia']['fechaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":hora", $datosGuia['guia']['horaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":comp_ref", $datosGuia['comp_ref'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_numdoc", $datosGuia['docBaja']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_tipodoc", $datosGuia['docBaja']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_numdoc", $datosGuia['relDoc']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_tipodoc", $datosGuia['relDoc']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_tipodoc", $datosGuia['terceros']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_numdoc", $datosGuia['terceros']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_nombrerazon", $datosGuia['terceros']['nombreRazon'], PDO::PARAM_STR);
        $stmt->bindParam(":cod_traslado", $datosGuia['datosEnvio']['codTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":uniPeso", $datosGuia['datosEnvio']['uniPesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":pesoTotal", $datosGuia['datosEnvio']['pesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":numBultos", $datosGuia['datosEnvio']['numBultos'], PDO::PARAM_STR);
        $stmt->bindParam(":indTransbordo", $datosGuia['datosEnvio']['indTransbordo'], PDO::PARAM_STR);
        $stmt->bindParam(":modTraslado", $datosGuia['datosEnvio']['modTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":fechaTraslado", $datosGuia['datosEnvio']['fechaTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_tipoDoc", $datosGuia['transportista']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_numDoc", $datosGuia['transportista']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_nombreRazon", $transportistaNom, PDO::PARAM_STR);
        $stmt->bindParam(":transp_placa", $datosGuia['transportista']['placa'], PDO::PARAM_STR);
        $stmt->bindParam(":tipoDocChofer", $datosGuia['transportista']['tipoDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":numDocChofer", $datosGuia['transportista']['numDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoPartida", $datosGuia['partida']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionPartida", $datosGuia['partida']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoLlegada", $datosGuia['llegada']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionLlegada", $datosGuia['llegada']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datosGuia['guia']['observacion'], PDO::PARAM_STR);
        $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
        $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
        $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
        $stmt->bindParam(":nombrexml", $codigosSunat['nombrexml'], PDO::PARAM_STR);
        $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
        $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);
        $stmt->bindParam(":tipovehiculo", $datosGuia['datosEnvio']['tipoVehiculo'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datosGuia['guia']['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":ticket", $ticket, PDO::PARAM_STR);
        $stmt->bindParam(":series", $datosGuia['guia']['series'], PDO::PARAM_STR);
        $stmt->bindParam(":borrador", $borrador, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlGuardarGuiaSinEnviarSunat($id_sucursal, $datosGuia)
    {
        $transportistaNom = (string) $datosGuia['transportista']['nombreRazon'] . ' ' . $datosGuia['transportista']['apellidosRazon'];
        $stmt = Conexion::conectar()->prepare("INSERT INTO guia(id, id_sucursal, id_cliente, id_conductor, cli_tipodoc, tipodoc, serie, correlativo, fecha_emision, hora, comp_ref, baja_numdoc, baja_tipodoc, rel_numdoc, rel_tipodoc, terceros_tipodoc, terceros_numdoc, terceros_nombrerazon, cod_traslado, uniPeso, pesoTotal, numBultos, indTransbordo, modTraslado, fechaTraslado, transp_tipoDoc, transp_numDoc, transp_nombreRazon, transp_placa, tipoDocChofer, numDocChofer, observacion, ubigeoPartida, direccionPartida, ubigeoLlegada, direccionLlegada, tipovehiculo, descripcion, series, nombrexml, borrador) VALUES (NULL, :id_sucursal, :id_cliente, :id_conductor, :cli_tipodoc, :tipodoc, :serie, :correlativo, :fecha_emision, :hora, :comp_ref, :baja_numdoc, :baja_tipodoc, :rel_numdoc, :rel_tipodoc, :terceros_tipodoc, :terceros_numdoc, :terceros_nombrerazon, :cod_traslado, :uniPeso, :pesoTotal, :numBultos, :indTransbordo, :modTraslado, :fechaTraslado, :transp_tipoDoc, :transp_numDoc, :transp_nombreRazon, :transp_placa, :tipoDocChofer, :numDocChofer, :observacion, :ubigeoPartida, :direccionPartida, :ubigeoLlegada, :direccionLlegada, :tipovehiculo, :descripcion, :series, :nombrexml, :borrador)");
        $borrador = 'S';
        $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
        $stmt->bindParam(":id_cliente", $datosGuia['id_cliente'], PDO::PARAM_INT);
        $stmt->bindParam(":id_conductor", $datosGuia['id_conductor'], PDO::PARAM_INT);
        $stmt->bindParam(":cli_tipodoc", $datosGuia['destinatario']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":tipodoc", $datosGuia['guia']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":serie", $datosGuia['guia']['serie'], PDO::PARAM_STR);
        $stmt->bindParam(":correlativo", $datosGuia['guia']['correlativo'], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_emision", $datosGuia['guia']['fechaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":hora", $datosGuia['guia']['horaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":comp_ref", $datosGuia['comp_ref'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_numdoc", $datosGuia['docBaja']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_tipodoc", $datosGuia['docBaja']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_numdoc", $datosGuia['relDoc']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_tipodoc", $datosGuia['relDoc']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_tipodoc", $datosGuia['terceros']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_numdoc", $datosGuia['terceros']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_nombrerazon", $datosGuia['terceros']['nombreRazon'], PDO::PARAM_STR);
        $stmt->bindParam(":cod_traslado", $datosGuia['datosEnvio']['codTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":uniPeso", $datosGuia['datosEnvio']['uniPesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":pesoTotal", $datosGuia['datosEnvio']['pesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":numBultos", $datosGuia['datosEnvio']['numBultos'], PDO::PARAM_STR);
        $stmt->bindParam(":indTransbordo", $datosGuia['datosEnvio']['indTransbordo'], PDO::PARAM_STR);
        $stmt->bindParam(":modTraslado", $datosGuia['datosEnvio']['modTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":fechaTraslado", $datosGuia['datosEnvio']['fechaTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_tipoDoc", $datosGuia['transportista']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_numDoc", $datosGuia['transportista']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_nombreRazon", $transportistaNom, PDO::PARAM_STR);
        $stmt->bindParam(":transp_placa", $datosGuia['transportista']['placa'], PDO::PARAM_STR);
        $stmt->bindParam(":tipoDocChofer", $datosGuia['transportista']['tipoDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":numDocChofer", $datosGuia['transportista']['numDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoPartida", $datosGuia['partida']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionPartida", $datosGuia['partida']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoLlegada", $datosGuia['llegada']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionLlegada", $datosGuia['llegada']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datosGuia['guia']['observacion'], PDO::PARAM_STR);
        /* $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
        $stmt->bindParam(":fecodigoerror", $codigosSunat['fecodigoerror'], PDO::PARAM_STR);
        $stmt->bindParam(":femensajesunat", $codigosSunat['femensajesunat'], PDO::PARAM_STR);
        $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR); */
        $stmt->bindParam(":tipovehiculo", $datosGuia['datosEnvio']['tipoVehiculo'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datosGuia['guia']['descripcion'], PDO::PARAM_STR);
        /* $stmt->bindParam(":ticket", $ticket, PDO::PARAM_STR); */
        $stmt->bindParam(":series", $datosGuia['guia']['series'], PDO::PARAM_STR);
        $stmt->bindParam(":nombrexml", $datosGuia['nombrexml'], PDO::PARAM_STR);
        $stmt->bindParam(":borrador", $borrador, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }

        $stmt->close();
        $stmt = null;
    }

    public static function mdlEditarGuiaSinEnviarSunat($id_sucursal, $datosGuia, $idGuia)
    {
        $transportistaNom = (string) $datosGuia['transportista']['nombreRazon'] . ' ' . $datosGuia['transportista']['apellidosRazon'];
        $stmt = Conexion::conectar()->prepare("UPDATE guia SET id_sucursal = :id_sucursal, id_cliente = :id_cliente, id_conductor = :id_conductor, cli_tipodoc = :cli_tipodoc, tipodoc = :tipodoc, serie = :serie, correlativo = :correlativo, fecha_emision = :fecha_emision, hora = :hora, comp_ref = :comp_ref, baja_numdoc = :baja_numdoc, baja_tipodoc = :baja_tipodoc, rel_numdoc = :rel_numdoc, rel_tipodoc = :rel_tipodoc, terceros_tipodoc = :terceros_tipodoc, terceros_numdoc = :terceros_numdoc, terceros_nombrerazon = :terceros_nombrerazon, cod_traslado = :cod_traslado, uniPeso = :uniPeso, pesoTotal = :pesoTotal, numBultos = :numBultos, indTransbordo = :indTransbordo, modTraslado = :modTraslado, fechaTraslado = :fechaTraslado, transp_tipoDoc = :transp_tipoDoc, transp_numDoc = :transp_numDoc, transp_nombreRazon = :transp_nombreRazon, transp_placa = :transp_placa, tipoDocChofer = :tipoDocChofer, numDocChofer = :numDocChofer, observacion = :observacion, ubigeoPartida = :ubigeoPartida, direccionPartida = :direccionPartida, ubigeoLlegada = :ubigeoLlegada, direccionLlegada = :direccionLlegada, tipovehiculo = :tipovehiculo, descripcion = :descripcion, series = :series, nombrexml = :nombrexml, borrador = :borrador WHERE id = :id_guia");
        $borrador = 'S';
        $stmt->bindParam(":id_sucursal", $id_sucursal, PDO::PARAM_INT);
        $stmt->bindParam(":id_cliente", $datosGuia['id_cliente'], PDO::PARAM_INT);
        $stmt->bindParam(":id_conductor", $datosGuia['id_conductor'], PDO::PARAM_INT);
        $stmt->bindParam(":cli_tipodoc", $datosGuia['destinatario']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":tipodoc", $datosGuia['guia']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":serie", $datosGuia['guia']['serie'], PDO::PARAM_STR);
        $stmt->bindParam(":correlativo", $datosGuia['guia']['correlativo'], PDO::PARAM_INT);
        $stmt->bindParam(":fecha_emision", $datosGuia['guia']['fechaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":hora", $datosGuia['guia']['horaEmision'], PDO::PARAM_STR);
        $stmt->bindParam(":comp_ref", $datosGuia['comp_ref'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_numdoc", $datosGuia['docBaja']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":baja_tipodoc", $datosGuia['docBaja']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_numdoc", $datosGuia['relDoc']['nroDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":rel_tipodoc", $datosGuia['relDoc']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_tipodoc", $datosGuia['terceros']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_numdoc", $datosGuia['terceros']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":terceros_nombrerazon", $datosGuia['terceros']['nombreRazon'], PDO::PARAM_STR);
        $stmt->bindParam(":cod_traslado", $datosGuia['datosEnvio']['codTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":uniPeso", $datosGuia['datosEnvio']['uniPesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":pesoTotal", $datosGuia['datosEnvio']['pesoTotal'], PDO::PARAM_STR);
        $stmt->bindParam(":numBultos", $datosGuia['datosEnvio']['numBultos'], PDO::PARAM_STR);
        $stmt->bindParam(":indTransbordo", $datosGuia['datosEnvio']['indTransbordo'], PDO::PARAM_STR);
        $stmt->bindParam(":modTraslado", $datosGuia['datosEnvio']['modTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":fechaTraslado", $datosGuia['datosEnvio']['fechaTraslado'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_tipoDoc", $datosGuia['transportista']['tipoDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_numDoc", $datosGuia['transportista']['numDoc'], PDO::PARAM_STR);
        $stmt->bindParam(":transp_nombreRazon", $transportistaNom, PDO::PARAM_STR);
        $stmt->bindParam(":transp_placa", $datosGuia['transportista']['placa'], PDO::PARAM_STR);
        $stmt->bindParam(":tipoDocChofer", $datosGuia['transportista']['tipoDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":numDocChofer", $datosGuia['transportista']['numDocChofer'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoPartida", $datosGuia['partida']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionPartida", $datosGuia['partida']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":ubigeoLlegada", $datosGuia['llegada']['ubigeo'], PDO::PARAM_STR);
        $stmt->bindParam(":direccionLlegada", $datosGuia['llegada']['direccion'], PDO::PARAM_STR);
        $stmt->bindParam(":observacion", $datosGuia['guia']['observacion'], PDO::PARAM_STR);
        $stmt->bindParam(":tipovehiculo", $datosGuia['datosEnvio']['tipoVehiculo'], PDO::PARAM_STR);
        $stmt->bindParam(":descripcion", $datosGuia['guia']['descripcion'], PDO::PARAM_STR);
        $stmt->bindParam(":series", $datosGuia['guia']['series'], PDO::PARAM_STR);
        $stmt->bindParam(":nombrexml", $datosGuia['nombrexml'], PDO::PARAM_STR);
        $stmt->bindParam(":id_guia", $idGuia, PDO::PARAM_INT);
        $stmt->bindParam(":borrador", $borrador, PDO::PARAM_STR);

        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }

        $stmt->close();
        $stmt = null;
    }

    // MOSTRAR DETALLES Y PRODUCTOS DE LA GUIA
    public static function mdlMostrarDetallesProductosGuia($item, $valor)
    {
        $stmt = Conexion::conectar()->prepare("SELECT t1.id_producto, t1.cantidad, t2.descripcion, t2.id, t2.codunidad, t2.codigo, t2.id, t1.cantidad, t1.peso, t1.bultos, t1.color, t1.PO, t1.partida, t1.adicional, t1.servicio, t1.caracteristica, c.categoria as categoria_des FROM guia_detalle t1 INNER JOIN productos t2 ON t1.id_producto=t2.id LEFT JOIN categorias c ON c.id = t2.id_categoria WHERE $item=:$item ORDER BY t1.indexg");
        $stmt->bindParam(":" . $item, $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchall();
        $stmt->close();
        $stmt = null;
    }

    public static function mdlDesactivarRetorno($tabla, $datos)
    {

        $stmt = Conexion::conectar();
        $stmt = $stmt->prepare("UPDATE $tabla SET retorno=:retorno WHERE id = :id");

        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        $stmt->bindParam(":retorno", $datos['retorno'], PDO::PARAM_STR);


        if ($stmt->execute()) {

            return "ok";
        } else {

            return "error";
        }

        $stmt->close();
        $stmt = null;
    }
    public static function mdlListarGuias()
    {
        $content =  "<tbody class='body-listaguias'></tbody>";
        return $content;
    }
    public static function mdlListarGuiasRetorno()
    {
        $content =  "<tbody class='body-listaguias-retorno'></tbody>";
        return $content;
    }

    // GUARDAR VENTA CARRITO EN LA BD
    public static function mdlActualizarCDR($idGuia, $codigosSunat)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE guia SET feestado = :feestado WHERE id = :id");
        $stmt->bindParam(":id", $idGuia, PDO::PARAM_INT);
        $stmt->bindParam(":feestado", $codigosSunat['feestado'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlActualizarCDRName($idGuia, $codigosSunat)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE guia SET cdrbase64 = :cdrbase64, xmlbase64 = :xmlbase64 WHERE id = :id");
        $stmt->bindParam(":id", $idGuia, PDO::PARAM_INT);
        $stmt->bindParam(":cdrbase64", $codigosSunat['cdrbase64'], PDO::PARAM_STR);
        $stmt->bindParam(":xmlbase64", $codigosSunat['xmlbase64'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return   'ok';
        } else {
            return  'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlEliminarGuia($idGuia)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM guia WHERE id = :id");
        $stmt->bindParam(":id", $idGuia, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlAnularGuia($idGuia)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE guia SET anulado = 'S' WHERE id = :id");
        $stmt->bindParam(":id", $idGuia, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlEliminarGuiaDetalle($idGuia)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM guia_detalle WHERE id_guia = :id");
        $stmt->bindParam(":id", $idGuia, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlActualizarGuiaEstado($idGuia, $estado)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE guia SET borrador = 'N', feestado = :feestado WHERE id = :id");
        $stmt->bindParam(":id", $idGuia, PDO::PARAM_INT);
        $stmt->bindParam(":feestado", $estado, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }

    public static function mdlActualizarGuiaTicket($idGuia, $ticket)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE guia SET ticket = :ticket WHERE id = :id");
        $stmt->bindParam(":id", $idGuia, PDO::PARAM_INT);
        $stmt->bindParam(":ticket", $ticket, PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
        $stmt->close();
        $stmt = null;
    }
}

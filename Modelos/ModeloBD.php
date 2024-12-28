<?php

namespace Modelos;

use Conect\Conexion;
use PDO;

class ModeloBD
{

    public static function mdlCrearTablas()
    {
        $success = array();
        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'inventario'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `inventario` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `movimiento` varchar(100) NOT NULL,
                `accion` varchar(20) NOT NULL,
                `cantidad` int(11) NOT NULL,
                `stock_actual` int(11) NOT NULL,
                `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `id_producto` int(11) NOT NULL,
                `id_usuario` int(11) NOT NULL,
                INDEX (id_producto),
                
                INDEX (id_usuario)

              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();

            array_push($success,  'Se agrego la tabla inventario');
        };

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'roles'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `roles` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `rol` varchar(180) DEFAULT NULL

              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `roles` (`id`, `rol`) VALUES
            (1, 'Administrador'),
            (2, 'Especial')");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla roles');
        };
        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'rol_acceso'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `rol_acceso` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_rol` int(11) NOT NULL,
                `nombreacceso` varchar(250) DEFAULT NULL,
                `linkacceso` varchar(300) DEFAULT NULL,
                `activo` enum('s','n') NOT NULL DEFAULT 'n',
                INDEX (`id_rol`)

              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();

            $stmt = Conexion::conectar()->prepare("INSERT INTO `rol_acceso` (`id`, `id_rol`, `nombreacceso`, `linkacceso`, `activo`) VALUES
            (1, 1, 'INICIO', 'inicio', 's'),
            (2, 1, 'USUARIOS', 'usuarios', 's'),
            (3, 1, 'CATEGORIAS', 'categorias', 's'),
            (4, 1, 'PRODUCTOS', 'productos', 's'),
            (5, 1, 'CLIENTES', 'clientes', 's'),
            (6, 1, 'VENTAS', 'ventas', 's'),
            (7, 1, 'CREAR FACTURA', 'crear-factura', 's'),
            (8, 1, 'CREAR BOLETA', 'crear-boleta', 's'),
            (9, 1, 'CREAR NOTA', 'crear-nota', 's'),
            (10, 1, 'NOTA CREDITO', 'nota-credito', 's'),
            (11, 1, 'NOTA DEBITO', 'nota-debito', 's'),
            (12, 1, 'CREAR COTIZACION', 'crear-cotizacion', 's'),
            (13, 1, 'CREAR GUIA', 'crear-guia', 's'),
            (14, 1, 'NUEVA COMPRA', 'nueva-compra', 's'),
            (15, 1, 'REPORTES', 'reportes', 's'),
            (16, 1, 'EMPRESA', 'empresa', 's'),
            (17, 1, 'RESUMEN DIARIO', 'resumen-diario', 's'),
            (18, 1, 'REPORTE VENTAS', 'reporte-ventas', 's'),
            (19, 1, 'REPORTE COMPRAS', 'reporte-compras', 's'),
            (20, 1, 'VER GUIAS', 'ver-guias', 's'),
            (21, 1, 'LISTAR COTIZACIONES', 'listar-cotizaciones', 's'),
            (22, 1, 'CONSULTA COMPROBANTE', 'consulta-comprobante', 's'),
            (23, 1, 'UNIDAD MEDIDA', 'unidad-medida', 's'),
            (24, 1, 'ROLES', 'roles', 's'),
            (25, 1, 'INVENTARIO', 'inventario', 's'),
            (26, 1, 'KARDEX', 'kardex', 's'),
            (27, 1, 'LINK DE ACCESO', 'accesos', 's')");
            $stmt->execute();

            array_push($success,  'Se agrego la tabla rol_acceso');
        };



        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'guia'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `guia` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_cliente` int(11) DEFAULT NULL,
                `cli_tipodoc` int(11) DEFAULT NULL,
                `tipodoc` varchar(4) DEFAULT NULL,
                `serie` varchar(15) NOT NULL,
                `correlativo` int(11) NOT NULL,
                `fecha_emision` date DEFAULT NULL,
                `hora` varchar(15) DEFAULT NULL,
                `comp_ref` varchar(15) DEFAULT NULL,
                `baja_numdoc` varchar(50) DEFAULT NULL,
                `baja_tipodoc` varchar(6) DEFAULT NULL,
                `rel_numdoc` varchar(100) DEFAULT NULL,
                `rel_tipodoc` varchar(6) DEFAULT NULL,
                `terceros_tipodoc` varchar(6) DEFAULT NULL,
                `terceros_numdoc` varchar(15) DEFAULT NULL,
                `terceros_nombrerazon` varchar(180) DEFAULT NULL,
                `cod_traslado` varchar(5) DEFAULT NULL,
                `uniPeso` varchar(5) DEFAULT NULL,
                `pesoTotal` float DEFAULT NULL,
                `numBultos` int(11) DEFAULT NULL,
                `indTransbordo` varchar(20) DEFAULT NULL,
                `modTraslado` varchar(5) DEFAULT NULL,
                `fechaTraslado` date DEFAULT NULL,
                `transp_tipoDoc` varchar(6) DEFAULT NULL,
                `transp_numDoc` varchar(15) DEFAULT NULL,
                `transp_nombreRazon` varchar(250) DEFAULT NULL,
                `transp_placa` varchar(15) DEFAULT NULL,
                `tipoDocChofer` varchar(6) DEFAULT NULL,
                `numDocChofer` varchar(15) DEFAULT NULL,
                `ubigeoPartida` varchar(15) DEFAULT NULL,
                `direccionPartida` varchar(300) DEFAULT NULL,
                `ubigeoLlegada` varchar(15) DEFAULT NULL,
                `direccionLlegada` varchar(300) DEFAULT NULL,
                `observacion` text DEFAULT NULL,
                `feestado` char(1) DEFAULT NULL,
                `fecodigoerror` varchar(10) DEFAULT NULL,
                `femensajesunat` text DEFAULT NULL,
                `nombrexml` varchar(50) DEFAULT NULL,
                `xmlbase64` text DEFAULT NULL,
                `cdrbase64` text DEFAULT NULL,
                `tipovehiculo` varchar(8) DEFAULT NULL,
                INDEX (`id_cliente`) 
                
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla guia');
        }
        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'guia_detalle'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `guia_detalle` (
                `id` int(11)  NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `indexg` int(11) DEFAULT NULL,
                `id_guia` int(11) NOT NULL,
                `id_producto` int(11) NOT NULL,
                `codSunat` varchar(30) DEFAULT NULL,
                `cantidad` decimal(11,2) DEFAULT 0.00,
                INDEX (id_guia),
                INDEX (id_producto)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla guia_detalle');
        }


        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'cotizaciones'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `cotizaciones` (
                `id` int(11) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
                `id_sucursal` int(11) DEFAULT NULL,
                `tipocomp` char(2) DEFAULT NULL,
                `idserie` int(11) DEFAULT NULL,
                `serie` char(4) DEFAULT NULL,
                `correlativo` int(11) DEFAULT NULL,
                `serie_correlativo` varchar(15) DEFAULT NULL,
                `fecha_emision` date DEFAULT NULL,
                `fechahora` datetime DEFAULT NULL,
                `codmoneda` char(3) DEFAULT NULL,
                `tipocambio` float DEFAULT NULL,
                `metodopago` varchar(4) DEFAULT NULL,
                `comentario` text DEFAULT NULL,
                `op_gravadas` decimal(11,2) DEFAULT NULL,
                `op_exoneradas` decimal(11,2) DEFAULT NULL,
                `op_inafectas` decimal(11,2) DEFAULT NULL,
                `op_gratuitas` decimal(11,2) DEFAULT NULL,
                `igv_op` decimal(11,2) DEFAULT 0.00,
                `descuento_factor` decimal(11,5) DEFAULT NULL,
                `descuento` decimal(11,2) DEFAULT NULL,
                `icbper` decimal(11,2) DEFAULT 0.00,
                `igv` decimal(11,2) DEFAULT NULL,
                `subtotal` decimal(11,2) DEFAULT 0.00,
                `total` decimal(11,2) DEFAULT NULL,
                `codcliente` int(11) DEFAULT NULL,
                `codvendedor` int(11) DEFAULT NULL,
                `tipodoc` char(1) DEFAULT NULL,
                `feestado` char(1) DEFAULT NULL,
                `fecodigoerror` varchar(10) DEFAULT NULL,
                `femensajesunat` text DEFAULT NULL,
                `nombrexml` varchar(50) DEFAULT NULL,
                `xmlbase64` text DEFAULT NULL,
                `cdrbase64` text DEFAULT NULL,
                `anulado` enum('n','s') NOT NULL DEFAULT 'n',
                `resumen` enum('s','n') DEFAULT 'n',
                `idbaja` int(11) DEFAULT NULL,
                `id_nc` int(11) DEFAULT NULL,
                `id_nd` int(11) DEFAULT NULL,
                `bienesSelva` varchar(4) DEFAULT NULL,
                `serviciosSelva` varchar(4) DEFAULT NULL,
                INDEX (`id_sucursal`),
                INDEX (`codcliente`),
                INDEX (`codvendedor`),
                INDEX (`idbaja`),
                INDEX (`id_nc`),
                INDEX (`id_nd`)

              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla cotizaciones');
        }

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'detalle_cotizaciones'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `detalle_cotizaciones` (
                `id` int(11) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
                `idventa` int(11) DEFAULT NULL,
                `item` int(11) DEFAULT NULL,
                `idproducto` int(11) DEFAULT NULL,
                `codigo_afectacion` varchar(2) DEFAULT NULL,
                `tipo_precio` varchar(2) DEFAULT NULL,
                `cantidad` decimal(11,2) DEFAULT NULL,
                `valor_unitario` decimal(11,2) DEFAULT NULL,
                `precio_unitario` decimal(11,2) DEFAULT NULL,
                `icbper` decimal(11,2) DEFAULT 0.00,
                `igv` decimal(11,2) DEFAULT NULL,
                `porcentaje_igv` decimal(11,2) DEFAULT NULL,
                `descuento` decimal(11,2) DEFAULT 0.00,
                `descuento_factor` decimal(11,5) DEFAULT NULL,
                `valor_total` decimal(11,2) DEFAULT NULL,
                `importe_total` decimal(11,2) DEFAULT NULL,
                INDEX (idventa),
                INDEX (idproducto)

              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla detalle_cotizaciones');
        }


        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'pago_credito'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `pago_credito` (
                `id` int(11) NOT NULL  AUTO_INCREMENT PRIMARY KEY,
                `id_venta` int(11) NOT NULL,
                `fecha` text DEFAULT NULL,
                `cuota` text DEFAULT NULL,
                `tipopago` varchar(10) DEFAULT NULL,
                INDEX (id_venta)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla pago_credito');
        }

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'compra'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `compra` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_sucursal` int(11) DEFAULT NULL,
                `tipocomp` char(2) DEFAULT NULL,
                `serie` char(4) DEFAULT NULL,
                `correlativo` int(11) DEFAULT NULL,
                `serie_correlativo` varchar(15) DEFAULT NULL,
                `fecha_emision` date DEFAULT NULL,
                `fechahora` datetime DEFAULT NULL,
                `codmoneda` char(3) DEFAULT NULL,
                `metodopago` varchar(4) DEFAULT NULL,
                `comentario` text DEFAULT NULL,
                `op_gravadas` decimal(11,2) DEFAULT NULL,
                `op_exoneradas` decimal(11,2) DEFAULT NULL,
                `op_inafectas` decimal(11,2) DEFAULT NULL,
                `op_gratuitas` decimal(11,2) DEFAULT NULL,
                `descuento` decimal(11,2) DEFAULT NULL,
                `icbper` decimal(11,2) DEFAULT 0.00,
                `igv` decimal(11,2) DEFAULT NULL,
                `subtotal` decimal(11,2) DEFAULT 0.00,
                `total` decimal(11,2) DEFAULT NULL,
                `codproveedor` int(11) DEFAULT NULL,
                `codvendedor` int(11) DEFAULT NULL,
                `tipodoc` char(1) DEFAULT NULL,
                `anulado` enum('n','s') NOT NULL DEFAULT 'n',
                `idbaja` int(11) DEFAULT NULL,
                `tipocomp_ref` varchar(4) DEFAULT NULL,
                `serie_ref` varchar(8) DEFAULT NULL,
                `correlativo_ref` int(11) DEFAULT NULL,
                `codmotivo` varchar(5) DEFAULT NULL,
                `fechamodificado` date DEFAULT NULL,
                INDEX (id_sucursal),
                INDEX (codproveedor),
                INDEX (codproveedor)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla compra');
        }

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'compra_detalle'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `compra_detalle` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `idcompra` int(11) DEFAULT NULL,
                `item` int(11) DEFAULT NULL,
                `idproducto` int(11) DEFAULT NULL,
                `cod_producto` varchar(30) DEFAULT NULL,
                `descripcion` varchar(350) DEFAULT NULL,
                `codigo_afectacion` varchar(2) DEFAULT NULL,
                `tipo_precio` varchar(2) DEFAULT NULL,
                `cantidad` decimal(11,2) DEFAULT NULL,
                `valor_unitario` decimal(11,2) DEFAULT NULL,
                `precio_unitario` decimal(11,2) DEFAULT NULL,
                `icbper` decimal(11,2) DEFAULT 0.00,
                `igv` decimal(11,2) DEFAULT NULL,
                `porcentaje_igv` decimal(11,2) DEFAULT NULL,
                `descuento` decimal(11,2) DEFAULT 0.00,
                `descuento_factor` decimal(11,5) DEFAULT NULL,
                `valor_total` decimal(11,2) DEFAULT NULL,
                `importe_total` decimal(11,2) DEFAULT NULL,
                INDEX (idcompra),
                INDEX (idproducto)
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla compra_detalle');
        }

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'arqueo_cajas'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `arqueo_cajas` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `id_caja` int(11) DEFAULT NULL,
                `id_usuario` int(11) NOT NULL,
                `monto_inicial` decimal(10,2) NOT NULL,
                `fecha_apertura` datetime NOT NULL,
                `fecha_cierre` datetime DEFAULT NULL,
                `monto_final` decimal(10,2) DEFAULT NULL,
                `total_ventas` int(11) DEFAULT NULL,
                `egresos` decimal(10,2) DEFAULT NULL,
                `gastos` decimal(10,2) DEFAULT NULL,
                `estado` int(11) NOT NULL DEFAULT 1,
                INDEX (id_usuario),
                INDEX (id_caja)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla arqueo_cajas');
        }



        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'gastos'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `gastos` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `monto` decimal(10,2) NOT NULL,
                `descripcion` varchar(255) NOT NULL,
                `foto` varchar(150) DEFAULT NULL,
                `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `apertura` int(11) NOT NULL DEFAULT 0,
                `id_usuario` int(11) NOT NULL,
                INDEX (id_usuario)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla gastos');
        }


        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'sucursales'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `sucursales` (
            `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
            `id_empresa` int(11) NOT NULL,
            `codigo` varchar(20) DEFAULT NULL,
            `nombre_sucursal` varchar(400) DEFAULT NULL,
            `direccion` varchar(300) DEFAULT NULL,
            `pais` varchar(4) DEFAULT 'PE',
            `departamento` varchar(100) DEFAULT NULL,
            `provincia` varchar(100) DEFAULT NULL,
            `distrito` varchar(100) DEFAULT NULL,
            `ubigeo` char(6) DEFAULT NULL,
            `telefono` varchar(12) DEFAULT NULL,
            `correo` varchar(200) DEFAULT NULL,
            `activo` ENUM('s','n') NOT NULL DEFAULT 's',
            INDEX (id_empresa)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `sucursales` (`id`, `id_empresa`, `codigo`, `nombre_sucursal`, `direccion`, `pais`, `departamento`, `provincia`, `distrito`, `ubigeo`, `telefono`, `correo`) VALUES
            (1, 1, '0000', 'TECHMULTISER PRINCIPAL', 'CAL. TACNA NRO. 336 URB. SAN MIGUEL LIMA - LIMA  - SAN MIGUEL', 'PE', 'LIMA', 'LIMA', 'SAN MIGUEL', '150136', '963124578', 'techsuv@gmail.com')");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla sucursales');
        }


        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'cajas'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `cajas` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `numero_caja` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
                `nombre` varchar(35) COLLATE utf8_spanish_ci NOT NULL,
                `activo` tinyint(4) NOT NULL DEFAULT 1,
                `fecha_alta` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                `fecha_modifica` timestamp NULL DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla cajas');
        }


        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'proveedores'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `proveedores` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `nombre` text COLLATE utf8_spanish_ci DEFAULT NULL,
                `documento` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
                `ruc` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL,
                `razon_social` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL,
                `email` text COLLATE utf8_spanish_ci DEFAULT NULL,
                `telefono` text COLLATE utf8_spanish_ci DEFAULT NULL,
                `direccion` text COLLATE utf8_spanish_ci DEFAULT NULL,
                `ubigeo` varchar(10) COLLATE utf8_spanish_ci DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;");
            $stmt->execute();

            array_push($success,  'Se agrego la tabla proveedores');
        }



        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'modalidad_transporte'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `modalidad_transporte` (
                `codigo` varchar(2) NOT NULL,
                `descripcion` varchar(18) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `modalidad_transporte` (`codigo`, `descripcion`) VALUES
            ('01', 'TRANSPORTE PÚBLICO'),
            ('02', 'TRANSPORTE PRIVADO');");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla modalidad_transporte');
        }

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'cuentas_banco'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `cuentas_banco` (
                `id` int(11)  NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `moneda` varchar(5) DEFAULT NULL,
                `tipocuenta` varchar(50) NOT NULL,
                `nombrebanco` varchar(100) NOT NULL,
                `titular` varchar(300) NOT NULL,
                `numerocuenta` varchar(20) NOT NULL,
                `numerocci` varchar(20) NOT NULL,
                `descripcion` text DEFAULT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;");
            $stmt->execute();

            array_push($success,  'Se agrego la tabla cuentas_banco');
        }


        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'detracciones'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `detracciones` (
                `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `codigo` varchar(3) NOT NULL,
                `descripcion` varchar(90) NOT NULL,
                `porcentaje` decimal(11,2) DEFAULT 0.00
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `detracciones` (`id`, `codigo`, `descripcion`, `porcentaje`) VALUES
                (1, '001', 'AZÚCAR', 10.00),
                (2, '002', 'ARROZ', 10.00),
                (3, '003', 'ALCOHOL ETÍLICO', 10.00),
                (4, '004', 'RECURSOS HIDROBIOLÓGICOS', 4.00),
                (5, '005', 'MAÍZ AMARILLO DURO', 4.00),
                (6, '007', 'CAÑA DE AZÚCAR', 4.00),
                (7, '008', 'MADERA', 4.00),
                (8, '009', 'ARENA Y PIEDRA.', 10.00),
                (9, '010', 'RESIDUOS, SUBPRODUCTOS, DESECHOS, RECORTES Y DESPERDICIOS', 15.00),
                (10, '011', 'BIENES GRAVADOS CON EL IGV, O RENUNCIA A LA EXONERACIÓN', 0.00),
                (11, '012', 'INTERMEDIACIÓN LABORAL Y TERCERIZACIÓN', 12.00),
                (12, '013', 'ANIMALES VIVOS', 10.00),
                (13, '014', 'CARNES Y DESPOJOS COMESTIBLES', 4.00),
                (14, '015', 'ABONOS, CUEROS Y PIELES DE ORIGEN ANIMAL', 10.00),
                (15, '016', 'ACEITE DE PESCADO', 10.00),
                (16, '017', 'HARINA, POLVO Y “PELLETS” DE PESCADO, CRUSTÁCEOS, MOLUSCOS Y DEMÁS INVERTEBRADOS ACUÁTICOS', 4.00),
                (17, '019', 'ARRENDAMIENTO DE BIENES MUEBLES', 10.00),
                (18, '020', 'MANTENIMIENTO Y REPARACIÓN DE BIENES MUEBLES', 12.00),
                (19, '021', 'MOVIMIENTO DE CARGA', 10.00),
                (20, '022', 'OTROS SERVICIOS EMPRESARIALES', 12.00),
                (21, '023', 'LECHE', 1.50),
                (22, '024', 'COMISIÓN MERCANTIL', 10.00),
                (23, '025', 'FABRICACIÓN DE BIENES POR ENCARGO', 10.00),
                (24, '026', 'SERVICIO DE TRANSPORTE DE PERSONAS', 10.00),
                (25, '027', 'SERVICIO DE TRANSPORTE DE CARGA', 4.00),
                (26, '028', 'TRANSPORTE DE PASAJEROS', 0.00),
                (27, '030', 'CONTRATOS DE CONSTRUCCIÓN', 4.00),
                (28, '031', 'ORO GRAVADO CON EL IGV', 10.00),
                (29, '032', 'PAPRIKA Y OTROS FRUTOS DE LOS GENEROS CAPSICUM O PIMIENTA', 4.00),
                (30, '034', 'MINERALES METÁLICOS NO AURÍFEROS', 10.00),
                (31, '035', 'BIENES EXONERADOS DEL IGV', 1.50),
                (32, '036', 'ORO Y DEMÁS MINERALES METÁLICOS EXONERADOS DEL IGV', 1.50),
                (33, '037', 'DEMÁS SERVICIOS GRAVADOS CON EL IGV', 12.00),
                (34, '039', 'MINERALES NO METÁLICOS', 10.00),
                (35, '040', 'BIEN INMUEBLE GRAVADO CON IGV', 4.00),
                (36, '041', 'PLOMO', 0.00),
                (37, '099', 'LEY 30737', 0.00);");
            $stmt->execute();

            array_push($success,  'Se agrego la tabla detracciones');
        }
        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'motivo_traslado'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `motivo_traslado` (
                `codigo` varchar(2) NOT NULL,
                `descripcion` varchar(51) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `motivo_traslado` (`codigo`, `descripcion`) VALUES
            ('01', 'VENTA'),
            ('02', 'COMPRA'),
            ('04', 'TRASLADO ENTRE ESTABLECIMIENTOS DE LA MISMA EMPRESA'),
            ('08', 'IMPORTACIÓN'),
            ('09', 'EXPORTACIÓN'),
            ('13', 'OTROS'),
            ('14', 'VENTA SUJETA A CONFIRMACIÓN DEL COMPRADOR'),
            ('18', 'TRASLADO EMISOR ITINERANTE CP'),
            ('19', 'TRASLADO A ZONA PRIMARIA');");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla motivo_traslado');
        }

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'ubigeo_departamento'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `ubigeo_departamento` (
                `id` varchar(2) NOT NULL ,
                `name` varchar(45) NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `ubigeo_departamento` (`id`, `name`) VALUES
            ('01', 'AMAZONAS'),
            ('02', 'ÁNCASH'),
            ('03', 'APURÍMAC'),
            ('04', 'AREQUIPA'),
            ('05', 'AYACUCHO'),
            ('06', 'CAJAMARCA'),
            ('07', 'CALLAO'),
            ('08', 'CUSCO'),
            ('09', 'HUANCAVELICA'),
            ('10', 'HUÁNUCO'),
            ('11', 'ICA'),
            ('12', 'JUNÍN'),
            ('13', 'LA LIBERTAD'),
            ('14', 'LAMBAYEQUE'),
            ('15', 'LIMA'),
            ('16', 'LORETO'),
            ('17', 'MADRE DE DIOS'),
            ('18', 'MOQUEGUA'),
            ('19', 'PASCO'),
            ('20', 'PIURA'),
            ('21', 'PUNO'),
            ('22', 'SAN MARTÍN'),
            ('23', 'TACNA'),
            ('24', 'TUMBES'),
            ('25', 'UCAYALI');");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla ubigeo_departamento');
        }

        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'ubigeo_provincia'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `ubigeo_provincia` (
                `id` varchar(4) NOT NULL,
                `nombre_provincia` varchar(45) NOT NULL,
                `department_id` varchar(2) NOT NULL,
                INDEX (department_id)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `ubigeo_provincia` (`id`, `nombre_provincia`, `department_id`) VALUES
            ('0101', 'CHACHAPOYAS', '01'),
            ('0102', 'BAGUA', '01'),
            ('0103', 'BONGARÁ', '01'),
            ('0104', 'CONDORCANQUI', '01'),
            ('0105', 'LUYA', '01'),
            ('0106', 'RODRÍGUEZ DE MENDOZA', '01'),
            ('0107', 'UTCUBAMBA', '01'),
            ('0201', 'HUARAZ', '02'),
            ('0202', 'AIJA', '02'),
            ('0203', 'ANTONIO RAYMONDI', '02'),
            ('0204', 'ASUNCIÓN', '02'),
            ('0205', 'BOLOGNESI', '02'),
            ('0206', 'CARHUAZ', '02'),
            ('0207', 'CARLOS FERMÍN FITZCARRALD', '02'),
            ('0208', 'CASMA', '02'),
            ('0209', 'CORONGO', '02'),
            ('0210', 'HUARI', '02'),
            ('0211', 'HUARMEY', '02'),
            ('0212', 'HUAYLAS', '02'),
            ('0213', 'MARISCAL LUZURIAGA', '02'),
            ('0214', 'OCROS', '02'),
            ('0215', 'PALLASCA', '02'),
            ('0216', 'POMABAMBA', '02'),
            ('0217', 'RECUAY', '02'),
            ('0218', 'SANTA', '02'),
            ('0219', 'SIHUAS', '02'),
            ('0220', 'YUNGAY', '02'),
            ('0301', 'ABANCAY', '03'),
            ('0302', 'ANDAHUAYLAS', '03'),
            ('0303', 'ANTABAMBA', '03'),
            ('0304', 'AYMARAES', '03'),
            ('0305', 'COTABAMBAS', '03'),
            ('0306', 'CHINCHEROS', '03'),
            ('0307', 'GRAU', '03'),
            ('0401', 'AREQUIPA', '04'),
            ('0402', 'CAMANÁ', '04'),
            ('0403', 'CARAVELÍ', '04'),
            ('0404', 'CASTILLA', '04'),
            ('0405', 'CAYLLOMA', '04'),
            ('0406', 'CONDESUYOS', '04'),
            ('0407', 'ISLAY', '04'),
            ('0408', 'LA UNIÒN', '04'),
            ('0501', 'HUAMANGA', '05'),
            ('0502', 'CANGALLO', '05'),
            ('0503', 'HUANCA SANCOS', '05'),
            ('0504', 'HUANTA', '05'),
            ('0505', 'LA MAR', '05'),
            ('0506', 'LUCANAS', '05'),
            ('0507', 'PARINACOCHAS', '05'),
            ('0508', 'PÀUCAR DEL SARA SARA', '05'),
            ('0509', 'SUCRE', '05'),
            ('0510', 'VÍCTOR FAJARDO', '05'),
            ('0511', 'VILCAS HUAMÁN', '05'),
            ('0601', 'CAJAMARCA', '06'),
            ('0602', 'CAJABAMBA', '06'),
            ('0603', 'CELENDÍN', '06'),
            ('0604', 'CHOTA', '06'),
            ('0605', 'CONTUMAZÁ', '06'),
            ('0606', 'CUTERVO', '06'),
            ('0607', 'HUALGAYOC', '06'),
            ('0608', 'JAÉN', '06'),
            ('0609', 'SAN IGNACIO', '06'),
            ('0610', 'SAN MARCOS', '06'),
            ('0611', 'SAN MIGUEL', '06'),
            ('0612', 'SAN PABLO', '06'),
            ('0613', 'SANTA CRUZ', '06'),
            ('0701', 'PROV. CONST. DEL CALLAO', '07'),
            ('0801', 'CUSCO', '08'),
            ('0802', 'ACOMAYO', '08'),
            ('0803', 'ANTA', '08'),
            ('0804', 'CALCA', '08'),
            ('0805', 'CANAS', '08'),
            ('0806', 'CANCHIS', '08'),
            ('0807', 'CHUMBIVILCAS', '08'),
            ('0808', 'ESPINAR', '08'),
            ('0809', 'LA CONVENCIÓN', '08'),
            ('0810', 'PARURO', '08'),
            ('0811', 'PAUCARTAMBO', '08'),
            ('0812', 'QUISPICANCHI', '08'),
            ('0813', 'URUBAMBA', '08'),
            ('0901', 'HUANCAVELICA', '09'),
            ('0902', 'ACOBAMBA', '09'),
            ('0903', 'ANGARAES', '09'),
            ('0904', 'CASTROVIRREYNA', '09'),
            ('0905', 'CHURCAMPA', '09'),
            ('0906', 'HUAYTARÁ', '09'),
            ('0907', 'TAYACAJA', '09'),
            ('1001', 'HUÁNUCO', '10'),
            ('1002', 'AMBO', '10'),
            ('1003', 'DOS DE MAYO', '10'),
            ('1004', 'HUACAYBAMBA', '10'),
            ('1005', 'HUAMALÍES', '10'),
            ('1006', 'LEONCIO PRADO', '10'),
            ('1007', 'MARAÑÓN', '10'),
            ('1008', 'PACHITEA', '10'),
            ('1009', 'PUERTO INCA', '10'),
            ('1010', 'LAURICOCHA ', '10'),
            ('1011', 'YAROWILCA ', '10'),
            ('1101', 'ICA ', '11'),
            ('1102', 'CHINCHA ', '11'),
            ('1103', 'NASCA ', '11'),
            ('1104', 'PALPA ', '11'),
            ('1105', 'PISCO ', '11'),
            ('1201', 'HUANCAYO ', '12'),
            ('1202', 'CONCEPCIÓN ', '12'),
            ('1203', 'CHANCHAMAYO ', '12'),
            ('1204', 'JAUJA ', '12'),
            ('1205', 'JUNÍN ', '12'),
            ('1206', 'SATIPO ', '12'),
            ('1207', 'TARMA ', '12'),
            ('1208', 'YAULI ', '12'),
            ('1209', 'CHUPACA ', '12'),
            ('1301', 'TRUJILLO ', '13'),
            ('1302', 'ASCOPE ', '13'),
            ('1303', 'BOLÍVAR ', '13'),
            ('1304', 'CHEPÉN ', '13'),
            ('1305', 'JULCÁN ', '13'),
            ('1306', 'OTUZCO ', '13'),
            ('1307', 'PACASMAYO ', '13'),
            ('1308', 'PATAZ ', '13'),
            ('1309', 'SÁNCHEZ CARRIÓN ', '13'),
            ('1310', 'SANTIAGO DE CHUCO ', '13'),
            ('1311', 'GRAN CHIMÚ ', '13'),
            ('1312', 'VIRÚ ', '13'),
            ('1401', 'CHICLAYO ', '14'),
            ('1402', 'FERREÑAFE ', '14'),
            ('1403', 'LAMBAYEQUE ', '14'),
            ('1501', 'LIMA ', '15'),
            ('1502', 'BARRANCA ', '15'),
            ('1503', 'CAJATAMBO ', '15'),
            ('1504', 'CANTA ', '15'),
            ('1505', 'CAÑETE ', '15'),
            ('1506', 'HUARAL ', '15'),
            ('1507', 'HUAROCHIRÍ ', '15'),
            ('1508', 'HUAURA ', '15'),
            ('1509', 'OYÓN ', '15'),
            ('1510', 'YAUYOS ', '15'),
            ('1601', 'MAYNAS ', '16'),
            ('1602', 'ALTO AMAZONAS ', '16'),
            ('1603', 'LORETO ', '16'),
            ('1604', 'MARISCAL RAMÓN CASTILLA ', '16'),
            ('1605', 'REQUENA ', '16'),
            ('1606', 'UCAYALI ', '16'),
            ('1607', 'DATEM DEL MARAÑÓN ', '16'),
            ('1608', 'PUTUMAYO', '16'),
            ('1701', 'TAMBOPATA ', '17'),
            ('1702', 'MANU ', '17'),
            ('1703', 'TAHUAMANU ', '17'),
            ('1801', 'MARISCAL NIETO ', '18'),
            ('1802', 'GENERAL SÁNCHEZ CERRO ', '18'),
            ('1803', 'ILO ', '18'),
            ('1901', 'PASCO ', '19'),
            ('1902', 'DANIEL ALCIDES CARRIÓN ', '19'),
            ('1903', 'OXAPAMPA ', '19'),
            ('2001', 'PIURA ', '20'),
            ('2002', 'AYABACA ', '20'),
            ('2003', 'HUANCABAMBA ', '20'),
            ('2004', 'MORROPÓN ', '20'),
            ('2005', 'PAITA ', '20'),
            ('2006', 'SULLANA ', '20'),
            ('2007', 'TALARA ', '20'),
            ('2008', 'SECHURA ', '20'),
            ('2101', 'PUNO ', '21'),
            ('2102', 'AZÁNGARO ', '21'),
            ('2103', 'CARABAYA ', '21'),
            ('2104', 'CHUCUITO ', '21'),
            ('2105', 'EL COLLAO ', '21'),
            ('2106', 'HUANCANÉ ', '21'),
            ('2107', 'LAMPA ', '21'),
            ('2108', 'MELGAR ', '21'),
            ('2109', 'MOHO ', '21'),
            ('2110', 'SAN ANTONIO DE PUTINA ', '21'),
            ('2111', 'SAN ROMÁN ', '21'),
            ('2112', 'SANDIA ', '21'),
            ('2113', 'YUNGUYO ', '21'),
            ('2201', 'MOYOBAMBA ', '22'),
            ('2202', 'BELLAVISTA ', '22'),
            ('2203', 'EL DORADO ', '22'),
            ('2204', 'HUALLAGA ', '22'),
            ('2205', 'LAMAS ', '22'),
            ('2206', 'MARISCAL CÁCERES ', '22'),
            ('2207', 'PICOTA ', '22'),
            ('2208', 'RIOJA ', '22'),
            ('2209', 'SAN MARTÍN ', '22'),
            ('2210', 'TOCACHE ', '22'),
            ('2301', 'TACNA ', '23'),
            ('2302', 'CANDARAVE ', '23'),
            ('2303', 'JORGE BASADRE ', '23'),
            ('2304', 'TARATA ', '23'),
            ('2401', 'TUMBES ', '24'),
            ('2402', 'CONTRALMIRANTE VILLAR ', '24'),
            ('2403', 'ZARUMILLA ', '24'),
            ('2501', 'CORONEL PORTILLO ', '25'),
            ('2502', 'ATALAYA ', '25'),
            ('2503', 'PADRE ABAD ', '25'),
            ('2504', 'PURÚS', '25');");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla ubigeo_provincia');
        }


        $stmt = Conexion::conectar()->prepare("SHOW tables LIKE 'ubigeo_distrito'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("CREATE TABLE `ubigeo_distrito` (
                `id` varchar(6) NOT NULL,
                `nombre_distrito` varchar(45) DEFAULT NULL,
                `province_id` varchar(4) DEFAULT NULL,
                `department_id` varchar(2) DEFAULT NULL,
                INDEX (province_id),
                INDEX (department_id)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;");
            $stmt->execute();
            $stmt = Conexion::conectar()->prepare("INSERT INTO `ubigeo_distrito` (`id`, `nombre_distrito`, `province_id`, `department_id`) VALUES
            ('010101', 'CHACHAPOYAS', '0101', '01'),
            ('010102', 'ASUNCIÓN', '0101', '01'),
            ('010103', 'BALSAS', '0101', '01'),
            ('010104', 'CHETO', '0101', '01'),
            ('010105', 'CHILIQUIN', '0101', '01'),
            ('010106', 'CHUQUIBAMBA', '0101', '01'),
            ('010107', 'GRANADA', '0101', '01'),
            ('010108', 'HUANCAS', '0101', '01'),
            ('010109', 'LA JALCA', '0101', '01'),
            ('010110', 'LEIMEBAMBA', '0101', '01'),
            ('010111', 'LEVANTO', '0101', '01'),
            ('010112', 'MAGDALENA', '0101', '01'),
            ('010113', 'MARISCAL CASTILLA', '0101', '01'),
            ('010114', 'MOLINOPAMPA', '0101', '01'),
            ('010115', 'MONTEVIDEO', '0101', '01'),
            ('010116', 'OLLEROS', '0101', '01'),
            ('010117', 'QUINJALCA', '0101', '01'),
            ('010118', 'SAN FRANCISCO DE DAGUAS', '0101', '01'),
            ('010119', 'SAN ISIDRO DE MAINO', '0101', '01'),
            ('010120', 'SOLOCO', '0101', '01'),
            ('010121', 'SONCHE', '0101', '01'),
            ('010201', 'BAGUA', '0102', '01'),
            ('010202', 'ARAMANGO', '0102', '01'),
            ('010203', 'COPALLIN', '0102', '01'),
            ('010204', 'EL PARCO', '0102', '01'),
            ('010205', 'IMAZA', '0102', '01'),
            ('010206', 'LA PECA', '0102', '01'),
            ('010301', 'JUMBILLA', '0103', '01'),
            ('010302', 'CHISQUILLA', '0103', '01'),
            ('010303', 'CHURUJA', '0103', '01'),
            ('010304', 'COROSHA', '0103', '01'),
            ('010305', 'CUISPES', '0103', '01'),
            ('010306', 'FLORIDA', '0103', '01'),
            ('010307', 'JAZAN', '0103', '01'),
            ('010308', 'RECTA', '0103', '01'),
            ('010309', 'SAN CARLOS', '0103', '01'),
            ('010310', 'SHIPASBAMBA', '0103', '01'),
            ('010311', 'VALERA', '0103', '01'),
            ('010312', 'YAMBRASBAMBA', '0103', '01'),
            ('010401', 'NIEVA', '0104', '01'),
            ('010402', 'EL CENEPA', '0104', '01'),
            ('010403', 'RÍO SANTIAGO', '0104', '01'),
            ('010501', 'LAMUD', '0105', '01'),
            ('010502', 'CAMPORREDONDO', '0105', '01'),
            ('010503', 'COCABAMBA', '0105', '01'),
            ('010504', 'COLCAMAR', '0105', '01'),
            ('010505', 'CONILA', '0105', '01'),
            ('010506', 'INGUILPATA', '0105', '01'),
            ('010507', 'LONGUITA', '0105', '01'),
            ('010508', 'LONYA CHICO', '0105', '01'),
            ('010509', 'LUYA', '0105', '01'),
            ('010510', 'LUYA VIEJO', '0105', '01'),
            ('010511', 'MARÍA', '0105', '01'),
            ('010512', 'OCALLI', '0105', '01'),
            ('010513', 'OCUMAL', '0105', '01'),
            ('010514', 'PISUQUIA', '0105', '01'),
            ('010515', 'PROVIDENCIA', '0105', '01'),
            ('010516', 'SAN CRISTÓBAL', '0105', '01'),
            ('010517', 'SAN FRANCISCO DE YESO', '0105', '01'),
            ('010518', 'SAN JERÓNIMO', '0105', '01'),
            ('010519', 'SAN JUAN DE LOPECANCHA', '0105', '01'),
            ('010520', 'SANTA CATALINA', '0105', '01'),
            ('010521', 'SANTO TOMAS', '0105', '01'),
            ('010522', 'TINGO', '0105', '01'),
            ('010523', 'TRITA', '0105', '01'),
            ('010601', 'SAN NICOLÁS', '0106', '01'),
            ('010602', 'CHIRIMOTO', '0106', '01'),
            ('010603', 'COCHAMAL', '0106', '01'),
            ('010604', 'HUAMBO', '0106', '01'),
            ('010605', 'LIMABAMBA', '0106', '01'),
            ('010606', 'LONGAR', '0106', '01'),
            ('010607', 'MARISCAL BENAVIDES', '0106', '01'),
            ('010608', 'MILPUC', '0106', '01'),
            ('010609', 'OMIA', '0106', '01'),
            ('010610', 'SANTA ROSA', '0106', '01'),
            ('010611', 'TOTORA', '0106', '01'),
            ('010612', 'VISTA ALEGRE', '0106', '01'),
            ('010701', 'BAGUA GRANDE', '0107', '01'),
            ('010702', 'CAJARURO', '0107', '01'),
            ('010703', 'CUMBA', '0107', '01'),
            ('010704', 'EL MILAGRO', '0107', '01'),
            ('010705', 'JAMALCA', '0107', '01'),
            ('010706', 'LONYA GRANDE', '0107', '01'),
            ('010707', 'YAMON', '0107', '01'),
            ('020101', 'HUARAZ', '0201', '02'),
            ('020102', 'COCHABAMBA', '0201', '02'),
            ('020103', 'COLCABAMBA', '0201', '02'),
            ('020104', 'HUANCHAY', '0201', '02'),
            ('020105', 'INDEPENDENCIA', '0201', '02'),
            ('020106', 'JANGAS', '0201', '02'),
            ('020107', 'LA LIBERTAD', '0201', '02'),
            ('020108', 'OLLEROS', '0201', '02'),
            ('020109', 'PAMPAS GRANDE', '0201', '02'),
            ('020110', 'PARIACOTO', '0201', '02'),
            ('020111', 'PIRA', '0201', '02'),
            ('020112', 'TARICA', '0201', '02'),
            ('020201', 'AIJA', '0202', '02'),
            ('020202', 'CORIS', '0202', '02'),
            ('020203', 'HUACLLAN', '0202', '02'),
            ('020204', 'LA MERCED', '0202', '02'),
            ('020205', 'SUCCHA', '0202', '02'),
            ('020301', 'LLAMELLIN', '0203', '02'),
            ('020302', 'ACZO', '0203', '02'),
            ('020303', 'CHACCHO', '0203', '02'),
            ('020304', 'CHINGAS', '0203', '02'),
            ('020305', 'MIRGAS', '0203', '02'),
            ('020306', 'SAN JUAN DE RONTOY', '0203', '02'),
            ('020401', 'CHACAS', '0204', '02'),
            ('020402', 'ACOCHACA', '0204', '02'),
            ('020501', 'CHIQUIAN', '0205', '02'),
            ('020502', 'ABELARDO PARDO LEZAMETA', '0205', '02'),
            ('020503', 'ANTONIO RAYMONDI', '0205', '02'),
            ('020504', 'AQUIA', '0205', '02'),
            ('020505', 'CAJACAY', '0205', '02'),
            ('020506', 'CANIS', '0205', '02'),
            ('020507', 'COLQUIOC', '0205', '02'),
            ('020508', 'HUALLANCA', '0205', '02'),
            ('020509', 'HUASTA', '0205', '02'),
            ('020510', 'HUAYLLACAYAN', '0205', '02'),
            ('020511', 'LA PRIMAVERA', '0205', '02'),
            ('020512', 'MANGAS', '0205', '02'),
            ('020513', 'PACLLON', '0205', '02'),
            ('020514', 'SAN MIGUEL DE CORPANQUI', '0205', '02'),
            ('020515', 'TICLLOS', '0205', '02'),
            ('020601', 'CARHUAZ', '0206', '02'),
            ('020602', 'ACOPAMPA', '0206', '02'),
            ('020603', 'AMASHCA', '0206', '02'),
            ('020604', 'ANTA', '0206', '02'),
            ('020605', 'ATAQUERO', '0206', '02'),
            ('020606', 'MARCARA', '0206', '02'),
            ('020607', 'PARIAHUANCA', '0206', '02'),
            ('020608', 'SAN MIGUEL DE ACO', '0206', '02'),
            ('020609', 'SHILLA', '0206', '02'),
            ('020610', 'TINCO', '0206', '02'),
            ('020611', 'YUNGAR', '0206', '02'),
            ('020701', 'SAN LUIS', '0207', '02'),
            ('020702', 'SAN NICOLÁS', '0207', '02'),
            ('020703', 'YAUYA', '0207', '02'),
            ('020801', 'CASMA', '0208', '02'),
            ('020802', 'BUENA VISTA ALTA', '0208', '02'),
            ('020803', 'COMANDANTE NOEL', '0208', '02'),
            ('020804', 'YAUTAN', '0208', '02'),
            ('020901', 'CORONGO', '0209', '02'),
            ('020902', 'ACO', '0209', '02'),
            ('020903', 'BAMBAS', '0209', '02'),
            ('020904', 'CUSCA', '0209', '02'),
            ('020905', 'LA PAMPA', '0209', '02'),
            ('020906', 'YANAC', '0209', '02'),
            ('020907', 'YUPAN', '0209', '02'),
            ('021001', 'HUARI', '0210', '02'),
            ('021002', 'ANRA', '0210', '02'),
            ('021003', 'CAJAY', '0210', '02'),
            ('021004', 'CHAVIN DE HUANTAR', '0210', '02'),
            ('021005', 'HUACACHI', '0210', '02'),
            ('021006', 'HUACCHIS', '0210', '02'),
            ('021007', 'HUACHIS', '0210', '02'),
            ('021008', 'HUANTAR', '0210', '02'),
            ('021009', 'MASIN', '0210', '02'),
            ('021010', 'PAUCAS', '0210', '02'),
            ('021011', 'PONTO', '0210', '02'),
            ('021012', 'RAHUAPAMPA', '0210', '02'),
            ('021013', 'RAPAYAN', '0210', '02'),
            ('021014', 'SAN MARCOS', '0210', '02'),
            ('021015', 'SAN PEDRO DE CHANA', '0210', '02'),
            ('021016', 'UCO', '0210', '02'),
            ('021101', 'HUARMEY', '0211', '02'),
            ('021102', 'COCHAPETI', '0211', '02'),
            ('021103', 'CULEBRAS', '0211', '02'),
            ('021104', 'HUAYAN', '0211', '02'),
            ('021105', 'MALVAS', '0211', '02'),
            ('021201', 'CARAZ', '0212', '02'),
            ('021202', 'HUALLANCA', '0212', '02'),
            ('021203', 'HUATA', '0212', '02'),
            ('021204', 'HUAYLAS', '0212', '02'),
            ('021205', 'MATO', '0212', '02'),
            ('021206', 'PAMPAROMAS', '0212', '02'),
            ('021207', 'PUEBLO LIBRE', '0212', '02'),
            ('021208', 'SANTA CRUZ', '0212', '02'),
            ('021209', 'SANTO TORIBIO', '0212', '02'),
            ('021210', 'YURACMARCA', '0212', '02'),
            ('021301', 'PISCOBAMBA', '0213', '02'),
            ('021302', 'CASCA', '0213', '02'),
            ('021303', 'ELEAZAR GUZMÁN BARRON', '0213', '02'),
            ('021304', 'FIDEL OLIVAS ESCUDERO', '0213', '02'),
            ('021305', 'LLAMA', '0213', '02'),
            ('021306', 'LLUMPA', '0213', '02'),
            ('021307', 'LUCMA', '0213', '02'),
            ('021308', 'MUSGA', '0213', '02'),
            ('021401', 'OCROS', '0214', '02'),
            ('021402', 'ACAS', '0214', '02'),
            ('021403', 'CAJAMARQUILLA', '0214', '02'),
            ('021404', 'CARHUAPAMPA', '0214', '02'),
            ('021405', 'COCHAS', '0214', '02'),
            ('021406', 'CONGAS', '0214', '02'),
            ('021407', 'LLIPA', '0214', '02'),
            ('021408', 'SAN CRISTÓBAL DE RAJAN', '0214', '02'),
            ('021409', 'SAN PEDRO', '0214', '02'),
            ('021410', 'SANTIAGO DE CHILCAS', '0214', '02'),
            ('021501', 'CABANA', '0215', '02'),
            ('021502', 'BOLOGNESI', '0215', '02'),
            ('021503', 'CONCHUCOS', '0215', '02'),
            ('021504', 'HUACASCHUQUE', '0215', '02'),
            ('021505', 'HUANDOVAL', '0215', '02'),
            ('021506', 'LACABAMBA', '0215', '02'),
            ('021507', 'LLAPO', '0215', '02'),
            ('021508', 'PALLASCA', '0215', '02'),
            ('021509', 'PAMPAS', '0215', '02'),
            ('021510', 'SANTA ROSA', '0215', '02'),
            ('021511', 'TAUCA', '0215', '02'),
            ('021601', 'POMABAMBA', '0216', '02'),
            ('021602', 'HUAYLLAN', '0216', '02'),
            ('021603', 'PAROBAMBA', '0216', '02'),
            ('021604', 'QUINUABAMBA', '0216', '02'),
            ('021701', 'RECUAY', '0217', '02'),
            ('021702', 'CATAC', '0217', '02'),
            ('021703', 'COTAPARACO', '0217', '02'),
            ('021704', 'HUAYLLAPAMPA', '0217', '02'),
            ('021705', 'LLACLLIN', '0217', '02'),
            ('021706', 'MARCA', '0217', '02'),
            ('021707', 'PAMPAS CHICO', '0217', '02'),
            ('021708', 'PARARIN', '0217', '02'),
            ('021709', 'TAPACOCHA', '0217', '02'),
            ('021710', 'TICAPAMPA', '0217', '02'),
            ('021801', 'CHIMBOTE', '0218', '02'),
            ('021802', 'CÁCERES DEL PERÚ', '0218', '02'),
            ('021803', 'COISHCO', '0218', '02'),
            ('021804', 'MACATE', '0218', '02'),
            ('021805', 'MORO', '0218', '02'),
            ('021806', 'NEPEÑA', '0218', '02'),
            ('021807', 'SAMANCO', '0218', '02'),
            ('021808', 'SANTA', '0218', '02'),
            ('021809', 'NUEVO CHIMBOTE', '0218', '02'),
            ('021901', 'SIHUAS', '0219', '02'),
            ('021902', 'ACOBAMBA', '0219', '02'),
            ('021903', 'ALFONSO UGARTE', '0219', '02'),
            ('021904', 'CASHAPAMPA', '0219', '02'),
            ('021905', 'CHINGALPO', '0219', '02'),
            ('021906', 'HUAYLLABAMBA', '0219', '02'),
            ('021907', 'QUICHES', '0219', '02'),
            ('021908', 'RAGASH', '0219', '02'),
            ('021909', 'SAN JUAN', '0219', '02'),
            ('021910', 'SICSIBAMBA', '0219', '02'),
            ('022001', 'YUNGAY', '0220', '02'),
            ('022002', 'CASCAPARA', '0220', '02'),
            ('022003', 'MANCOS', '0220', '02'),
            ('022004', 'MATACOTO', '0220', '02'),
            ('022005', 'QUILLO', '0220', '02'),
            ('022006', 'RANRAHIRCA', '0220', '02'),
            ('022007', 'SHUPLUY', '0220', '02'),
            ('022008', 'YANAMA', '0220', '02'),
            ('030101', 'ABANCAY', '0301', '03'),
            ('030102', 'CHACOCHE', '0301', '03'),
            ('030103', 'CIRCA', '0301', '03'),
            ('030104', 'CURAHUASI', '0301', '03'),
            ('030105', 'HUANIPACA', '0301', '03'),
            ('030106', 'LAMBRAMA', '0301', '03'),
            ('030107', 'PICHIRHUA', '0301', '03'),
            ('030108', 'SAN PEDRO DE CACHORA', '0301', '03'),
            ('030109', 'TAMBURCO', '0301', '03'),
            ('030201', 'ANDAHUAYLAS', '0302', '03'),
            ('030202', 'ANDARAPA', '0302', '03'),
            ('030203', 'CHIARA', '0302', '03'),
            ('030204', 'HUANCARAMA', '0302', '03'),
            ('030205', 'HUANCARAY', '0302', '03'),
            ('030206', 'HUAYANA', '0302', '03'),
            ('030207', 'KISHUARA', '0302', '03'),
            ('030208', 'PACOBAMBA', '0302', '03'),
            ('030209', 'PACUCHA', '0302', '03'),
            ('030210', 'PAMPACHIRI', '0302', '03'),
            ('030211', 'POMACOCHA', '0302', '03'),
            ('030212', 'SAN ANTONIO DE CACHI', '0302', '03'),
            ('030213', 'SAN JERÓNIMO', '0302', '03'),
            ('030214', 'SAN MIGUEL DE CHACCRAMPA', '0302', '03'),
            ('030215', 'SANTA MARÍA DE CHICMO', '0302', '03'),
            ('030216', 'TALAVERA', '0302', '03'),
            ('030217', 'TUMAY HUARACA', '0302', '03'),
            ('030218', 'TURPO', '0302', '03'),
            ('030219', 'KAQUIABAMBA', '0302', '03'),
            ('030220', 'JOSÉ MARÍA ARGUEDAS', '0302', '03'),
            ('030301', 'ANTABAMBA', '0303', '03'),
            ('030302', 'EL ORO', '0303', '03'),
            ('030303', 'HUAQUIRCA', '0303', '03'),
            ('030304', 'JUAN ESPINOZA MEDRANO', '0303', '03'),
            ('030305', 'OROPESA', '0303', '03'),
            ('030306', 'PACHACONAS', '0303', '03'),
            ('030307', 'SABAINO', '0303', '03'),
            ('030401', 'CHALHUANCA', '0304', '03'),
            ('030402', 'CAPAYA', '0304', '03'),
            ('030403', 'CARAYBAMBA', '0304', '03'),
            ('030404', 'CHAPIMARCA', '0304', '03'),
            ('030405', 'COLCABAMBA', '0304', '03'),
            ('030406', 'COTARUSE', '0304', '03'),
            ('030407', 'IHUAYLLO', '0304', '03'),
            ('030408', 'JUSTO APU SAHUARAURA', '0304', '03'),
            ('030409', 'LUCRE', '0304', '03'),
            ('030410', 'POCOHUANCA', '0304', '03'),
            ('030411', 'SAN JUAN DE CHACÑA', '0304', '03'),
            ('030412', 'SAÑAYCA', '0304', '03'),
            ('030413', 'SORAYA', '0304', '03'),
            ('030414', 'TAPAIRIHUA', '0304', '03'),
            ('030415', 'TINTAY', '0304', '03'),
            ('030416', 'TORAYA', '0304', '03'),
            ('030417', 'YANACA', '0304', '03'),
            ('030501', 'TAMBOBAMBA', '0305', '03'),
            ('030502', 'COTABAMBAS', '0305', '03'),
            ('030503', 'COYLLURQUI', '0305', '03'),
            ('030504', 'HAQUIRA', '0305', '03'),
            ('030505', 'MARA', '0305', '03'),
            ('030506', 'CHALLHUAHUACHO', '0305', '03'),
            ('030601', 'CHINCHEROS', '0306', '03'),
            ('030602', 'ANCO_HUALLO', '0306', '03'),
            ('030603', 'COCHARCAS', '0306', '03'),
            ('030604', 'HUACCANA', '0306', '03'),
            ('030605', 'OCOBAMBA', '0306', '03'),
            ('030606', 'ONGOY', '0306', '03'),
            ('030607', 'URANMARCA', '0306', '03'),
            ('030608', 'RANRACANCHA', '0306', '03'),
            ('030609', 'ROCCHACC', '0306', '03'),
            ('030610', 'EL PORVENIR', '0306', '03'),
            ('030611', 'LOS CHANKAS', '0306', '03'),
            ('030701', 'CHUQUIBAMBILLA', '0307', '03'),
            ('030702', 'CURPAHUASI', '0307', '03'),
            ('030703', 'GAMARRA', '0307', '03'),
            ('030704', 'HUAYLLATI', '0307', '03'),
            ('030705', 'MAMARA', '0307', '03'),
            ('030706', 'MICAELA BASTIDAS', '0307', '03'),
            ('030707', 'PATAYPAMPA', '0307', '03'),
            ('030708', 'PROGRESO', '0307', '03'),
            ('030709', 'SAN ANTONIO', '0307', '03'),
            ('030710', 'SANTA ROSA', '0307', '03'),
            ('030711', 'TURPAY', '0307', '03'),
            ('030712', 'VILCABAMBA', '0307', '03'),
            ('030713', 'VIRUNDO', '0307', '03'),
            ('030714', 'CURASCO', '0307', '03'),
            ('040101', 'AREQUIPA', '0401', '04'),
            ('040102', 'ALTO SELVA ALEGRE', '0401', '04'),
            ('040103', 'CAYMA', '0401', '04'),
            ('040104', 'CERRO COLORADO', '0401', '04'),
            ('040105', 'CHARACATO', '0401', '04'),
            ('040106', 'CHIGUATA', '0401', '04'),
            ('040107', 'JACOBO HUNTER', '0401', '04'),
            ('040108', 'LA JOYA', '0401', '04'),
            ('040109', 'MARIANO MELGAR', '0401', '04'),
            ('040110', 'MIRAFLORES', '0401', '04'),
            ('040111', 'MOLLEBAYA', '0401', '04'),
            ('040112', 'PAUCARPATA', '0401', '04'),
            ('040113', 'POCSI', '0401', '04'),
            ('040114', 'POLOBAYA', '0401', '04'),
            ('040115', 'QUEQUEÑA', '0401', '04'),
            ('040116', 'SABANDIA', '0401', '04'),
            ('040117', 'SACHACA', '0401', '04'),
            ('040118', 'SAN JUAN DE SIGUAS', '0401', '04'),
            ('040119', 'SAN JUAN DE TARUCANI', '0401', '04'),
            ('040120', 'SANTA ISABEL DE SIGUAS', '0401', '04'),
            ('040121', 'SANTA RITA DE SIGUAS', '0401', '04'),
            ('040122', 'SOCABAYA', '0401', '04'),
            ('040123', 'TIABAYA', '0401', '04'),
            ('040124', 'UCHUMAYO', '0401', '04'),
            ('040125', 'VITOR', '0401', '04'),
            ('040126', 'YANAHUARA', '0401', '04'),
            ('040127', 'YARABAMBA', '0401', '04'),
            ('040128', 'YURA', '0401', '04'),
            ('040129', 'JOSÉ LUIS BUSTAMANTE Y RIVERO', '0401', '04'),
            ('040201', 'CAMANÁ', '0402', '04'),
            ('040202', 'JOSÉ MARÍA QUIMPER', '0402', '04'),
            ('040203', 'MARIANO NICOLÁS VALCÁRCEL', '0402', '04'),
            ('040204', 'MARISCAL CÁCERES', '0402', '04'),
            ('040205', 'NICOLÁS DE PIEROLA', '0402', '04'),
            ('040206', 'OCOÑA', '0402', '04'),
            ('040207', 'QUILCA', '0402', '04'),
            ('040208', 'SAMUEL PASTOR', '0402', '04'),
            ('040301', 'CARAVELÍ', '0403', '04'),
            ('040302', 'ACARÍ', '0403', '04'),
            ('040303', 'ATICO', '0403', '04'),
            ('040304', 'ATIQUIPA', '0403', '04'),
            ('040305', 'BELLA UNIÓN', '0403', '04'),
            ('040306', 'CAHUACHO', '0403', '04'),
            ('040307', 'CHALA', '0403', '04'),
            ('040308', 'CHAPARRA', '0403', '04'),
            ('040309', 'HUANUHUANU', '0403', '04'),
            ('040310', 'JAQUI', '0403', '04'),
            ('040311', 'LOMAS', '0403', '04'),
            ('040312', 'QUICACHA', '0403', '04'),
            ('040313', 'YAUCA', '0403', '04'),
            ('040401', 'APLAO', '0404', '04'),
            ('040402', 'ANDAGUA', '0404', '04'),
            ('040403', 'AYO', '0404', '04'),
            ('040404', 'CHACHAS', '0404', '04'),
            ('040405', 'CHILCAYMARCA', '0404', '04'),
            ('040406', 'CHOCO', '0404', '04'),
            ('040407', 'HUANCARQUI', '0404', '04'),
            ('040408', 'MACHAGUAY', '0404', '04'),
            ('040409', 'ORCOPAMPA', '0404', '04'),
            ('040410', 'PAMPACOLCA', '0404', '04'),
            ('040411', 'TIPAN', '0404', '04'),
            ('040412', 'UÑON', '0404', '04'),
            ('040413', 'URACA', '0404', '04'),
            ('040414', 'VIRACO', '0404', '04'),
            ('040501', 'CHIVAY', '0405', '04'),
            ('040502', 'ACHOMA', '0405', '04'),
            ('040503', 'CABANACONDE', '0405', '04'),
            ('040504', 'CALLALLI', '0405', '04'),
            ('040505', 'CAYLLOMA', '0405', '04'),
            ('040506', 'COPORAQUE', '0405', '04'),
            ('040507', 'HUAMBO', '0405', '04'),
            ('040508', 'HUANCA', '0405', '04'),
            ('040509', 'ICHUPAMPA', '0405', '04'),
            ('040510', 'LARI', '0405', '04'),
            ('040511', 'LLUTA', '0405', '04'),
            ('040512', 'MACA', '0405', '04'),
            ('040513', 'MADRIGAL', '0405', '04'),
            ('040514', 'SAN ANTONIO DE CHUCA', '0405', '04'),
            ('040515', 'SIBAYO', '0405', '04'),
            ('040516', 'TAPAY', '0405', '04'),
            ('040517', 'TISCO', '0405', '04'),
            ('040518', 'TUTI', '0405', '04'),
            ('040519', 'YANQUE', '0405', '04'),
            ('040520', 'MAJES', '0405', '04'),
            ('040601', 'CHUQUIBAMBA', '0406', '04'),
            ('040602', 'ANDARAY', '0406', '04'),
            ('040603', 'CAYARANI', '0406', '04'),
            ('040604', 'CHICHAS', '0406', '04'),
            ('040605', 'IRAY', '0406', '04'),
            ('040606', 'RÍO GRANDE', '0406', '04'),
            ('040607', 'SALAMANCA', '0406', '04'),
            ('040608', 'YANAQUIHUA', '0406', '04'),
            ('040701', 'MOLLENDO', '0407', '04'),
            ('040702', 'COCACHACRA', '0407', '04'),
            ('040703', 'DEAN VALDIVIA', '0407', '04'),
            ('040704', 'ISLAY', '0407', '04'),
            ('040705', 'MEJIA', '0407', '04'),
            ('040706', 'PUNTA DE BOMBÓN', '0407', '04'),
            ('040801', 'COTAHUASI', '0408', '04'),
            ('040802', 'ALCA', '0408', '04'),
            ('040803', 'CHARCANA', '0408', '04'),
            ('040804', 'HUAYNACOTAS', '0408', '04'),
            ('040805', 'PAMPAMARCA', '0408', '04'),
            ('040806', 'PUYCA', '0408', '04'),
            ('040807', 'QUECHUALLA', '0408', '04'),
            ('040808', 'SAYLA', '0408', '04'),
            ('040809', 'TAURIA', '0408', '04'),
            ('040810', 'TOMEPAMPA', '0408', '04'),
            ('040811', 'TORO', '0408', '04'),
            ('050101', 'AYACUCHO', '0501', '05'),
            ('050102', 'ACOCRO', '0501', '05'),
            ('050103', 'ACOS VINCHOS', '0501', '05'),
            ('050104', 'CARMEN ALTO', '0501', '05'),
            ('050105', 'CHIARA', '0501', '05'),
            ('050106', 'OCROS', '0501', '05'),
            ('050107', 'PACAYCASA', '0501', '05'),
            ('050108', 'QUINUA', '0501', '05'),
            ('050109', 'SAN JOSÉ DE TICLLAS', '0501', '05'),
            ('050110', 'SAN JUAN BAUTISTA', '0501', '05'),
            ('050111', 'SANTIAGO DE PISCHA', '0501', '05'),
            ('050112', 'SOCOS', '0501', '05'),
            ('050113', 'TAMBILLO', '0501', '05'),
            ('050114', 'VINCHOS', '0501', '05'),
            ('050115', 'JESÚS NAZARENO', '0501', '05'),
            ('050116', 'ANDRÉS AVELINO CÁCERES DORREGARAY', '0501', '05'),
            ('050201', 'CANGALLO', '0502', '05'),
            ('050202', 'CHUSCHI', '0502', '05'),
            ('050203', 'LOS MOROCHUCOS', '0502', '05'),
            ('050204', 'MARÍA PARADO DE BELLIDO', '0502', '05'),
            ('050205', 'PARAS', '0502', '05'),
            ('050206', 'TOTOS', '0502', '05'),
            ('050301', 'SANCOS', '0503', '05'),
            ('050302', 'CARAPO', '0503', '05'),
            ('050303', 'SACSAMARCA', '0503', '05'),
            ('050304', 'SANTIAGO DE LUCANAMARCA', '0503', '05'),
            ('050401', 'HUANTA', '0504', '05'),
            ('050402', 'AYAHUANCO', '0504', '05'),
            ('050403', 'HUAMANGUILLA', '0504', '05'),
            ('050404', 'IGUAIN', '0504', '05'),
            ('050405', 'LURICOCHA', '0504', '05'),
            ('050406', 'SANTILLANA', '0504', '05'),
            ('050407', 'SIVIA', '0504', '05'),
            ('050408', 'LLOCHEGUA', '0504', '05'),
            ('050409', 'CANAYRE', '0504', '05'),
            ('050410', 'UCHURACCAY', '0504', '05'),
            ('050411', 'PUCACOLPA', '0504', '05'),
            ('050412', 'CHACA', '0504', '05'),
            ('050501', 'SAN MIGUEL', '0505', '05'),
            ('050502', 'ANCO', '0505', '05'),
            ('050503', 'AYNA', '0505', '05'),
            ('050504', 'CHILCAS', '0505', '05'),
            ('050505', 'CHUNGUI', '0505', '05'),
            ('050506', 'LUIS CARRANZA', '0505', '05'),
            ('050507', 'SANTA ROSA', '0505', '05'),
            ('050508', 'TAMBO', '0505', '05'),
            ('050509', 'SAMUGARI', '0505', '05'),
            ('050510', 'ANCHIHUAY', '0505', '05'),
            ('050511', 'ORONCCOY', '0505', '05'),
            ('050601', 'PUQUIO', '0506', '05'),
            ('050602', 'AUCARA', '0506', '05'),
            ('050603', 'CABANA', '0506', '05'),
            ('050604', 'CARMEN SALCEDO', '0506', '05'),
            ('050605', 'CHAVIÑA', '0506', '05'),
            ('050606', 'CHIPAO', '0506', '05'),
            ('050607', 'HUAC-HUAS', '0506', '05'),
            ('050608', 'LARAMATE', '0506', '05'),
            ('050609', 'LEONCIO PRADO', '0506', '05'),
            ('050610', 'LLAUTA', '0506', '05'),
            ('050611', 'LUCANAS', '0506', '05'),
            ('050612', 'OCAÑA', '0506', '05'),
            ('050613', 'OTOCA', '0506', '05'),
            ('050614', 'SAISA', '0506', '05'),
            ('050615', 'SAN CRISTÓBAL', '0506', '05'),
            ('050616', 'SAN JUAN', '0506', '05'),
            ('050617', 'SAN PEDRO', '0506', '05'),
            ('050618', 'SAN PEDRO DE PALCO', '0506', '05'),
            ('050619', 'SANCOS', '0506', '05'),
            ('050620', 'SANTA ANA DE HUAYCAHUACHO', '0506', '05'),
            ('050621', 'SANTA LUCIA', '0506', '05'),
            ('050701', 'CORACORA', '0507', '05'),
            ('050702', 'CHUMPI', '0507', '05'),
            ('050703', 'CORONEL CASTAÑEDA', '0507', '05'),
            ('050704', 'PACAPAUSA', '0507', '05'),
            ('050705', 'PULLO', '0507', '05'),
            ('050706', 'PUYUSCA', '0507', '05'),
            ('050707', 'SAN FRANCISCO DE RAVACAYCO', '0507', '05'),
            ('050708', 'UPAHUACHO', '0507', '05'),
            ('050801', 'PAUSA', '0508', '05'),
            ('050802', 'COLTA', '0508', '05'),
            ('050803', 'CORCULLA', '0508', '05'),
            ('050804', 'LAMPA', '0508', '05'),
            ('050805', 'MARCABAMBA', '0508', '05'),
            ('050806', 'OYOLO', '0508', '05'),
            ('050807', 'PARARCA', '0508', '05'),
            ('050808', 'SAN JAVIER DE ALPABAMBA', '0508', '05'),
            ('050809', 'SAN JOSÉ DE USHUA', '0508', '05'),
            ('050810', 'SARA SARA', '0508', '05'),
            ('050901', 'QUEROBAMBA', '0509', '05'),
            ('050902', 'BELÉN', '0509', '05'),
            ('050903', 'CHALCOS', '0509', '05'),
            ('050904', 'CHILCAYOC', '0509', '05'),
            ('050905', 'HUACAÑA', '0509', '05'),
            ('050906', 'MORCOLLA', '0509', '05'),
            ('050907', 'PAICO', '0509', '05'),
            ('050908', 'SAN PEDRO DE LARCAY', '0509', '05'),
            ('050909', 'SAN SALVADOR DE QUIJE', '0509', '05'),
            ('050910', 'SANTIAGO DE PAUCARAY', '0509', '05'),
            ('050911', 'SORAS', '0509', '05'),
            ('051001', 'HUANCAPI', '0510', '05'),
            ('051002', 'ALCAMENCA', '0510', '05'),
            ('051003', 'APONGO', '0510', '05'),
            ('051004', 'ASQUIPATA', '0510', '05'),
            ('051005', 'CANARIA', '0510', '05'),
            ('051006', 'CAYARA', '0510', '05'),
            ('051007', 'COLCA', '0510', '05'),
            ('051008', 'HUAMANQUIQUIA', '0510', '05'),
            ('051009', 'HUANCARAYLLA', '0510', '05'),
            ('051010', 'HUALLA', '0510', '05'),
            ('051011', 'SARHUA', '0510', '05'),
            ('051012', 'VILCANCHOS', '0510', '05'),
            ('051101', 'VILCAS HUAMAN', '0511', '05'),
            ('051102', 'ACCOMARCA', '0511', '05'),
            ('051103', 'CARHUANCA', '0511', '05'),
            ('051104', 'CONCEPCIÓN', '0511', '05'),
            ('051105', 'HUAMBALPA', '0511', '05'),
            ('051106', 'INDEPENDENCIA', '0511', '05'),
            ('051107', 'SAURAMA', '0511', '05'),
            ('051108', 'VISCHONGO', '0511', '05'),
            ('060101', 'CAJAMARCA', '0601', '06'),
            ('060102', 'ASUNCIÓN', '0601', '06'),
            ('060103', 'CHETILLA', '0601', '06'),
            ('060104', 'COSPAN', '0601', '06'),
            ('060105', 'ENCAÑADA', '0601', '06'),
            ('060106', 'JESÚS', '0601', '06'),
            ('060107', 'LLACANORA', '0601', '06'),
            ('060108', 'LOS BAÑOS DEL INCA', '0601', '06'),
            ('060109', 'MAGDALENA', '0601', '06'),
            ('060110', 'MATARA', '0601', '06'),
            ('060111', 'NAMORA', '0601', '06'),
            ('060112', 'SAN JUAN', '0601', '06'),
            ('060201', 'CAJABAMBA', '0602', '06'),
            ('060202', 'CACHACHI', '0602', '06'),
            ('060203', 'CONDEBAMBA', '0602', '06'),
            ('060204', 'SITACOCHA', '0602', '06'),
            ('060301', 'CELENDÍN', '0603', '06'),
            ('060302', 'CHUMUCH', '0603', '06'),
            ('060303', 'CORTEGANA', '0603', '06'),
            ('060304', 'HUASMIN', '0603', '06'),
            ('060305', 'JORGE CHÁVEZ', '0603', '06'),
            ('060306', 'JOSÉ GÁLVEZ', '0603', '06'),
            ('060307', 'MIGUEL IGLESIAS', '0603', '06'),
            ('060308', 'OXAMARCA', '0603', '06'),
            ('060309', 'SOROCHUCO', '0603', '06'),
            ('060310', 'SUCRE', '0603', '06'),
            ('060311', 'UTCO', '0603', '06'),
            ('060312', 'LA LIBERTAD DE PALLAN', '0603', '06'),
            ('060401', 'CHOTA', '0604', '06'),
            ('060402', 'ANGUIA', '0604', '06'),
            ('060403', 'CHADIN', '0604', '06'),
            ('060404', 'CHIGUIRIP', '0604', '06'),
            ('060405', 'CHIMBAN', '0604', '06'),
            ('060406', 'CHOROPAMPA', '0604', '06'),
            ('060407', 'COCHABAMBA', '0604', '06'),
            ('060408', 'CONCHAN', '0604', '06'),
            ('060409', 'HUAMBOS', '0604', '06'),
            ('060410', 'LAJAS', '0604', '06'),
            ('060411', 'LLAMA', '0604', '06'),
            ('060412', 'MIRACOSTA', '0604', '06'),
            ('060413', 'PACCHA', '0604', '06'),
            ('060414', 'PION', '0604', '06'),
            ('060415', 'QUEROCOTO', '0604', '06'),
            ('060416', 'SAN JUAN DE LICUPIS', '0604', '06'),
            ('060417', 'TACABAMBA', '0604', '06'),
            ('060418', 'TOCMOCHE', '0604', '06'),
            ('060419', 'CHALAMARCA', '0604', '06'),
            ('060501', 'CONTUMAZA', '0605', '06'),
            ('060502', 'CHILETE', '0605', '06'),
            ('060503', 'CUPISNIQUE', '0605', '06'),
            ('060504', 'GUZMANGO', '0605', '06'),
            ('060505', 'SAN BENITO', '0605', '06'),
            ('060506', 'SANTA CRUZ DE TOLEDO', '0605', '06'),
            ('060507', 'TANTARICA', '0605', '06'),
            ('060508', 'YONAN', '0605', '06'),
            ('060601', 'CUTERVO', '0606', '06'),
            ('060602', 'CALLAYUC', '0606', '06'),
            ('060603', 'CHOROS', '0606', '06'),
            ('060604', 'CUJILLO', '0606', '06'),
            ('060605', 'LA RAMADA', '0606', '06'),
            ('060606', 'PIMPINGOS', '0606', '06'),
            ('060607', 'QUEROCOTILLO', '0606', '06'),
            ('060608', 'SAN ANDRÉS DE CUTERVO', '0606', '06'),
            ('060609', 'SAN JUAN DE CUTERVO', '0606', '06'),
            ('060610', 'SAN LUIS DE LUCMA', '0606', '06'),
            ('060611', 'SANTA CRUZ', '0606', '06'),
            ('060612', 'SANTO DOMINGO DE LA CAPILLA', '0606', '06'),
            ('060613', 'SANTO TOMAS', '0606', '06'),
            ('060614', 'SOCOTA', '0606', '06'),
            ('060615', 'TORIBIO CASANOVA', '0606', '06'),
            ('060701', 'BAMBAMARCA', '0607', '06'),
            ('060702', 'CHUGUR', '0607', '06'),
            ('060703', 'HUALGAYOC', '0607', '06'),
            ('060801', 'JAÉN', '0608', '06'),
            ('060802', 'BELLAVISTA', '0608', '06'),
            ('060803', 'CHONTALI', '0608', '06'),
            ('060804', 'COLASAY', '0608', '06'),
            ('060805', 'HUABAL', '0608', '06'),
            ('060806', 'LAS PIRIAS', '0608', '06'),
            ('060807', 'POMAHUACA', '0608', '06'),
            ('060808', 'PUCARA', '0608', '06'),
            ('060809', 'SALLIQUE', '0608', '06'),
            ('060810', 'SAN FELIPE', '0608', '06'),
            ('060811', 'SAN JOSÉ DEL ALTO', '0608', '06'),
            ('060812', 'SANTA ROSA', '0608', '06'),
            ('060901', 'SAN IGNACIO', '0609', '06'),
            ('060902', 'CHIRINOS', '0609', '06'),
            ('060903', 'HUARANGO', '0609', '06'),
            ('060904', 'LA COIPA', '0609', '06'),
            ('060905', 'NAMBALLE', '0609', '06'),
            ('060906', 'SAN JOSÉ DE LOURDES', '0609', '06'),
            ('060907', 'TABACONAS', '0609', '06'),
            ('061001', 'PEDRO GÁLVEZ', '0610', '06'),
            ('061002', 'CHANCAY', '0610', '06'),
            ('061003', 'EDUARDO VILLANUEVA', '0610', '06'),
            ('061004', 'GREGORIO PITA', '0610', '06'),
            ('061005', 'ICHOCAN', '0610', '06'),
            ('061006', 'JOSÉ MANUEL QUIROZ', '0610', '06'),
            ('061007', 'JOSÉ SABOGAL', '0610', '06'),
            ('061101', 'SAN MIGUEL', '0611', '06'),
            ('061102', 'BOLÍVAR', '0611', '06'),
            ('061103', 'CALQUIS', '0611', '06'),
            ('061104', 'CATILLUC', '0611', '06'),
            ('061105', 'EL PRADO', '0611', '06'),
            ('061106', 'LA FLORIDA', '0611', '06'),
            ('061107', 'LLAPA', '0611', '06'),
            ('061108', 'NANCHOC', '0611', '06'),
            ('061109', 'NIEPOS', '0611', '06'),
            ('061110', 'SAN GREGORIO', '0611', '06'),
            ('061111', 'SAN SILVESTRE DE COCHAN', '0611', '06'),
            ('061112', 'TONGOD', '0611', '06'),
            ('061113', 'UNIÓN AGUA BLANCA', '0611', '06'),
            ('061201', 'SAN PABLO', '0612', '06'),
            ('061202', 'SAN BERNARDINO', '0612', '06'),
            ('061203', 'SAN LUIS', '0612', '06'),
            ('061204', 'TUMBADEN', '0612', '06'),
            ('061301', 'SANTA CRUZ', '0613', '06'),
            ('061302', 'ANDABAMBA', '0613', '06'),
            ('061303', 'CATACHE', '0613', '06'),
            ('061304', 'CHANCAYBAÑOS', '0613', '06'),
            ('061305', 'LA ESPERANZA', '0613', '06'),
            ('061306', 'NINABAMBA', '0613', '06'),
            ('061307', 'PULAN', '0613', '06'),
            ('061308', 'SAUCEPAMPA', '0613', '06'),
            ('061309', 'SEXI', '0613', '06'),
            ('061310', 'UTICYACU', '0613', '06'),
            ('061311', 'YAUYUCAN', '0613', '06'),
            ('070101', 'CALLAO', '0701', '07'),
            ('070102', 'BELLAVISTA', '0701', '07'),
            ('070103', 'CARMEN DE LA LEGUA REYNOSO', '0701', '07'),
            ('070104', 'LA PERLA', '0701', '07'),
            ('070105', 'LA PUNTA', '0701', '07'),
            ('070106', 'VENTANILLA', '0701', '07'),
            ('070107', 'MI PERÚ', '0701', '07'),
            ('080101', 'CUSCO', '0801', '08'),
            ('080102', 'CCORCA', '0801', '08'),
            ('080103', 'POROY', '0801', '08'),
            ('080104', 'SAN JERÓNIMO', '0801', '08'),
            ('080105', 'SAN SEBASTIAN', '0801', '08'),
            ('080106', 'SANTIAGO', '0801', '08'),
            ('080107', 'SAYLLA', '0801', '08'),
            ('080108', 'WANCHAQ', '0801', '08'),
            ('080201', 'ACOMAYO', '0802', '08'),
            ('080202', 'ACOPIA', '0802', '08'),
            ('080203', 'ACOS', '0802', '08'),
            ('080204', 'MOSOC LLACTA', '0802', '08'),
            ('080205', 'POMACANCHI', '0802', '08'),
            ('080206', 'RONDOCAN', '0802', '08'),
            ('080207', 'SANGARARA', '0802', '08'),
            ('080301', 'ANTA', '0803', '08'),
            ('080302', 'ANCAHUASI', '0803', '08'),
            ('080303', 'CACHIMAYO', '0803', '08'),
            ('080304', 'CHINCHAYPUJIO', '0803', '08'),
            ('080305', 'HUAROCONDO', '0803', '08'),
            ('080306', 'LIMATAMBO', '0803', '08'),
            ('080307', 'MOLLEPATA', '0803', '08'),
            ('080308', 'PUCYURA', '0803', '08'),
            ('080309', 'ZURITE', '0803', '08'),
            ('080401', 'CALCA', '0804', '08'),
            ('080402', 'COYA', '0804', '08'),
            ('080403', 'LAMAY', '0804', '08'),
            ('080404', 'LARES', '0804', '08'),
            ('080405', 'PISAC', '0804', '08'),
            ('080406', 'SAN SALVADOR', '0804', '08'),
            ('080407', 'TARAY', '0804', '08'),
            ('080408', 'YANATILE', '0804', '08'),
            ('080501', 'YANAOCA', '0805', '08'),
            ('080502', 'CHECCA', '0805', '08'),
            ('080503', 'KUNTURKANKI', '0805', '08'),
            ('080504', 'LANGUI', '0805', '08'),
            ('080505', 'LAYO', '0805', '08'),
            ('080506', 'PAMPAMARCA', '0805', '08'),
            ('080507', 'QUEHUE', '0805', '08'),
            ('080508', 'TUPAC AMARU', '0805', '08'),
            ('080601', 'SICUANI', '0806', '08'),
            ('080602', 'CHECACUPE', '0806', '08'),
            ('080603', 'COMBAPATA', '0806', '08'),
            ('080604', 'MARANGANI', '0806', '08'),
            ('080605', 'PITUMARCA', '0806', '08'),
            ('080606', 'SAN PABLO', '0806', '08'),
            ('080607', 'SAN PEDRO', '0806', '08'),
            ('080608', 'TINTA', '0806', '08'),
            ('080701', 'SANTO TOMAS', '0807', '08'),
            ('080702', 'CAPACMARCA', '0807', '08'),
            ('080703', 'CHAMACA', '0807', '08'),
            ('080704', 'COLQUEMARCA', '0807', '08'),
            ('080705', 'LIVITACA', '0807', '08'),
            ('080706', 'LLUSCO', '0807', '08'),
            ('080707', 'QUIÑOTA', '0807', '08'),
            ('080708', 'VELILLE', '0807', '08'),
            ('080801', 'ESPINAR', '0808', '08'),
            ('080802', 'CONDOROMA', '0808', '08'),
            ('080803', 'COPORAQUE', '0808', '08'),
            ('080804', 'OCORURO', '0808', '08'),
            ('080805', 'PALLPATA', '0808', '08'),
            ('080806', 'PICHIGUA', '0808', '08'),
            ('080807', 'SUYCKUTAMBO', '0808', '08'),
            ('080808', 'ALTO PICHIGUA', '0808', '08'),
            ('080901', 'SANTA ANA', '0809', '08'),
            ('080902', 'ECHARATE', '0809', '08'),
            ('080903', 'HUAYOPATA', '0809', '08'),
            ('080904', 'MARANURA', '0809', '08'),
            ('080905', 'OCOBAMBA', '0809', '08'),
            ('080906', 'QUELLOUNO', '0809', '08'),
            ('080907', 'KIMBIRI', '0809', '08'),
            ('080908', 'SANTA TERESA', '0809', '08'),
            ('080909', 'VILCABAMBA', '0809', '08'),
            ('080910', 'PICHARI', '0809', '08'),
            ('080911', 'INKAWASI', '0809', '08'),
            ('080912', 'VILLA VIRGEN', '0809', '08'),
            ('080913', 'VILLA KINTIARINA', '0809', '08'),
            ('080914', 'MEGANTONI', '0809', '08'),
            ('081001', 'PARURO', '0810', '08'),
            ('081002', 'ACCHA', '0810', '08'),
            ('081003', 'CCAPI', '0810', '08'),
            ('081004', 'COLCHA', '0810', '08'),
            ('081005', 'HUANOQUITE', '0810', '08'),
            ('081006', 'OMACHAÇ', '0810', '08'),
            ('081007', 'PACCARITAMBO', '0810', '08'),
            ('081008', 'PILLPINTO', '0810', '08'),
            ('081009', 'YAURISQUE', '0810', '08'),
            ('081101', 'PAUCARTAMBO', '0811', '08'),
            ('081102', 'CAICAY', '0811', '08'),
            ('081103', 'CHALLABAMBA', '0811', '08'),
            ('081104', 'COLQUEPATA', '0811', '08'),
            ('081105', 'HUANCARANI', '0811', '08'),
            ('081106', 'KOSÑIPATA', '0811', '08'),
            ('081201', 'URCOS', '0812', '08'),
            ('081202', 'ANDAHUAYLILLAS', '0812', '08'),
            ('081203', 'CAMANTI', '0812', '08'),
            ('081204', 'CCARHUAYO', '0812', '08'),
            ('081205', 'CCATCA', '0812', '08'),
            ('081206', 'CUSIPATA', '0812', '08'),
            ('081207', 'HUARO', '0812', '08'),
            ('081208', 'LUCRE', '0812', '08'),
            ('081209', 'MARCAPATA', '0812', '08'),
            ('081210', 'OCONGATE', '0812', '08'),
            ('081211', 'OROPESA', '0812', '08'),
            ('081212', 'QUIQUIJANA', '0812', '08'),
            ('081301', 'URUBAMBA', '0813', '08'),
            ('081302', 'CHINCHERO', '0813', '08'),
            ('081303', 'HUAYLLABAMBA', '0813', '08'),
            ('081304', 'MACHUPICCHU', '0813', '08'),
            ('081305', 'MARAS', '0813', '08'),
            ('081306', 'OLLANTAYTAMBO', '0813', '08'),
            ('081307', 'YUCAY', '0813', '08'),
            ('090101', 'HUANCAVELICA', '0901', '09'),
            ('090102', 'ACOBAMBILLA', '0901', '09'),
            ('090103', 'ACORIA', '0901', '09'),
            ('090104', 'CONAYCA', '0901', '09'),
            ('090105', 'CUENCA', '0901', '09'),
            ('090106', 'HUACHOCOLPA', '0901', '09'),
            ('090107', 'HUAYLLAHUARA', '0901', '09'),
            ('090108', 'IZCUCHACA', '0901', '09'),
            ('090109', 'LARIA', '0901', '09'),
            ('090110', 'MANTA', '0901', '09'),
            ('090111', 'MARISCAL CÁCERES', '0901', '09'),
            ('090112', 'MOYA', '0901', '09'),
            ('090113', 'NUEVO OCCORO', '0901', '09'),
            ('090114', 'PALCA', '0901', '09'),
            ('090115', 'PILCHACA', '0901', '09'),
            ('090116', 'VILCA', '0901', '09'),
            ('090117', 'YAULI', '0901', '09'),
            ('090118', 'ASCENSIÓN', '0901', '09'),
            ('090119', 'HUANDO', '0901', '09'),
            ('090201', 'ACOBAMBA', '0902', '09'),
            ('090202', 'ANDABAMBA', '0902', '09'),
            ('090203', 'ANTA', '0902', '09'),
            ('090204', 'CAJA', '0902', '09'),
            ('090205', 'MARCAS', '0902', '09'),
            ('090206', 'PAUCARA', '0902', '09'),
            ('090207', 'POMACOCHA', '0902', '09'),
            ('090208', 'ROSARIO', '0902', '09'),
            ('090301', 'LIRCAY', '0903', '09'),
            ('090302', 'ANCHONGA', '0903', '09'),
            ('090303', 'CALLANMARCA', '0903', '09'),
            ('090304', 'CCOCHACCASA', '0903', '09'),
            ('090305', 'CHINCHO', '0903', '09'),
            ('090306', 'CONGALLA', '0903', '09'),
            ('090307', 'HUANCA-HUANCA', '0903', '09'),
            ('090308', 'HUAYLLAY GRANDE', '0903', '09'),
            ('090309', 'JULCAMARCA', '0903', '09'),
            ('090310', 'SAN ANTONIO DE ANTAPARCO', '0903', '09'),
            ('090311', 'SANTO TOMAS DE PATA', '0903', '09'),
            ('090312', 'SECCLLA', '0903', '09'),
            ('090401', 'CASTROVIRREYNA', '0904', '09'),
            ('090402', 'ARMA', '0904', '09'),
            ('090403', 'AURAHUA', '0904', '09'),
            ('090404', 'CAPILLAS', '0904', '09'),
            ('090405', 'CHUPAMARCA', '0904', '09'),
            ('090406', 'COCAS', '0904', '09'),
            ('090407', 'HUACHOS', '0904', '09'),
            ('090408', 'HUAMATAMBO', '0904', '09'),
            ('090409', 'MOLLEPAMPA', '0904', '09'),
            ('090410', 'SAN JUAN', '0904', '09'),
            ('090411', 'SANTA ANA', '0904', '09'),
            ('090412', 'TANTARA', '0904', '09'),
            ('090413', 'TICRAPO', '0904', '09'),
            ('090501', 'CHURCAMPA', '0905', '09'),
            ('090502', 'ANCO', '0905', '09'),
            ('090503', 'CHINCHIHUASI', '0905', '09'),
            ('090504', 'EL CARMEN', '0905', '09'),
            ('090505', 'LA MERCED', '0905', '09'),
            ('090506', 'LOCROJA', '0905', '09'),
            ('090507', 'PAUCARBAMBA', '0905', '09'),
            ('090508', 'SAN MIGUEL DE MAYOCC', '0905', '09'),
            ('090509', 'SAN PEDRO DE CORIS', '0905', '09'),
            ('090510', 'PACHAMARCA', '0905', '09'),
            ('090511', 'COSME', '0905', '09'),
            ('090601', 'HUAYTARA', '0906', '09'),
            ('090602', 'AYAVI', '0906', '09'),
            ('090603', 'CÓRDOVA', '0906', '09'),
            ('090604', 'HUAYACUNDO ARMA', '0906', '09'),
            ('090605', 'LARAMARCA', '0906', '09'),
            ('090606', 'OCOYO', '0906', '09'),
            ('090607', 'PILPICHACA', '0906', '09'),
            ('090608', 'QUERCO', '0906', '09'),
            ('090609', 'QUITO-ARMA', '0906', '09'),
            ('090610', 'SAN ANTONIO DE CUSICANCHA', '0906', '09'),
            ('090611', 'SAN FRANCISCO DE SANGAYAICO', '0906', '09'),
            ('090612', 'SAN ISIDRO', '0906', '09'),
            ('090613', 'SANTIAGO DE CHOCORVOS', '0906', '09'),
            ('090614', 'SANTIAGO DE QUIRAHUARA', '0906', '09'),
            ('090615', 'SANTO DOMINGO DE CAPILLAS', '0906', '09'),
            ('090616', 'TAMBO', '0906', '09'),
            ('090701', 'PAMPAS', '0907', '09'),
            ('090702', 'ACOSTAMBO', '0907', '09'),
            ('090703', 'ACRAQUIA', '0907', '09'),
            ('090704', 'AHUAYCHA', '0907', '09'),
            ('090705', 'COLCABAMBA', '0907', '09'),
            ('090706', 'DANIEL HERNÁNDEZ', '0907', '09'),
            ('090707', 'HUACHOCOLPA', '0907', '09'),
            ('090709', 'HUARIBAMBA', '0907', '09'),
            ('090710', 'ÑAHUIMPUQUIO', '0907', '09'),
            ('090711', 'PAZOS', '0907', '09'),
            ('090713', 'QUISHUAR', '0907', '09'),
            ('090714', 'SALCABAMBA', '0907', '09'),
            ('090715', 'SALCAHUASI', '0907', '09'),
            ('090716', 'SAN MARCOS DE ROCCHAC', '0907', '09'),
            ('090717', 'SURCUBAMBA', '0907', '09'),
            ('090718', 'TINTAY PUNCU', '0907', '09'),
            ('090719', 'QUICHUAS', '0907', '09'),
            ('090720', 'ANDAYMARCA', '0907', '09'),
            ('090721', 'ROBLE', '0907', '09'),
            ('090722', 'PICHOS', '0907', '09'),
            ('090723', 'SANTIAGO DE TUCUMA', '0907', '09'),
            ('100101', 'HUANUCO', '1001', '10'),
            ('100102', 'AMARILIS', '1001', '10'),
            ('100103', 'CHINCHAO', '1001', '10'),
            ('100104', 'CHURUBAMBA', '1001', '10'),
            ('100105', 'MARGOS', '1001', '10'),
            ('100106', 'QUISQUI (KICHKI)', '1001', '10'),
            ('100107', 'SAN FRANCISCO DE CAYRAN', '1001', '10'),
            ('100108', 'SAN PEDRO DE CHAULAN', '1001', '10'),
            ('100109', 'SANTA MARÍA DEL VALLE', '1001', '10'),
            ('100110', 'YARUMAYO', '1001', '10'),
            ('100111', 'PILLCO MARCA', '1001', '10'),
            ('100112', 'YACUS', '1001', '10'),
            ('100113', 'SAN PABLO DE PILLAO', '1001', '10'),
            ('100201', 'AMBO', '1002', '10'),
            ('100202', 'CAYNA', '1002', '10'),
            ('100203', 'COLPAS', '1002', '10'),
            ('100204', 'CONCHAMARCA', '1002', '10'),
            ('100205', 'HUACAR', '1002', '10'),
            ('100206', 'SAN FRANCISCO', '1002', '10'),
            ('100207', 'SAN RAFAEL', '1002', '10'),
            ('100208', 'TOMAY KICHWA', '1002', '10'),
            ('100301', 'LA UNIÓN', '1003', '10'),
            ('100307', 'CHUQUIS', '1003', '10'),
            ('100311', 'MARÍAS', '1003', '10'),
            ('100313', 'PACHAS', '1003', '10'),
            ('100316', 'QUIVILLA', '1003', '10'),
            ('100317', 'RIPAN', '1003', '10'),
            ('100321', 'SHUNQUI', '1003', '10'),
            ('100322', 'SILLAPATA', '1003', '10'),
            ('100323', 'YANAS', '1003', '10'),
            ('100401', 'HUACAYBAMBA', '1004', '10'),
            ('100402', 'CANCHABAMBA', '1004', '10'),
            ('100403', 'COCHABAMBA', '1004', '10'),
            ('100404', 'PINRA', '1004', '10'),
            ('100501', 'LLATA', '1005', '10'),
            ('100502', 'ARANCAY', '1005', '10'),
            ('100503', 'CHAVÍN DE PARIARCA', '1005', '10'),
            ('100504', 'JACAS GRANDE', '1005', '10'),
            ('100505', 'JIRCAN', '1005', '10'),
            ('100506', 'MIRAFLORES', '1005', '10'),
            ('100507', 'MONZÓN', '1005', '10'),
            ('100508', 'PUNCHAO', '1005', '10'),
            ('100509', 'PUÑOS', '1005', '10'),
            ('100510', 'SINGA', '1005', '10'),
            ('100511', 'TANTAMAYO', '1005', '10'),
            ('100601', 'RUPA-RUPA', '1006', '10'),
            ('100602', 'DANIEL ALOMÍA ROBLES', '1006', '10'),
            ('100603', 'HERMÍLIO VALDIZAN', '1006', '10'),
            ('100604', 'JOSÉ CRESPO Y CASTILLO', '1006', '10'),
            ('100605', 'LUYANDO', '1006', '10'),
            ('100606', 'MARIANO DAMASO BERAUN', '1006', '10'),
            ('100607', 'PUCAYACU', '1006', '10'),
            ('100608', 'CASTILLO GRANDE', '1006', '10'),
            ('100609', 'PUEBLO NUEVO', '1006', '10'),
            ('100610', 'SANTO DOMINGO DE ANDA', '1006', '10'),
            ('100701', 'HUACRACHUCO', '1007', '10'),
            ('100702', 'CHOLON', '1007', '10'),
            ('100703', 'SAN BUENAVENTURA', '1007', '10'),
            ('100704', 'LA MORADA', '1007', '10'),
            ('100705', 'SANTA ROSA DE ALTO YANAJANCA', '1007', '10'),
            ('100801', 'PANAO', '1008', '10'),
            ('100802', 'CHAGLLA', '1008', '10'),
            ('100803', 'MOLINO', '1008', '10'),
            ('100804', 'UMARI', '1008', '10'),
            ('100901', 'PUERTO INCA', '1009', '10'),
            ('100902', 'CODO DEL POZUZO', '1009', '10'),
            ('100903', 'HONORIA', '1009', '10'),
            ('100904', 'TOURNAVISTA', '1009', '10'),
            ('100905', 'YUYAPICHIS', '1009', '10'),
            ('101001', 'JESÚS', '1010', '10'),
            ('101002', 'BAÑOS', '1010', '10'),
            ('101003', 'JIVIA', '1010', '10'),
            ('101004', 'QUEROPALCA', '1010', '10'),
            ('101005', 'RONDOS', '1010', '10'),
            ('101006', 'SAN FRANCISCO DE ASÍS', '1010', '10'),
            ('101007', 'SAN MIGUEL DE CAURI', '1010', '10'),
            ('101101', 'CHAVINILLO', '1011', '10'),
            ('101102', 'CAHUAC', '1011', '10'),
            ('101103', 'CHACABAMBA', '1011', '10'),
            ('101104', 'APARICIO POMARES', '1011', '10'),
            ('101105', 'JACAS CHICO', '1011', '10'),
            ('101106', 'OBAS', '1011', '10'),
            ('101107', 'PAMPAMARCA', '1011', '10'),
            ('101108', 'CHORAS', '1011', '10'),
            ('110101', 'ICA', '1101', '11'),
            ('110102', 'LA TINGUIÑA', '1101', '11'),
            ('110103', 'LOS AQUIJES', '1101', '11'),
            ('110104', 'OCUCAJE', '1101', '11'),
            ('110105', 'PACHACUTEC', '1101', '11'),
            ('110106', 'PARCONA', '1101', '11'),
            ('110107', 'PUEBLO NUEVO', '1101', '11'),
            ('110108', 'SALAS', '1101', '11'),
            ('110109', 'SAN JOSÉ DE LOS MOLINOS', '1101', '11'),
            ('110110', 'SAN JUAN BAUTISTA', '1101', '11'),
            ('110111', 'SANTIAGO', '1101', '11'),
            ('110112', 'SUBTANJALLA', '1101', '11'),
            ('110113', 'TATE', '1101', '11'),
            ('110114', 'YAUCA DEL ROSARIO', '1101', '11'),
            ('110201', 'CHINCHA ALTA', '1102', '11'),
            ('110202', 'ALTO LARAN', '1102', '11'),
            ('110203', 'CHAVIN', '1102', '11'),
            ('110204', 'CHINCHA BAJA', '1102', '11'),
            ('110205', 'EL CARMEN', '1102', '11'),
            ('110206', 'GROCIO PRADO', '1102', '11'),
            ('110207', 'PUEBLO NUEVO', '1102', '11'),
            ('110208', 'SAN JUAN DE YANAC', '1102', '11'),
            ('110209', 'SAN PEDRO DE HUACARPANA', '1102', '11'),
            ('110210', 'SUNAMPE', '1102', '11'),
            ('110211', 'TAMBO DE MORA', '1102', '11'),
            ('110301', 'NASCA', '1103', '11'),
            ('110302', 'CHANGUILLO', '1103', '11'),
            ('110303', 'EL INGENIO', '1103', '11'),
            ('110304', 'MARCONA', '1103', '11'),
            ('110305', 'VISTA ALEGRE', '1103', '11'),
            ('110401', 'PALPA', '1104', '11'),
            ('110402', 'LLIPATA', '1104', '11'),
            ('110403', 'RÍO GRANDE', '1104', '11'),
            ('110404', 'SANTA CRUZ', '1104', '11'),
            ('110405', 'TIBILLO', '1104', '11'),
            ('110501', 'PISCO', '1105', '11'),
            ('110502', 'HUANCANO', '1105', '11'),
            ('110503', 'HUMAY', '1105', '11'),
            ('110504', 'INDEPENDENCIA', '1105', '11'),
            ('110505', 'PARACAS', '1105', '11'),
            ('110506', 'SAN ANDRÉS', '1105', '11'),
            ('110507', 'SAN CLEMENTE', '1105', '11'),
            ('110508', 'TUPAC AMARU INCA', '1105', '11'),
            ('120101', 'HUANCAYO', '1201', '12'),
            ('120104', 'CARHUACALLANGA', '1201', '12'),
            ('120105', 'CHACAPAMPA', '1201', '12'),
            ('120106', 'CHICCHE', '1201', '12'),
            ('120107', 'CHILCA', '1201', '12'),
            ('120108', 'CHONGOS ALTO', '1201', '12'),
            ('120111', 'CHUPURO', '1201', '12'),
            ('120112', 'COLCA', '1201', '12'),
            ('120113', 'CULLHUAS', '1201', '12'),
            ('120114', 'EL TAMBO', '1201', '12'),
            ('120116', 'HUACRAPUQUIO', '1201', '12'),
            ('120117', 'HUALHUAS', '1201', '12'),
            ('120119', 'HUANCAN', '1201', '12'),
            ('120120', 'HUASICANCHA', '1201', '12'),
            ('120121', 'HUAYUCACHI', '1201', '12'),
            ('120122', 'INGENIO', '1201', '12'),
            ('120124', 'PARIAHUANCA', '1201', '12'),
            ('120125', 'PILCOMAYO', '1201', '12'),
            ('120126', 'PUCARA', '1201', '12'),
            ('120127', 'QUICHUAY', '1201', '12'),
            ('120128', 'QUILCAS', '1201', '12'),
            ('120129', 'SAN AGUSTÍN', '1201', '12'),
            ('120130', 'SAN JERÓNIMO DE TUNAN', '1201', '12'),
            ('120132', 'SAÑO', '1201', '12'),
            ('120133', 'SAPALLANGA', '1201', '12'),
            ('120134', 'SICAYA', '1201', '12'),
            ('120135', 'SANTO DOMINGO DE ACOBAMBA', '1201', '12'),
            ('120136', 'VIQUES', '1201', '12'),
            ('120201', 'CONCEPCIÓN', '1202', '12'),
            ('120202', 'ACO', '1202', '12'),
            ('120203', 'ANDAMARCA', '1202', '12'),
            ('120204', 'CHAMBARA', '1202', '12'),
            ('120205', 'COCHAS', '1202', '12'),
            ('120206', 'COMAS', '1202', '12'),
            ('120207', 'HEROÍNAS TOLEDO', '1202', '12'),
            ('120208', 'MANZANARES', '1202', '12'),
            ('120209', 'MARISCAL CASTILLA', '1202', '12'),
            ('120210', 'MATAHUASI', '1202', '12'),
            ('120211', 'MITO', '1202', '12'),
            ('120212', 'NUEVE DE JULIO', '1202', '12'),
            ('120213', 'ORCOTUNA', '1202', '12'),
            ('120214', 'SAN JOSÉ DE QUERO', '1202', '12'),
            ('120215', 'SANTA ROSA DE OCOPA', '1202', '12'),
            ('120301', 'CHANCHAMAYO', '1203', '12'),
            ('120302', 'PERENE', '1203', '12'),
            ('120303', 'PICHANAQUI', '1203', '12'),
            ('120304', 'SAN LUIS DE SHUARO', '1203', '12'),
            ('120305', 'SAN RAMÓN', '1203', '12'),
            ('120306', 'VITOC', '1203', '12'),
            ('120401', 'JAUJA', '1204', '12'),
            ('120402', 'ACOLLA', '1204', '12'),
            ('120403', 'APATA', '1204', '12'),
            ('120404', 'ATAURA', '1204', '12'),
            ('120405', 'CANCHAYLLO', '1204', '12'),
            ('120406', 'CURICACA', '1204', '12'),
            ('120407', 'EL MANTARO', '1204', '12'),
            ('120408', 'HUAMALI', '1204', '12'),
            ('120409', 'HUARIPAMPA', '1204', '12'),
            ('120410', 'HUERTAS', '1204', '12'),
            ('120411', 'JANJAILLO', '1204', '12'),
            ('120412', 'JULCÁN', '1204', '12'),
            ('120413', 'LEONOR ORDÓÑEZ', '1204', '12'),
            ('120414', 'LLOCLLAPAMPA', '1204', '12'),
            ('120415', 'MARCO', '1204', '12'),
            ('120416', 'MASMA', '1204', '12'),
            ('120417', 'MASMA CHICCHE', '1204', '12'),
            ('120418', 'MOLINOS', '1204', '12'),
            ('120419', 'MONOBAMBA', '1204', '12'),
            ('120420', 'MUQUI', '1204', '12'),
            ('120421', 'MUQUIYAUYO', '1204', '12'),
            ('120422', 'PACA', '1204', '12'),
            ('120423', 'PACCHA', '1204', '12'),
            ('120424', 'PANCAN', '1204', '12'),
            ('120425', 'PARCO', '1204', '12'),
            ('120426', 'POMACANCHA', '1204', '12'),
            ('120427', 'RICRAN', '1204', '12'),
            ('120428', 'SAN LORENZO', '1204', '12'),
            ('120429', 'SAN PEDRO DE CHUNAN', '1204', '12'),
            ('120430', 'SAUSA', '1204', '12'),
            ('120431', 'SINCOS', '1204', '12'),
            ('120432', 'TUNAN MARCA', '1204', '12'),
            ('120433', 'YAULI', '1204', '12'),
            ('120434', 'YAUYOS', '1204', '12'),
            ('120501', 'JUNIN', '1205', '12'),
            ('120502', 'CARHUAMAYO', '1205', '12'),
            ('120503', 'ONDORES', '1205', '12'),
            ('120504', 'ULCUMAYO', '1205', '12'),
            ('120601', 'SATIPO', '1206', '12'),
            ('120602', 'COVIRIALI', '1206', '12'),
            ('120603', 'LLAYLLA', '1206', '12'),
            ('120604', 'MAZAMARI', '1206', '12'),
            ('120605', 'PAMPA HERMOSA', '1206', '12'),
            ('120606', 'PANGOA', '1206', '12'),
            ('120607', 'RÍO NEGRO', '1206', '12'),
            ('120608', 'RÍO TAMBO', '1206', '12'),
            ('120609', 'VIZCATAN DEL ENE', '1206', '12'),
            ('120701', 'TARMA', '1207', '12'),
            ('120702', 'ACOBAMBA', '1207', '12'),
            ('120703', 'HUARICOLCA', '1207', '12'),
            ('120704', 'HUASAHUASI', '1207', '12'),
            ('120705', 'LA UNIÓN', '1207', '12'),
            ('120706', 'PALCA', '1207', '12'),
            ('120707', 'PALCAMAYO', '1207', '12'),
            ('120708', 'SAN PEDRO DE CAJAS', '1207', '12'),
            ('120709', 'TAPO', '1207', '12'),
            ('120801', 'LA OROYA', '1208', '12'),
            ('120802', 'CHACAPALPA', '1208', '12'),
            ('120803', 'HUAY-HUAY', '1208', '12'),
            ('120804', 'MARCAPOMACOCHA', '1208', '12'),
            ('120805', 'MOROCOCHA', '1208', '12'),
            ('120806', 'PACCHA', '1208', '12'),
            ('120807', 'SANTA BÁRBARA DE CARHUACAYAN', '1208', '12'),
            ('120808', 'SANTA ROSA DE SACCO', '1208', '12'),
            ('120809', 'SUITUCANCHA', '1208', '12'),
            ('120810', 'YAULI', '1208', '12'),
            ('120901', 'CHUPACA', '1209', '12'),
            ('120902', 'AHUAC', '1209', '12'),
            ('120903', 'CHONGOS BAJO', '1209', '12'),
            ('120904', 'HUACHAC', '1209', '12'),
            ('120905', 'HUAMANCACA CHICO', '1209', '12'),
            ('120906', 'SAN JUAN DE ISCOS', '1209', '12'),
            ('120907', 'SAN JUAN DE JARPA', '1209', '12'),
            ('120908', 'TRES DE DICIEMBRE', '1209', '12'),
            ('120909', 'YANACANCHA', '1209', '12'),
            ('130101', 'TRUJILLO', '1301', '13'),
            ('130102', 'EL PORVENIR', '1301', '13'),
            ('130103', 'FLORENCIA DE MORA', '1301', '13'),
            ('130104', 'HUANCHACO', '1301', '13'),
            ('130105', 'LA ESPERANZA', '1301', '13'),
            ('130106', 'LAREDO', '1301', '13'),
            ('130107', 'MOCHE', '1301', '13'),
            ('130108', 'POROTO', '1301', '13'),
            ('130109', 'SALAVERRY', '1301', '13'),
            ('130110', 'SIMBAL', '1301', '13'),
            ('130111', 'VICTOR LARCO HERRERA', '1301', '13'),
            ('130201', 'ASCOPE', '1302', '13'),
            ('130202', 'CHICAMA', '1302', '13'),
            ('130203', 'CHOCOPE', '1302', '13'),
            ('130204', 'MAGDALENA DE CAO', '1302', '13'),
            ('130205', 'PAIJAN', '1302', '13'),
            ('130206', 'RÁZURI', '1302', '13'),
            ('130207', 'SANTIAGO DE CAO', '1302', '13'),
            ('130208', 'CASA GRANDE', '1302', '13'),
            ('130301', 'BOLÍVAR', '1303', '13'),
            ('130302', 'BAMBAMARCA', '1303', '13'),
            ('130303', 'CONDORMARCA', '1303', '13'),
            ('130304', 'LONGOTEA', '1303', '13'),
            ('130305', 'UCHUMARCA', '1303', '13'),
            ('130306', 'UCUNCHA', '1303', '13'),
            ('130401', 'CHEPEN', '1304', '13'),
            ('130402', 'PACANGA', '1304', '13'),
            ('130403', 'PUEBLO NUEVO', '1304', '13'),
            ('130501', 'JULCAN', '1305', '13'),
            ('130502', 'CALAMARCA', '1305', '13'),
            ('130503', 'CARABAMBA', '1305', '13'),
            ('130504', 'HUASO', '1305', '13'),
            ('130601', 'OTUZCO', '1306', '13'),
            ('130602', 'AGALLPAMPA', '1306', '13'),
            ('130604', 'CHARAT', '1306', '13'),
            ('130605', 'HUARANCHAL', '1306', '13'),
            ('130606', 'LA CUESTA', '1306', '13'),
            ('130608', 'MACHE', '1306', '13'),
            ('130610', 'PARANDAY', '1306', '13'),
            ('130611', 'SALPO', '1306', '13'),
            ('130613', 'SINSICAP', '1306', '13'),
            ('130614', 'USQUIL', '1306', '13'),
            ('130701', 'SAN PEDRO DE LLOC', '1307', '13'),
            ('130702', 'GUADALUPE', '1307', '13'),
            ('130703', 'JEQUETEPEQUE', '1307', '13'),
            ('130704', 'PACASMAYO', '1307', '13'),
            ('130705', 'SAN JOSÉ', '1307', '13'),
            ('130801', 'TAYABAMBA', '1308', '13'),
            ('130802', 'BULDIBUYO', '1308', '13'),
            ('130803', 'CHILLIA', '1308', '13'),
            ('130804', 'HUANCASPATA', '1308', '13'),
            ('130805', 'HUAYLILLAS', '1308', '13'),
            ('130806', 'HUAYO', '1308', '13'),
            ('130807', 'ONGON', '1308', '13'),
            ('130808', 'PARCOY', '1308', '13'),
            ('130809', 'PATAZ', '1308', '13'),
            ('130810', 'PIAS', '1308', '13'),
            ('130811', 'SANTIAGO DE CHALLAS', '1308', '13'),
            ('130812', 'TAURIJA', '1308', '13'),
            ('130813', 'URPAY', '1308', '13'),
            ('130901', 'HUAMACHUCO', '1309', '13'),
            ('130902', 'CHUGAY', '1309', '13'),
            ('130903', 'COCHORCO', '1309', '13'),
            ('130904', 'CURGOS', '1309', '13'),
            ('130905', 'MARCABAL', '1309', '13'),
            ('130906', 'SANAGORAN', '1309', '13'),
            ('130907', 'SARIN', '1309', '13'),
            ('130908', 'SARTIMBAMBA', '1309', '13'),
            ('131001', 'SANTIAGO DE CHUCO', '1310', '13'),
            ('131002', 'ANGASMARCA', '1310', '13'),
            ('131003', 'CACHICADAN', '1310', '13'),
            ('131004', 'MOLLEBAMBA', '1310', '13'),
            ('131005', 'MOLLEPATA', '1310', '13'),
            ('131006', 'QUIRUVILCA', '1310', '13'),
            ('131007', 'SANTA CRUZ DE CHUCA', '1310', '13'),
            ('131008', 'SITABAMBA', '1310', '13'),
            ('131101', 'CASCAS', '1311', '13'),
            ('131102', 'LUCMA', '1311', '13'),
            ('131103', 'MARMOT', '1311', '13'),
            ('131104', 'SAYAPULLO', '1311', '13'),
            ('131201', 'VIRU', '1312', '13'),
            ('131202', 'CHAO', '1312', '13'),
            ('131203', 'GUADALUPITO', '1312', '13'),
            ('140101', 'CHICLAYO', '1401', '14'),
            ('140102', 'CHONGOYAPE', '1401', '14'),
            ('140103', 'ETEN', '1401', '14'),
            ('140104', 'ETEN PUERTO', '1401', '14'),
            ('140105', 'JOSÉ LEONARDO ORTIZ', '1401', '14'),
            ('140106', 'LA VICTORIA', '1401', '14'),
            ('140107', 'LAGUNAS', '1401', '14'),
            ('140108', 'MONSEFU', '1401', '14'),
            ('140109', 'NUEVA ARICA', '1401', '14'),
            ('140110', 'OYOTUN', '1401', '14'),
            ('140111', 'PICSI', '1401', '14'),
            ('140112', 'PIMENTEL', '1401', '14'),
            ('140113', 'REQUE', '1401', '14'),
            ('140114', 'SANTA ROSA', '1401', '14'),
            ('140115', 'SAÑA', '1401', '14'),
            ('140116', 'CAYALTI', '1401', '14'),
            ('140117', 'PATAPO', '1401', '14'),
            ('140118', 'POMALCA', '1401', '14'),
            ('140119', 'PUCALA', '1401', '14'),
            ('140120', 'TUMAN', '1401', '14'),
            ('140201', 'FERREÑAFE', '1402', '14'),
            ('140202', 'CAÑARIS', '1402', '14'),
            ('140203', 'INCAHUASI', '1402', '14'),
            ('140204', 'MANUEL ANTONIO MESONES MURO', '1402', '14'),
            ('140205', 'PITIPO', '1402', '14'),
            ('140206', 'PUEBLO NUEVO', '1402', '14'),
            ('140301', 'LAMBAYEQUE', '1403', '14'),
            ('140302', 'CHOCHOPE', '1403', '14'),
            ('140303', 'ILLIMO', '1403', '14'),
            ('140304', 'JAYANCA', '1403', '14'),
            ('140305', 'MOCHUMI', '1403', '14'),
            ('140306', 'MORROPE', '1403', '14'),
            ('140307', 'MOTUPE', '1403', '14'),
            ('140308', 'OLMOS', '1403', '14'),
            ('140309', 'PACORA', '1403', '14'),
            ('140310', 'SALAS', '1403', '14'),
            ('140311', 'SAN JOSÉ', '1403', '14'),
            ('140312', 'TUCUME', '1403', '14'),
            ('150101', 'LIMA', '1501', '15'),
            ('150102', 'ANCÓN', '1501', '15'),
            ('150103', 'ATE', '1501', '15'),
            ('150104', 'BARRANCO', '1501', '15'),
            ('150105', 'BREÑA', '1501', '15'),
            ('150106', 'CARABAYLLO', '1501', '15'),
            ('150107', 'CHACLACAYO', '1501', '15'),
            ('150108', 'CHORRILLOS', '1501', '15'),
            ('150109', 'CIENEGUILLA', '1501', '15'),
            ('150110', 'COMAS', '1501', '15'),
            ('150111', 'EL AGUSTINO', '1501', '15'),
            ('150112', 'INDEPENDENCIA', '1501', '15'),
            ('150113', 'JESÚS MARÍA', '1501', '15'),
            ('150114', 'LA MOLINA', '1501', '15'),
            ('150115', 'LA VICTORIA', '1501', '15'),
            ('150116', 'LINCE', '1501', '15'),
            ('150117', 'LOS OLIVOS', '1501', '15'),
            ('150118', 'LURIGANCHO', '1501', '15'),
            ('150119', 'LURIN', '1501', '15'),
            ('150120', 'MAGDALENA DEL MAR', '1501', '15'),
            ('150121', 'PUEBLO LIBRE', '1501', '15'),
            ('150122', 'MIRAFLORES', '1501', '15'),
            ('150123', 'PACHACAMAC', '1501', '15'),
            ('150124', 'PUCUSANA', '1501', '15'),
            ('150125', 'PUENTE PIEDRA', '1501', '15'),
            ('150126', 'PUNTA HERMOSA', '1501', '15'),
            ('150127', 'PUNTA NEGRA', '1501', '15'),
            ('150128', 'RÍMAC', '1501', '15'),
            ('150129', 'SAN BARTOLO', '1501', '15'),
            ('150130', 'SAN BORJA', '1501', '15'),
            ('150131', 'SAN ISIDRO', '1501', '15'),
            ('150132', 'SAN JUAN DE LURIGANCHO', '1501', '15'),
            ('150133', 'SAN JUAN DE MIRAFLORES', '1501', '15'),
            ('150134', 'SAN LUIS', '1501', '15'),
            ('150135', 'SAN MARTÍN DE PORRES', '1501', '15'),
            ('150136', 'SAN MIGUEL', '1501', '15'),
            ('150137', 'SANTA ANITA', '1501', '15'),
            ('150138', 'SANTA MARÍA DEL MAR', '1501', '15'),
            ('150139', 'SANTA ROSA', '1501', '15'),
            ('150140', 'SANTIAGO DE SURCO', '1501', '15'),
            ('150141', 'SURQUILLO', '1501', '15'),
            ('150142', 'VILLA EL SALVADOR', '1501', '15'),
            ('150143', 'VILLA MARÍA DEL TRIUNFO', '1501', '15'),
            ('150201', 'BARRANCA', '1502', '15'),
            ('150202', 'PARAMONGA', '1502', '15'),
            ('150203', 'PATIVILCA', '1502', '15'),
            ('150204', 'SUPE', '1502', '15'),
            ('150205', 'SUPE PUERTO', '1502', '15'),
            ('150301', 'CAJATAMBO', '1503', '15'),
            ('150302', 'COPA', '1503', '15'),
            ('150303', 'GORGOR', '1503', '15'),
            ('150304', 'HUANCAPON', '1503', '15'),
            ('150305', 'MANAS', '1503', '15'),
            ('150401', 'CANTA', '1504', '15'),
            ('150402', 'ARAHUAY', '1504', '15'),
            ('150403', 'HUAMANTANGA', '1504', '15'),
            ('150404', 'HUAROS', '1504', '15'),
            ('150405', 'LACHAQUI', '1504', '15'),
            ('150406', 'SAN BUENAVENTURA', '1504', '15'),
            ('150407', 'SANTA ROSA DE QUIVES', '1504', '15');
            INSERT INTO `ubigeo_distrito` (`id`, `nombre_distrito`, `province_id`, `department_id`) VALUES
            ('150501', 'SAN VICENTE DE CAÑETE', '1505', '15'),
            ('150502', 'ASIA', '1505', '15'),
            ('150503', 'CALANGO', '1505', '15'),
            ('150504', 'CERRO AZUL', '1505', '15'),
            ('150505', 'CHILCA', '1505', '15'),
            ('150506', 'COAYLLO', '1505', '15'),
            ('150507', 'IMPERIAL', '1505', '15'),
            ('150508', 'LUNAHUANA', '1505', '15'),
            ('150509', 'MALA', '1505', '15'),
            ('150510', 'NUEVO IMPERIAL', '1505', '15'),
            ('150511', 'PACARAN', '1505', '15'),
            ('150512', 'QUILMANA', '1505', '15'),
            ('150513', 'SAN ANTONIO', '1505', '15'),
            ('150514', 'SAN LUIS', '1505', '15'),
            ('150515', 'SANTA CRUZ DE FLORES', '1505', '15'),
            ('150516', 'ZÚÑIGA', '1505', '15'),
            ('150601', 'HUARAL', '1506', '15'),
            ('150602', 'ATAVILLOS ALTO', '1506', '15'),
            ('150603', 'ATAVILLOS BAJO', '1506', '15'),
            ('150604', 'AUCALLAMA', '1506', '15'),
            ('150605', 'CHANCAY', '1506', '15'),
            ('150606', 'IHUARI', '1506', '15'),
            ('150607', 'LAMPIAN', '1506', '15'),
            ('150608', 'PACARAOS', '1506', '15'),
            ('150609', 'SAN MIGUEL DE ACOS', '1506', '15'),
            ('150610', 'SANTA CRUZ DE ANDAMARCA', '1506', '15'),
            ('150611', 'SUMBILCA', '1506', '15'),
            ('150612', 'VEINTISIETE DE NOVIEMBRE', '1506', '15'),
            ('150701', 'MATUCANA', '1507', '15'),
            ('150702', 'ANTIOQUIA', '1507', '15'),
            ('150703', 'CALLAHUANCA', '1507', '15'),
            ('150704', 'CARAMPOMA', '1507', '15'),
            ('150705', 'CHICLA', '1507', '15'),
            ('150706', 'CUENCA', '1507', '15'),
            ('150707', 'HUACHUPAMPA', '1507', '15'),
            ('150708', 'HUANZA', '1507', '15'),
            ('150709', 'HUAROCHIRI', '1507', '15'),
            ('150710', 'LAHUAYTAMBO', '1507', '15'),
            ('150711', 'LANGA', '1507', '15'),
            ('150712', 'LARAOS', '1507', '15'),
            ('150713', 'MARIATANA', '1507', '15'),
            ('150714', 'RICARDO PALMA', '1507', '15'),
            ('150715', 'SAN ANDRÉS DE TUPICOCHA', '1507', '15'),
            ('150716', 'SAN ANTONIO', '1507', '15'),
            ('150717', 'SAN BARTOLOMÉ', '1507', '15'),
            ('150718', 'SAN DAMIAN', '1507', '15'),
            ('150719', 'SAN JUAN DE IRIS', '1507', '15'),
            ('150720', 'SAN JUAN DE TANTARANCHE', '1507', '15'),
            ('150721', 'SAN LORENZO DE QUINTI', '1507', '15'),
            ('150722', 'SAN MATEO', '1507', '15'),
            ('150723', 'SAN MATEO DE OTAO', '1507', '15'),
            ('150724', 'SAN PEDRO DE CASTA', '1507', '15'),
            ('150725', 'SAN PEDRO DE HUANCAYRE', '1507', '15'),
            ('150726', 'SANGALLAYA', '1507', '15'),
            ('150727', 'SANTA CRUZ DE COCACHACRA', '1507', '15'),
            ('150728', 'SANTA EULALIA', '1507', '15'),
            ('150729', 'SANTIAGO DE ANCHUCAYA', '1507', '15'),
            ('150730', 'SANTIAGO DE TUNA', '1507', '15'),
            ('150731', 'SANTO DOMINGO DE LOS OLLEROS', '1507', '15'),
            ('150732', 'SURCO', '1507', '15'),
            ('150801', 'HUACHO', '1508', '15'),
            ('150802', 'AMBAR', '1508', '15'),
            ('150803', 'CALETA DE CARQUIN', '1508', '15'),
            ('150804', 'CHECRAS', '1508', '15'),
            ('150805', 'HUALMAY', '1508', '15'),
            ('150806', 'HUAURA', '1508', '15'),
            ('150807', 'LEONCIO PRADO', '1508', '15'),
            ('150808', 'PACCHO', '1508', '15'),
            ('150809', 'SANTA LEONOR', '1508', '15'),
            ('150810', 'SANTA MARÍA', '1508', '15'),
            ('150811', 'SAYAN', '1508', '15'),
            ('150812', 'VEGUETA', '1508', '15'),
            ('150901', 'OYON', '1509', '15'),
            ('150902', 'ANDAJES', '1509', '15'),
            ('150903', 'CAUJUL', '1509', '15'),
            ('150904', 'COCHAMARCA', '1509', '15'),
            ('150905', 'NAVAN', '1509', '15'),
            ('150906', 'PACHANGARA', '1509', '15'),
            ('151001', 'YAUYOS', '1510', '15'),
            ('151002', 'ALIS', '1510', '15'),
            ('151003', 'ALLAUCA', '1510', '15'),
            ('151004', 'AYAVIRI', '1510', '15'),
            ('151005', 'AZÁNGARO', '1510', '15'),
            ('151006', 'CACRA', '1510', '15'),
            ('151007', 'CARANIA', '1510', '15'),
            ('151008', 'CATAHUASI', '1510', '15'),
            ('151009', 'CHOCOS', '1510', '15'),
            ('151010', 'COCHAS', '1510', '15'),
            ('151011', 'COLONIA', '1510', '15'),
            ('151012', 'HONGOS', '1510', '15'),
            ('151013', 'HUAMPARA', '1510', '15'),
            ('151014', 'HUANCAYA', '1510', '15'),
            ('151015', 'HUANGASCAR', '1510', '15'),
            ('151016', 'HUANTAN', '1510', '15'),
            ('151017', 'HUAÑEC', '1510', '15'),
            ('151018', 'LARAOS', '1510', '15'),
            ('151019', 'LINCHA', '1510', '15'),
            ('151020', 'MADEAN', '1510', '15'),
            ('151021', 'MIRAFLORES', '1510', '15'),
            ('151022', 'OMAS', '1510', '15'),
            ('151023', 'PUTINZA', '1510', '15'),
            ('151024', 'QUINCHES', '1510', '15'),
            ('151025', 'QUINOCAY', '1510', '15'),
            ('151026', 'SAN JOAQUÍN', '1510', '15'),
            ('151027', 'SAN PEDRO DE PILAS', '1510', '15'),
            ('151028', 'TANTA', '1510', '15'),
            ('151029', 'TAURIPAMPA', '1510', '15'),
            ('151030', 'TOMAS', '1510', '15'),
            ('151031', 'TUPE', '1510', '15'),
            ('151032', 'VIÑAC', '1510', '15'),
            ('151033', 'VITIS', '1510', '15'),
            ('160101', 'IQUITOS', '1601', '16'),
            ('160102', 'ALTO NANAY', '1601', '16'),
            ('160103', 'FERNANDO LORES', '1601', '16'),
            ('160104', 'INDIANA', '1601', '16'),
            ('160105', 'LAS AMAZONAS', '1601', '16'),
            ('160106', 'MAZAN', '1601', '16'),
            ('160107', 'NAPO', '1601', '16'),
            ('160108', 'PUNCHANA', '1601', '16'),
            ('160110', 'TORRES CAUSANA', '1601', '16'),
            ('160112', 'BELÉN', '1601', '16'),
            ('160113', 'SAN JUAN BAUTISTA', '1601', '16'),
            ('160201', 'YURIMAGUAS', '1602', '16'),
            ('160202', 'BALSAPUERTO', '1602', '16'),
            ('160205', 'JEBEROS', '1602', '16'),
            ('160206', 'LAGUNAS', '1602', '16'),
            ('160210', 'SANTA CRUZ', '1602', '16'),
            ('160211', 'TENIENTE CESAR LÓPEZ ROJAS', '1602', '16'),
            ('160301', 'NAUTA', '1603', '16'),
            ('160302', 'PARINARI', '1603', '16'),
            ('160303', 'TIGRE', '1603', '16'),
            ('160304', 'TROMPETEROS', '1603', '16'),
            ('160305', 'URARINAS', '1603', '16'),
            ('160401', 'RAMÓN CASTILLA', '1604', '16'),
            ('160402', 'PEBAS', '1604', '16'),
            ('160403', 'YAVARI', '1604', '16'),
            ('160404', 'SAN PABLO', '1604', '16'),
            ('160501', 'REQUENA', '1605', '16'),
            ('160502', 'ALTO TAPICHE', '1605', '16'),
            ('160503', 'CAPELO', '1605', '16'),
            ('160504', 'EMILIO SAN MARTÍN', '1605', '16'),
            ('160505', 'MAQUIA', '1605', '16'),
            ('160506', 'PUINAHUA', '1605', '16'),
            ('160507', 'SAQUENA', '1605', '16'),
            ('160508', 'SOPLIN', '1605', '16'),
            ('160509', 'TAPICHE', '1605', '16'),
            ('160510', 'JENARO HERRERA', '1605', '16'),
            ('160511', 'YAQUERANA', '1605', '16'),
            ('160601', 'CONTAMANA', '1606', '16'),
            ('160602', 'INAHUAYA', '1606', '16'),
            ('160603', 'PADRE MÁRQUEZ', '1606', '16'),
            ('160604', 'PAMPA HERMOSA', '1606', '16'),
            ('160605', 'SARAYACU', '1606', '16'),
            ('160606', 'VARGAS GUERRA', '1606', '16'),
            ('160701', 'BARRANCA', '1607', '16'),
            ('160702', 'CAHUAPANAS', '1607', '16'),
            ('160703', 'MANSERICHE', '1607', '16'),
            ('160704', 'MORONA', '1607', '16'),
            ('160705', 'PASTAZA', '1607', '16'),
            ('160706', 'ANDOAS', '1607', '16'),
            ('160801', 'PUTUMAYO', '1608', '16'),
            ('160802', 'ROSA PANDURO', '1608', '16'),
            ('160803', 'TENIENTE MANUEL CLAVERO', '1608', '16'),
            ('160804', 'YAGUAS', '1608', '16'),
            ('170101', 'TAMBOPATA', '1701', '17'),
            ('170102', 'INAMBARI', '1701', '17'),
            ('170103', 'LAS PIEDRAS', '1701', '17'),
            ('170104', 'LABERINTO', '1701', '17'),
            ('170201', 'MANU', '1702', '17'),
            ('170202', 'FITZCARRALD', '1702', '17'),
            ('170203', 'MADRE DE DIOS', '1702', '17'),
            ('170204', 'HUEPETUHE', '1702', '17'),
            ('170301', 'IÑAPARI', '1703', '17'),
            ('170302', 'IBERIA', '1703', '17'),
            ('170303', 'TAHUAMANU', '1703', '17'),
            ('180101', 'MOQUEGUA', '1801', '18'),
            ('180102', 'CARUMAS', '1801', '18'),
            ('180103', 'CUCHUMBAYA', '1801', '18'),
            ('180104', 'SAMEGUA', '1801', '18'),
            ('180105', 'SAN CRISTÓBAL', '1801', '18'),
            ('180106', 'TORATA', '1801', '18'),
            ('180201', 'OMATE', '1802', '18'),
            ('180202', 'CHOJATA', '1802', '18'),
            ('180203', 'COALAQUE', '1802', '18'),
            ('180204', 'ICHUÑA', '1802', '18'),
            ('180205', 'LA CAPILLA', '1802', '18'),
            ('180206', 'LLOQUE', '1802', '18'),
            ('180207', 'MATALAQUE', '1802', '18'),
            ('180208', 'PUQUINA', '1802', '18'),
            ('180209', 'QUINISTAQUILLAS', '1802', '18'),
            ('180210', 'UBINAS', '1802', '18'),
            ('180211', 'YUNGA', '1802', '18'),
            ('180301', 'ILO', '1803', '18'),
            ('180302', 'EL ALGARROBAL', '1803', '18'),
            ('180303', 'PACOCHA', '1803', '18'),
            ('190101', 'CHAUPIMARCA', '1901', '19'),
            ('190102', 'HUACHON', '1901', '19'),
            ('190103', 'HUARIACA', '1901', '19'),
            ('190104', 'HUAYLLAY', '1901', '19'),
            ('190105', 'NINACACA', '1901', '19'),
            ('190106', 'PALLANCHACRA', '1901', '19'),
            ('190107', 'PAUCARTAMBO', '1901', '19'),
            ('190108', 'SAN FRANCISCO DE ASÍS DE YARUSYACAN', '1901', '19'),
            ('190109', 'SIMON BOLÍVAR', '1901', '19'),
            ('190110', 'TICLACAYAN', '1901', '19'),
            ('190111', 'TINYAHUARCO', '1901', '19'),
            ('190112', 'VICCO', '1901', '19'),
            ('190113', 'YANACANCHA', '1901', '19'),
            ('190201', 'YANAHUANCA', '1902', '19'),
            ('190202', 'CHACAYAN', '1902', '19'),
            ('190203', 'GOYLLARISQUIZGA', '1902', '19'),
            ('190204', 'PAUCAR', '1902', '19'),
            ('190205', 'SAN PEDRO DE PILLAO', '1902', '19'),
            ('190206', 'SANTA ANA DE TUSI', '1902', '19'),
            ('190207', 'TAPUC', '1902', '19'),
            ('190208', 'VILCABAMBA', '1902', '19'),
            ('190301', 'OXAPAMPA', '1903', '19'),
            ('190302', 'CHONTABAMBA', '1903', '19'),
            ('190303', 'HUANCABAMBA', '1903', '19'),
            ('190304', 'PALCAZU', '1903', '19'),
            ('190305', 'POZUZO', '1903', '19'),
            ('190306', 'PUERTO BERMÚDEZ', '1903', '19'),
            ('190307', 'VILLA RICA', '1903', '19'),
            ('190308', 'CONSTITUCIÓN', '1903', '19'),
            ('200101', 'PIURA', '2001', '20'),
            ('200104', 'CASTILLA', '2001', '20'),
            ('200105', 'CATACAOS', '2001', '20'),
            ('200107', 'CURA MORI', '2001', '20'),
            ('200108', 'EL TALLAN', '2001', '20'),
            ('200109', 'LA ARENA', '2001', '20'),
            ('200110', 'LA UNIÓN', '2001', '20'),
            ('200111', 'LAS LOMAS', '2001', '20'),
            ('200114', 'TAMBO GRANDE', '2001', '20'),
            ('200115', 'VEINTISEIS DE OCTUBRE', '2001', '20'),
            ('200201', 'AYABACA', '2002', '20'),
            ('200202', 'FRIAS', '2002', '20'),
            ('200203', 'JILILI', '2002', '20'),
            ('200204', 'LAGUNAS', '2002', '20'),
            ('200205', 'MONTERO', '2002', '20'),
            ('200206', 'PACAIPAMPA', '2002', '20'),
            ('200207', 'PAIMAS', '2002', '20'),
            ('200208', 'SAPILLICA', '2002', '20'),
            ('200209', 'SICCHEZ', '2002', '20'),
            ('200210', 'SUYO', '2002', '20'),
            ('200301', 'HUANCABAMBA', '2003', '20'),
            ('200302', 'CANCHAQUE', '2003', '20'),
            ('200303', 'EL CARMEN DE LA FRONTERA', '2003', '20'),
            ('200304', 'HUARMACA', '2003', '20'),
            ('200305', 'LALAQUIZ', '2003', '20'),
            ('200306', 'SAN MIGUEL DE EL FAIQUE', '2003', '20'),
            ('200307', 'SONDOR', '2003', '20'),
            ('200308', 'SONDORILLO', '2003', '20'),
            ('200401', 'CHULUCANAS', '2004', '20'),
            ('200402', 'BUENOS AIRES', '2004', '20'),
            ('200403', 'CHALACO', '2004', '20'),
            ('200404', 'LA MATANZA', '2004', '20'),
            ('200405', 'MORROPON', '2004', '20'),
            ('200406', 'SALITRAL', '2004', '20'),
            ('200407', 'SAN JUAN DE BIGOTE', '2004', '20'),
            ('200408', 'SANTA CATALINA DE MOSSA', '2004', '20'),
            ('200409', 'SANTO DOMINGO', '2004', '20'),
            ('200410', 'YAMANGO', '2004', '20'),
            ('200501', 'PAITA', '2005', '20'),
            ('200502', 'AMOTAPE', '2005', '20'),
            ('200503', 'ARENAL', '2005', '20'),
            ('200504', 'COLAN', '2005', '20'),
            ('200505', 'LA HUACA', '2005', '20'),
            ('200506', 'TAMARINDO', '2005', '20'),
            ('200507', 'VICHAYAL', '2005', '20'),
            ('200601', 'SULLANA', '2006', '20'),
            ('200602', 'BELLAVISTA', '2006', '20'),
            ('200603', 'IGNACIO ESCUDERO', '2006', '20'),
            ('200604', 'LANCONES', '2006', '20'),
            ('200605', 'MARCAVELICA', '2006', '20'),
            ('200606', 'MIGUEL CHECA', '2006', '20'),
            ('200607', 'QUERECOTILLO', '2006', '20'),
            ('200608', 'SALITRAL', '2006', '20'),
            ('200701', 'PARIÑAS', '2007', '20'),
            ('200702', 'EL ALTO', '2007', '20'),
            ('200703', 'LA BREA', '2007', '20'),
            ('200704', 'LOBITOS', '2007', '20'),
            ('200705', 'LOS ORGANOS', '2007', '20'),
            ('200706', 'MANCORA', '2007', '20'),
            ('200801', 'SECHURA', '2008', '20'),
            ('200802', 'BELLAVISTA DE LA UNIÓN', '2008', '20'),
            ('200803', 'BERNAL', '2008', '20'),
            ('200804', 'CRISTO NOS VALGA', '2008', '20'),
            ('200805', 'VICE', '2008', '20'),
            ('200806', 'RINCONADA LLICUAR', '2008', '20'),
            ('210101', 'PUNO', '2101', '21'),
            ('210102', 'ACORA', '2101', '21'),
            ('210103', 'AMANTANI', '2101', '21'),
            ('210104', 'ATUNCOLLA', '2101', '21'),
            ('210105', 'CAPACHICA', '2101', '21'),
            ('210106', 'CHUCUITO', '2101', '21'),
            ('210107', 'COATA', '2101', '21'),
            ('210108', 'HUATA', '2101', '21'),
            ('210109', 'MAÑAZO', '2101', '21'),
            ('210110', 'PAUCARCOLLA', '2101', '21'),
            ('210111', 'PICHACANI', '2101', '21'),
            ('210112', 'PLATERIA', '2101', '21'),
            ('210113', 'SAN ANTONIO', '2101', '21'),
            ('210114', 'TIQUILLACA', '2101', '21'),
            ('210115', 'VILQUE', '2101', '21'),
            ('210201', 'AZÁNGARO', '2102', '21'),
            ('210202', 'ACHAYA', '2102', '21'),
            ('210203', 'ARAPA', '2102', '21'),
            ('210204', 'ASILLO', '2102', '21'),
            ('210205', 'CAMINACA', '2102', '21'),
            ('210206', 'CHUPA', '2102', '21'),
            ('210207', 'JOSÉ DOMINGO CHOQUEHUANCA', '2102', '21'),
            ('210208', 'MUÑANI', '2102', '21'),
            ('210209', 'POTONI', '2102', '21'),
            ('210210', 'SAMAN', '2102', '21'),
            ('210211', 'SAN ANTON', '2102', '21'),
            ('210212', 'SAN JOSÉ', '2102', '21'),
            ('210213', 'SAN JUAN DE SALINAS', '2102', '21'),
            ('210214', 'SANTIAGO DE PUPUJA', '2102', '21'),
            ('210215', 'TIRAPATA', '2102', '21'),
            ('210301', 'MACUSANI', '2103', '21'),
            ('210302', 'AJOYANI', '2103', '21'),
            ('210303', 'AYAPATA', '2103', '21'),
            ('210304', 'COASA', '2103', '21'),
            ('210305', 'CORANI', '2103', '21'),
            ('210306', 'CRUCERO', '2103', '21'),
            ('210307', 'ITUATA', '2103', '21'),
            ('210308', 'OLLACHEA', '2103', '21'),
            ('210309', 'SAN GABAN', '2103', '21'),
            ('210310', 'USICAYOS', '2103', '21'),
            ('210401', 'JULI', '2104', '21'),
            ('210402', 'DESAGUADERO', '2104', '21'),
            ('210403', 'HUACULLANI', '2104', '21'),
            ('210404', 'KELLUYO', '2104', '21'),
            ('210405', 'PISACOMA', '2104', '21'),
            ('210406', 'POMATA', '2104', '21'),
            ('210407', 'ZEPITA', '2104', '21'),
            ('210501', 'ILAVE', '2105', '21'),
            ('210502', 'CAPAZO', '2105', '21'),
            ('210503', 'PILCUYO', '2105', '21'),
            ('210504', 'SANTA ROSA', '2105', '21'),
            ('210505', 'CONDURIRI', '2105', '21'),
            ('210601', 'HUANCANE', '2106', '21'),
            ('210602', 'COJATA', '2106', '21'),
            ('210603', 'HUATASANI', '2106', '21'),
            ('210604', 'INCHUPALLA', '2106', '21'),
            ('210605', 'PUSI', '2106', '21'),
            ('210606', 'ROSASPATA', '2106', '21'),
            ('210607', 'TARACO', '2106', '21'),
            ('210608', 'VILQUE CHICO', '2106', '21'),
            ('210701', 'LAMPA', '2107', '21'),
            ('210702', 'CABANILLA', '2107', '21'),
            ('210703', 'CALAPUJA', '2107', '21'),
            ('210704', 'NICASIO', '2107', '21'),
            ('210705', 'OCUVIRI', '2107', '21'),
            ('210706', 'PALCA', '2107', '21'),
            ('210707', 'PARATIA', '2107', '21'),
            ('210708', 'PUCARA', '2107', '21'),
            ('210709', 'SANTA LUCIA', '2107', '21'),
            ('210710', 'VILAVILA', '2107', '21'),
            ('210801', 'AYAVIRI', '2108', '21'),
            ('210802', 'ANTAUTA', '2108', '21'),
            ('210803', 'CUPI', '2108', '21'),
            ('210804', 'LLALLI', '2108', '21'),
            ('210805', 'MACARI', '2108', '21'),
            ('210806', 'NUÑOA', '2108', '21'),
            ('210807', 'ORURILLO', '2108', '21'),
            ('210808', 'SANTA ROSA', '2108', '21'),
            ('210809', 'UMACHIRI', '2108', '21'),
            ('210901', 'MOHO', '2109', '21'),
            ('210902', 'CONIMA', '2109', '21'),
            ('210903', 'HUAYRAPATA', '2109', '21'),
            ('210904', 'TILALI', '2109', '21'),
            ('211001', 'PUTINA', '2110', '21'),
            ('211002', 'ANANEA', '2110', '21'),
            ('211003', 'PEDRO VILCA APAZA', '2110', '21'),
            ('211004', 'QUILCAPUNCU', '2110', '21'),
            ('211005', 'SINA', '2110', '21'),
            ('211101', 'JULIACA', '2111', '21'),
            ('211102', 'CABANA', '2111', '21'),
            ('211103', 'CABANILLAS', '2111', '21'),
            ('211104', 'CARACOTO', '2111', '21'),
            ('211105', 'SAN MIGUEL', '2111', '21'),
            ('211201', 'SANDIA', '2112', '21'),
            ('211202', 'CUYOCUYO', '2112', '21'),
            ('211203', 'LIMBANI', '2112', '21'),
            ('211204', 'PATAMBUCO', '2112', '21'),
            ('211205', 'PHARA', '2112', '21'),
            ('211206', 'QUIACA', '2112', '21'),
            ('211207', 'SAN JUAN DEL ORO', '2112', '21'),
            ('211208', 'YANAHUAYA', '2112', '21'),
            ('211209', 'ALTO INAMBARI', '2112', '21'),
            ('211210', 'SAN PEDRO DE PUTINA PUNCO', '2112', '21'),
            ('211301', 'YUNGUYO', '2113', '21'),
            ('211302', 'ANAPIA', '2113', '21'),
            ('211303', 'COPANI', '2113', '21'),
            ('211304', 'CUTURAPI', '2113', '21'),
            ('211305', 'OLLARAYA', '2113', '21'),
            ('211306', 'TINICACHI', '2113', '21'),
            ('211307', 'UNICACHI', '2113', '21'),
            ('220101', 'MOYOBAMBA', '2201', '22'),
            ('220102', 'CALZADA', '2201', '22'),
            ('220103', 'HABANA', '2201', '22'),
            ('220104', 'JEPELACIO', '2201', '22'),
            ('220105', 'SORITOR', '2201', '22'),
            ('220106', 'YANTALO', '2201', '22'),
            ('220201', 'BELLAVISTA', '2202', '22'),
            ('220202', 'ALTO BIAVO', '2202', '22'),
            ('220203', 'BAJO BIAVO', '2202', '22'),
            ('220204', 'HUALLAGA', '2202', '22'),
            ('220205', 'SAN PABLO', '2202', '22'),
            ('220206', 'SAN RAFAEL', '2202', '22'),
            ('220301', 'SAN JOSÉ DE SISA', '2203', '22'),
            ('220302', 'AGUA BLANCA', '2203', '22'),
            ('220303', 'SAN MARTÍN', '2203', '22'),
            ('220304', 'SANTA ROSA', '2203', '22'),
            ('220305', 'SHATOJA', '2203', '22'),
            ('220401', 'SAPOSOA', '2204', '22'),
            ('220402', 'ALTO SAPOSOA', '2204', '22'),
            ('220403', 'EL ESLABÓN', '2204', '22'),
            ('220404', 'PISCOYACU', '2204', '22'),
            ('220405', 'SACANCHE', '2204', '22'),
            ('220406', 'TINGO DE SAPOSOA', '2204', '22'),
            ('220501', 'LAMAS', '2205', '22'),
            ('220502', 'ALONSO DE ALVARADO', '2205', '22'),
            ('220503', 'BARRANQUITA', '2205', '22'),
            ('220504', 'CAYNARACHI', '2205', '22'),
            ('220505', 'CUÑUMBUQUI', '2205', '22'),
            ('220506', 'PINTO RECODO', '2205', '22'),
            ('220507', 'RUMISAPA', '2205', '22'),
            ('220508', 'SAN ROQUE DE CUMBAZA', '2205', '22'),
            ('220509', 'SHANAO', '2205', '22'),
            ('220510', 'TABALOSOS', '2205', '22'),
            ('220511', 'ZAPATERO', '2205', '22'),
            ('220601', 'JUANJUÍ', '2206', '22'),
            ('220602', 'CAMPANILLA', '2206', '22'),
            ('220603', 'HUICUNGO', '2206', '22'),
            ('220604', 'PACHIZA', '2206', '22'),
            ('220605', 'PAJARILLO', '2206', '22'),
            ('220701', 'PICOTA', '2207', '22'),
            ('220702', 'BUENOS AIRES', '2207', '22'),
            ('220703', 'CASPISAPA', '2207', '22'),
            ('220704', 'PILLUANA', '2207', '22'),
            ('220705', 'PUCACACA', '2207', '22'),
            ('220706', 'SAN CRISTÓBAL', '2207', '22'),
            ('220707', 'SAN HILARIÓN', '2207', '22'),
            ('220708', 'SHAMBOYACU', '2207', '22'),
            ('220709', 'TINGO DE PONASA', '2207', '22'),
            ('220710', 'TRES UNIDOS', '2207', '22'),
            ('220801', 'RIOJA', '2208', '22'),
            ('220802', 'AWAJUN', '2208', '22'),
            ('220803', 'ELÍAS SOPLIN VARGAS', '2208', '22'),
            ('220804', 'NUEVA CAJAMARCA', '2208', '22'),
            ('220805', 'PARDO MIGUEL', '2208', '22'),
            ('220806', 'POSIC', '2208', '22'),
            ('220807', 'SAN FERNANDO', '2208', '22'),
            ('220808', 'YORONGOS', '2208', '22'),
            ('220809', 'YURACYACU', '2208', '22'),
            ('220901', 'TARAPOTO', '2209', '22'),
            ('220902', 'ALBERTO LEVEAU', '2209', '22'),
            ('220903', 'CACATACHI', '2209', '22'),
            ('220904', 'CHAZUTA', '2209', '22'),
            ('220905', 'CHIPURANA', '2209', '22'),
            ('220906', 'EL PORVENIR', '2209', '22'),
            ('220907', 'HUIMBAYOC', '2209', '22'),
            ('220908', 'JUAN GUERRA', '2209', '22'),
            ('220909', 'LA BANDA DE SHILCAYO', '2209', '22'),
            ('220910', 'MORALES', '2209', '22'),
            ('220911', 'PAPAPLAYA', '2209', '22'),
            ('220912', 'SAN ANTONIO', '2209', '22'),
            ('220913', 'SAUCE', '2209', '22'),
            ('220914', 'SHAPAJA', '2209', '22'),
            ('221001', 'TOCACHE', '2210', '22'),
            ('221002', 'NUEVO PROGRESO', '2210', '22'),
            ('221003', 'POLVORA', '2210', '22'),
            ('221004', 'SHUNTE', '2210', '22'),
            ('221005', 'UCHIZA', '2210', '22'),
            ('230101', 'TACNA', '2301', '23'),
            ('230102', 'ALTO DE LA ALIANZA', '2301', '23'),
            ('230103', 'CALANA', '2301', '23'),
            ('230104', 'CIUDAD NUEVA', '2301', '23'),
            ('230105', 'INCLAN', '2301', '23'),
            ('230106', 'PACHIA', '2301', '23'),
            ('230107', 'PALCA', '2301', '23'),
            ('230108', 'POCOLLAY', '2301', '23'),
            ('230109', 'SAMA', '2301', '23'),
            ('230110', 'CORONEL GREGORIO ALBARRACÍN LANCHIPA', '2301', '23'),
            ('230111', 'LA YARADA LOS PALOS', '2301', '23'),
            ('230201', 'CANDARAVE', '2302', '23'),
            ('230202', 'CAIRANI', '2302', '23'),
            ('230203', 'CAMILACA', '2302', '23'),
            ('230204', 'CURIBAYA', '2302', '23'),
            ('230205', 'HUANUARA', '2302', '23'),
            ('230206', 'QUILAHUANI', '2302', '23'),
            ('230301', 'LOCUMBA', '2303', '23'),
            ('230302', 'ILABAYA', '2303', '23'),
            ('230303', 'ITE', '2303', '23'),
            ('230401', 'TARATA', '2304', '23'),
            ('230402', 'HÉROES ALBARRACÍN', '2304', '23'),
            ('230403', 'ESTIQUE', '2304', '23'),
            ('230404', 'ESTIQUE-PAMPA', '2304', '23'),
            ('230405', 'SITAJARA', '2304', '23'),
            ('230406', 'SUSAPAYA', '2304', '23'),
            ('230407', 'TARUCACHI', '2304', '23'),
            ('230408', 'TICACO', '2304', '23'),
            ('240101', 'TUMBES', '2401', '24'),
            ('240102', 'CORRALES', '2401', '24'),
            ('240103', 'LA CRUZ', '2401', '24'),
            ('240104', 'PAMPAS DE HOSPITAL', '2401', '24'),
            ('240105', 'SAN JACINTO', '2401', '24'),
            ('240106', 'SAN JUAN DE LA VIRGEN', '2401', '24'),
            ('240201', 'ZORRITOS', '2402', '24'),
            ('240202', 'CASITAS', '2402', '24'),
            ('240203', 'CANOAS DE PUNTA SAL', '2402', '24'),
            ('240301', 'ZARUMILLA', '2403', '24'),
            ('240302', 'AGUAS VERDES', '2403', '24'),
            ('240303', 'MATAPALO', '2403', '24'),
            ('240304', 'PAPAYAL', '2403', '24'),
            ('250101', 'CALLERIA', '2501', '25'),
            ('250102', 'CAMPOVERDE', '2501', '25'),
            ('250103', 'IPARIA', '2501', '25'),
            ('250104', 'MASISEA', '2501', '25'),
            ('250105', 'YARINACOCHA', '2501', '25'),
            ('250106', 'NUEVA REQUENA', '2501', '25'),
            ('250107', 'MANANTAY', '2501', '25'),
            ('250201', 'RAYMONDI', '2502', '25'),
            ('250202', 'SEPAHUA', '2502', '25'),
            ('250203', 'TAHUANIA', '2502', '25'),
            ('250204', 'YURUA', '2502', '25'),
            ('250301', 'PADRE ABAD', '2503', '25'),
            ('250302', 'IRAZOLA', '2503', '25'),
            ('250303', 'CURIMANA', '2503', '25'),
            ('250304', 'NESHUYA', '2503', '25'),
            ('250305', 'ALEXANDER VON HUMBOLDT', '2503', '25'),
            ('250401', 'PURUS', '2504', '25');");
            $stmt->execute();
            array_push($success,  'Se agrego la tabla ubigeo_distrito');
        }


        $stmt = Conexion::conectar()->prepare("SELECT tipocomp FROM serie WHERE tipocomp = '09' || tipocomp = '00'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("INSERT INTO serie(tipocomp, serie, correlativo) VALUES ('09', 'T001', 0), ('00', 'C002', 0)");
            $stmt->execute();
        }

        $stmt = Conexion::conectar()->prepare("SELECT codigo FROM tipo_comprobante WHERE codigo = '00'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("INSERT INTO tipo_comprobante(codigo, descripcion) VALUES ('00', 'COTIZACIÓN')");
            $stmt->execute();
        }
        if (count($success) > 0) {
            foreach ($success as $k => $succ) {
                echo ++$k . ' - ' . $succ . '<br>';
            }
        } else {
            return 'ok';
        };
    }
    public static function mdlAgregarCampoTabla()
    {
        $resultado = ModeloBD::mdlCrearTablas();

        $success = array();
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'bienesSelva'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD bienesSelva ENUM('s','n') NOT NULL DEFAULT 's' AFTER plantilla ");
            $stmt->execute();
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'serviciosSelva'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD serviciosSelva ENUM('s','n') NOT NULL DEFAULT 's' AFTER bienesSelva ");
            $stmt->execute();
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'conexion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD conexion ENUM('s','n') NOT NULL DEFAULT 's' AFTER serviciosSelva ");
            $stmt->execute();
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM sucursales WHERE Field = 'activo'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE sucursales ADD activo ENUM('s','n') NOT NULL DEFAULT 's' AFTER correo ");
            $stmt->execute();
        };



        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'igv'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD igv INT(11) NOT NULL AFTER conexion");
            $stmt->execute();
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'client_id'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD client_id VARCHAR(120) NULL AFTER igv");
            $stmt->execute();
            array_push($success,  'Se agrego el campo client_id a la tabla emisor');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'secret_id'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD secret_id VARCHAR(120) NULL AFTER client_id");
            $stmt->execute();
            array_push($success,  'Se agrego el campo secret_id a la tabla emisor');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'clavePublica'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD clavePublica VARCHAR(120) NULL AFTER secret_id");
            $stmt->execute();
            array_push($success,  'Se agrego el campo clavePublica a la tabla emisor');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'clavePrivada'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD clavePrivada VARCHAR(120) NULL AFTER clavePublica");
            $stmt->execute();
            array_push($success,  'Se agrego el campo clavePrivada a la tabla emisor');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM guia WHERE Field = 'tipovehiculo'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE guia ADD tipovehiculo VARCHAR(8) NULL AFTER cdrbase64");
            $stmt->execute();
            array_push($success,  'Se agrego el campo tipovehiculo a la tabla guia');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'claveapi'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD claveapi VARCHAR(500) NULL AFTER clavePrivada");
            $stmt->execute();
            array_push($success,  'Se agrego el campo claveapi a la tabla emisor');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM productos WHERE Field = 'fecha'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE productos CHANGE fecha fecha_c TIMESTAMP on update CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP");
            $stmt->execute();
            array_push($success,  'Se cambió el campo fecha de la tabla productos');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'idemisor'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta CHANGE idemisor id_sucursal INT(11)");


            $stmt->execute();
            array_push($success,  'Se cambió el campo idemisor a id_sucursal');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM compra WHERE Field = 'idemisor'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {

            $stmt = Conexion::conectar()->prepare("ALTER TABLE compra CHANGE idemisor id_sucursal INT(11)");


            $stmt->execute();
            array_push($success,  'Se cambió el campo idemisor a id_sucursal');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM cotizaciones WHERE Field = 'idemisor'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {

            $stmt = Conexion::conectar()->prepare("ALTER TABLE cotizaciones CHANGE idemisor id_sucursal INT(11)");


            $stmt->execute();
            array_push($success,  'Se cambió el campo idemisor a id_sucursal');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM envio_resumen WHERE Field = 'idemisor'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {

            $stmt = Conexion::conectar()->prepare("ALTER TABLE envio_resumen CHANGE idemisor id_sucursal INT(11)");


            $stmt->execute();
            array_push($success,  'Se cambió el campo idemisor a id_sucursal');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_credito WHERE Field = 'idemisor'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {

            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_credito CHANGE idemisor id_sucursal INT(11)");


            $stmt->execute();
            array_push($success,  'Se cambió el campo idemisor a id_sucursal');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_debito WHERE Field = 'idemisor'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {

            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_debito CHANGE idemisor id_sucursal INT(11)");


            $stmt->execute();
            array_push($success,  'Se cambió el campo idemisor a id_sucursal');
        };



        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM productos WHERE Field = 'serie'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE productos ADD serie   VARCHAR(50) NULL  AFTER codigo");
            $stmt->execute();
            array_push($success,  'Se cambió el campo serie de la tabla productos');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM guia WHERE Field = 'id_sucursal'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE guia ADD id_sucursal INT(11) NULL  AFTER id");
            $stmt->execute();
            array_push($success,  'Se agrego id_sucursal la tabla guia');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM inventario WHERE Field = 'id_sucursal'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE inventario ADD id_sucursal INT(11) NULL  AFTER id");
            $stmt->execute();
            array_push($success,  'Se agrego id_sucursal la tabla inventario');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM productos WHERE Field = 'id_sucursal'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE productos ADD id_sucursal INT(11) NULL DEFAULT 1 AFTER id_categoria");
            $stmt->execute();
            array_push($success,  'Se agrego id_sucursal la tabla productos');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM productos WHERE Field = 'precio_venta'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE productos CHANGE precio_venta precio_unitario   decimal(11,2) NULL DEFAULT '0.00'");
            $stmt->execute();
            array_push($success,  'Se cambió el campo precio_venta de la tabla productos');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM productos WHERE Field = 'valor_unitario'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE productos ADD valor_unitario   decimal(11,2) NULL DEFAULT '0.00' AFTER precio_unitario");
            $stmt->execute();
            array_push($success,  'Se cambió el agrego valor_unitario a la tabla productos');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM productos WHERE Field = 'activo'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE productos ADD activo ENUM('s','n') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 's' AFTER fecha_c");
            $stmt->execute();
            array_push($success,  'Se agrego el campo activo a la tabla productos');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM clientes WHERE Field = 'activo'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE clientes ADD activo ENUM('s','n') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 's' AFTER fecha");
            $stmt->execute();
            array_push($success,  'Se agrego el campo activo a la tabla clientes');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'icbper'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD icbper decimal(11,2) NULL DEFAULT '0.00' AFTER claveapi");
            $stmt->execute();
            array_push($success,  'Se agrego el campo icbper a la tabla emisor');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM usuarios WHERE Field = 'dni'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE usuarios ADD dni VARCHAR(8) NULL AFTER password");
            $stmt->execute();
            array_push($success,  'Se agrego el campo dni a la tabla usuarios');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM usuarios WHERE Field = 'email'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE usuarios ADD email VARCHAR(8) NULL AFTER dni");
            $stmt->execute();
            array_push($success,  'Se agrego el campo email a la tabla usuarios');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM usuarios WHERE Field = 'id_sucursal'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE usuarios ADD id_sucursal INT(11) NOT NULL DEFAULT '1' AFTER fecha");
            $stmt->execute();
            array_push($success,  'Se agrego el campo id_sucursal a la tabla usuarios');
        };



        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'tipocambio'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD tipocambio FLOAT NULL  AFTER codmoneda");
            $stmt->execute();
            array_push($success,  'Se agrego el campo tipocambio a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'id_nd'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD id_nd INT(11) NULL  AFTER id_nc");
            $stmt->execute();
            array_push($success,  'Se agrego el campo id_nd a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'bienesSelva'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD bienesSelva INT(11) NULL  AFTER id_nd");
            $stmt->execute();
            array_push($success,  'Se agrego el campo bienesSelva a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'serviciosSelva'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD serviciosSelva VARCHAR(4) NULL  AFTER bienesSelva");
            $stmt->execute();
            array_push($success,  'Se agrego el campo serviciosSelva a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'op_gratuitas'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD op_gratuitas DECIMAL(11,2) NULL  AFTER op_inafectas");
            $stmt->execute();
            array_push($success,  'Se agrego el campo op_gratuitas a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'igv_op'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD igv_op DECIMAL(11,2) NULL  AFTER op_gratuitas");
            $stmt->execute();
            array_push($success,  'Se agrego el campo igv_op a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'subdescuento'");
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta CHANGE subdescuento descuento_factor DECIMAL(11,5) NULL");
            $stmt->execute();
            array_push($success,  'Se agrego el cambió subdescuento de la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'icbper'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD icbper DECIMAL(11,2) NULL  AFTER descuento");
            $stmt->execute();
            array_push($success,  'Se agrego el campo icbper a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'subtotal'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD subtotal DECIMAL(11,2) NULL  AFTER igv");
            $stmt->execute();
            array_push($success,  'Se agrego el campo subtotal a la tabla venta');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'apertura'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD apertura tinyint(4) NULL DEFAULT '0' AFTER serviciosSelva");
            $stmt->execute();
            array_push($success,  'Se agrego el campo apertura a la tabla venta');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'detraccion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD detraccion ENUM('si','no') NOT NULL DEFAULT 'no'  AFTER apertura");
            $stmt->execute();
            array_push($success,  'Se agrego el campo detraccion a la tabla venta');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'id_cuenta'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD id_cuenta INT(11) NULL  AFTER detraccion");
            $stmt->execute();
            array_push($success,  'Se agrego el campo id_cuenta a la tabla venta');
        };



        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'cod_detraccion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD cod_detraccion VARCHAR(5) NULL  AFTER id_cuenta");
            $stmt->execute();
            array_push($success,  'Se agrego el campo cod_detraccion a la tabla venta');
        };



        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'cod_pago'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD cod_pago VARCHAR(5) NULL  AFTER cod_detraccion");
            $stmt->execute();
            array_push($success,  'Se agrego el campo cod_pago a la tabla venta');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'pordetraccion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD pordetraccion decimal(11,2) NULL DEFAULT '0.00' AFTER id_cuenta");
            $stmt->execute();
            array_push($success,  'Se agrego el campo pordetraccion a la tabla venta');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM venta WHERE Field = 'totaldetraccion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE venta ADD totaldetraccion decimal(11,2) NULL DEFAULT '0.00' AFTER pordetraccion");
            $stmt->execute();
            array_push($success,  'Se agrego el campo totaldetraccion a la tabla venta');
        };
      

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM detalle WHERE Field = 'codigo_afectacion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE detalle ADD codigo_afectacion VARCHAR(2) NULL  AFTER idproducto");
            $stmt->execute();
            array_push($success,  'Se agrego el campo codigo_afectacion a la tabla detalle');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM detalle WHERE Field = 'tipo_precio'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE detalle ADD tipo_precio VARCHAR(2) NULL  AFTER codigo_afectacion");
            $stmt->execute();
            array_push($success,  'Se agrego el campo tipo_precio a la tabla detalle');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM detalle WHERE Field = 'icbper'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE detalle ADD icbper DECIMAL(11,2) NULL  AFTER precio_unitario");
            $stmt->execute();
            array_push($success,  'Se agrego el campo icbper a la tabla detalle');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM detalle WHERE Field = 'descuento'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE detalle ADD descuento DECIMAL(11,2) NULL  AFTER porcentaje_igv");
            $stmt->execute();
            array_push($success,  'Se agrego el campo descuento a la tabla detalle');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM detalle WHERE Field = 'descuento_factor'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE detalle ADD descuento_factor DECIMAL(11,5) NULL  AFTER descuento");
            $stmt->execute();
            array_push($success,  'Se agrego el campo descuento_factor a la tabla detalle');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM compra WHERE Field = 'apertura'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE compra ADD apertura tinyint(4) NULL DEFAULT '0' AFTER idbaja");
            $stmt->execute();
            array_push($success,  'Se agrego el campo apertura a la tabla compra');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_credito WHERE Field = 'id_usuario'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_credito ADD id_usuario int(11) NULL  AFTER femensajesunat");
            $stmt->execute();
            array_push($success,  'Se agrego el campo id_usuario a la tabla nota_credito');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_credito WHERE Field = 'apertura'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_credito ADD apertura tinyint(4) NULL DEFAULT '0' AFTER id_usuario");
            $stmt->execute();
            array_push($success,  'Se agrego el campo apertura a la tabla nota_credito');
        };
        
        
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_credito WHERE Field = 'anulado'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_credito ADD anulado ENUM('s','n') NULL DEFAULT 'n' AFTER apertura");
            $stmt->execute();
            array_push($success,  'Se agrego el campo anulado a la tabla nota_credito');
        };
 
        
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_credito WHERE Field = 'idbaja'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_credito ADD idbaja int(11) NULL  AFTER anulado");
            $stmt->execute();
            array_push($success,  'Se agrego el campo idbaja a la tabla nota_credito');
        };



        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_debito WHERE Field = 'anulado'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_debito ADD anulado ENUM('s','n') NULL DEFAULT 'n' AFTER apertura");
            $stmt->execute();
            array_push($success,  'Se agrego el campo anulado a la tabla nota_debito');
        };
 
        
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_debito WHERE Field = 'idbaja'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_debito ADD idbaja int(11) NULL  AFTER anulado");
            $stmt->execute();
            array_push($success,  'Se agrego el campo idbaja a la tabla nota_debito');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_debito WHERE Field = 'id_usuario'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_debito ADD id_usuario int(11) NULL  AFTER femensajesunat");
            $stmt->execute();
            array_push($success,  'Se agrego el campo id_usuario a la tabla nota_debito');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM nota_debito WHERE Field = 'apertura'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE nota_debito ADD apertura tinyint(4) NULL DEFAULT '0' AFTER id_usuario");
            $stmt->execute();
            array_push($success,  'Se agrego el campo apertura a la tabla nota_debito');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'caja'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD caja ENUM('s','n') NULL DEFAULT 'n' AFTER icbper");
            $stmt->execute();
            array_push($success,  'Se agrego el campo caja a la tabla emisor');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM proveedores WHERE Field = 'fecha_registro'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE proveedores ADD fecha_registro datetime NULL  AFTER ubigeo");
            $stmt->execute();
            array_push($success,  'Se agrego el campo fecha_registro a la tabla proveedores');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM productos WHERE Field = 'precio_pormayor'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE productos ADD precio_pormayor DECIMAL(11,2) NULL DEFAULT 0.00  AFTER valor_unitario");
            $stmt->execute();
            array_push($success,  'Se agrego el campo precio_pormayor a la tabla productos');
        };
        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM guia WHERE Field = 'descripcion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE guia ADD descripcion VARCHAR(5000) NULL  AFTER tipovehiculo");
            $stmt->execute();
            array_push($success,  'Se agrego el campo descripcion a la tabla guia');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'ipimpresora'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD ipimpresora VARCHAR(50) NULL  AFTER caja");
            $stmt->execute();
            array_push($success,  'Se agrego el campo ipimpresora a la tabla emisor');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'nombreimpresora'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD nombreimpresora VARCHAR(50) NULL  AFTER ipimpresora");
            $stmt->execute();
            array_push($success,  'Se agrego el campo nombreimpresora a la tabla emisor');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'tipoimpresion'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD tipoimpresion VARCHAR(50) NULL  AFTER nombreimpresora");
            $stmt->execute();
            array_push($success,  'Se agrego el campo tipoimpresion a la tabla emisor');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM pago_credito WHERE Field = 'numcuota'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE pago_credito ADD numcuota int(11) NULL  AFTER id_venta");
            $stmt->execute();
            array_push($success,  'Se agrego el campo numcuota a la tabla pago_credito');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM pago_credito WHERE Field = 'estado'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE pago_credito ADD estado ENUM('s','n') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'n' AFTER tipopago");
            $stmt->execute();
            array_push($success,  'Se agrego el campo estado a la tabla pago_credito');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'rol'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD rol VARCHAR(30)  AFTER tipoimpresion");
            $stmt->execute();
            array_push($success,  'Se agrego el campo rol a la tabla emisor');
        };


        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM emisor WHERE Field = 'multialmacen'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE emisor ADD multialmacen ENUM('s','n') CHARACTER SET utf8 COLLATE utf8_spanish_ci NOT NULL DEFAULT 'n' AFTER rol");
            $stmt->execute();
            array_push($success,  'Se agrego el campo multialmacen a la tabla emisor');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM serie WHERE Field = 'id_sucursal'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE serie ADD id_sucursal INT(11)  DEFAULT 1 AFTER id");
            $stmt->execute();
            array_push($success,  'Se agrego el campo id_sucursal a la tabla serie');
        };

        $stmt = Conexion::conectar()->prepare("SHOW COLUMNS FROM guia WHERE Field = 'retorno'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            $stmt = Conexion::conectar()->prepare("ALTER TABLE guia ADD retorno ENUM('s','n') NOT NULL DEFAULT 'n' AFTER descripcion ");
            $stmt->execute();
            array_push($success,  'Se agrego el campo retorno a la tabla guia');
        };


        if (count($success) > 0) {
            foreach ($success as $k => $succ) {
                echo ++$k . ' - ' . $succ . '<br>';
            }
        } else {
            return 'ok';
        };
    }
}

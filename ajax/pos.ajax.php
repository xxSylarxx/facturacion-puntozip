<?php
session_start();
require_once "../vendor/autoload.php";

use Controladores\ControladorProductos;
use Controladores\ControladorEmpresa;
use Controladores\ControladorVentas;
use Controladores\ControladorClientes;
use Controladores\ControladorUsuarios;
use Controladores\ControladorPos;
use Controladores\ControladorSunat;

class AjaxPos
{


    public function ajaxCambiarFoto()
    {
        // var_dump($_FILES['file']);

        $directorio = __DIR__ . "/../vistas/img/productos/" . $_POST['codPro'] . "/";
        $directorioCrear = "../vistas/img/productos/" . $_POST['codPro'];
        $files = glob("../vistas/img/productos/" . $_POST['codPro'] . "/*");

        if (!file_exists($directorioCrear)) {
            mkdir($directorioCrear, 0777, true);
        }
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file); //elimino el fichero
        }

        $nombre_foto = $_FILES['file']['name'];


        $nombrec = substr($nombre_foto, strrpos($nombre_foto, '.') + 1);
        $nomfoto = $_POST['codPro'] . '.' . $nombrec;
        $nombre_foto = $nomfoto;
        $directoriobd = "vistas/img/productos/" . $_POST['codPro'] . "/" . $nombre_foto;
        move_uploaded_file($_FILES['file']['tmp_name'], $directorio . $nombre_foto);


        $datos = array(
            "id" => $_POST['idpro'],
            "foto" => $directoriobd
        );

        $respuesta = ControladorProductos::ctrCambiarFoto($datos);

        echo $respuesta;
    }


    public function ajaxTraerProducto()
    {
        $item = "id";
        $valor = $_POST['idProServ'];
        $idsucursal = $_POST['idSucursal'];
        $respuesta = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);
        echo json_encode($respuesta);
    }

    public function ajaxAgregarCarrito()
    {
        if (isset($_SESSION['id_sucursal'])) {
        } else {
            session_destroy();
            echo "<script>
                    window.location = 'ingreso';
                    </script>";
        }
        if (isset($_POST['icbperLineaPos']) && !empty($_POST['icbperLineaPos'])) {
            $icbper = $_POST['icbperLineaPos'];
        } else {
            $icbper = 0;
        }
        $item = "id";
        $valor = $_POST['idProducto'];
        $datosCarrito = array(
            'id' => $_POST['idProducto'],
            'moneda' =>   $_POST['moneda'],
            'cantidad' =>  $_POST['cantidadPos'],
            'tipo_desc' =>  $_POST['tipo_desc'],
            'descuentoG' =>  $_POST['descuentoGlobal'],
            'descuentoGP' =>  $_POST['descuentoGlobalP'],
            'tipo_cambio' => floatval($_POST['tipo_cambio']),
            'descuento_item' => $_POST['descuentoLineaPos'],
            'codigoafectacion' => $_POST['tipoigvLineaPos'],
            'precio_unitario' => $_POST['precioUnitarioLineaPos'],
            'valor_unitario' => $_POST['valorUnitarioLineaPos'],
            'igv' => $_POST['igvLineaPos'],
            'icbper' => $icbper,
            'editar_item' => $_POST['editar_item'],
            "idSucursal" => $_POST['idSucursal'],
            // 'cantidaditem' => $_POST['cantidaditem']

        );
        // var_dump($datosCarrito);
        if (!empty($datosCarrito['precio_unitario'])) {
            $respuesta = ControladorPos::ctrLlenarCarritoPos($item, $valor, $datosCarrito);
        }
    }

    // AGREGAR PRODUCTOS AL CARRITO
    public $idProducto;
    public $cantidad;
    public $descuentoGlobal;
    public $moneda;
    //  public $descuento_item;
    //  public $tipo_afectacion;
    public function ajaxAgregarCarritoEscaner()
    {
        if (isset($_SESSION['id_sucursal'])) {
        } else {
            session_destroy();
            echo "<script>
                        window.location = 'ingreso';
                        </script>";
        }
        if (isset($_POST['icbper']) && !empty($_POST['icbper'])) {
            $icbper = $_POST['icbper'];
        } else {
            $icbper = 0;
        }
        $item = "id";
        $valor = $this->idProducto;
        $datosCarrito = array(
            'cantidad' =>  $this->cantidad,
            'descuentoG' =>  $this->descuentoGlobal,
            'moneda' =>   $this->moneda,
            'tipo_desc' =>  $_POST['tipo_desc'],
            'descuentoGP' =>  $_POST['descuentoGlobalP'],
            'tipo_cambio' => $_POST['tipo_cambio'],
            'descuento_item' => $_POST['descuento_item'],
            'codigoafectacion' => $_POST['tipo_afectacion'],
            'precio_unitario' => $_POST['precio_unitario'],
            'valor_unitario' => $_POST['valor_unitario'],
            'igv' => $_POST['igv'],
            'icbper' => $icbper,
            "idSucursal" => $_POST['idSucursal'],

        );

        // var_dump($_POST);

        $respuesta = ControladorPos::ctrLlenarCarritoPos($item, $valor, $datosCarrito);
    }

    //ELIMINAR UN ITEM DEL CARRITO
    public $idEliminarCarro;
    public function ajaxEliminarItemCarrito()
    {
        $idEliminarCarro = $this->idEliminarCarro;

        //Como antes, usamos extract() por comodidad, pero podemos no hacerlo tranquilamente
        $carrito = $_SESSION['carrito'];
        //Asignamos a la variable $carro los valores guardados en la sessión
        unset($carrito[$idEliminarCarro]);
        $carrito = array_values($carrito);
        $_SESSION['carrito'] = $carrito;



        $item =   null;
        $valor = null;
        $datosCarrito = array(
            'cantidad' =>  null,
            'descuentoG' =>  $_POST['descuentoGlobal'],
            'moneda' =>   $_POST['moneda'],
            'tipo_desc' =>  $_POST['tipo_desc'],
            'descuentoGP' =>  $_POST['descuentoGlobalP'],
            'tipo_cambio' => $_POST['tipo_cambio'],
            'descuento_item' => null,
            'tipo_afectacion' => null,
            "idSucursal" => $_POST['idSucursal'],
        );
        $respuesta = ControladorPos::ctrLlenarCarritoPos($item, $valor, $datosCarrito);
    }

    public function ajaxEditarCantidadItemCarrito()
    {

        $item =   'id';
        $valor = $_POST['idproductoServicio'];
        $datosCarrito = array(
            'cantidad' =>  $_POST['cantidaditem'],
            'cantidaditem' => $_POST['cantidaditem'],
            'descuentoG' =>  null,
            'moneda' =>  null,
            'tipo_desc' =>  null,
            'descuentoGP' =>  null,
            'tipo_cambio' => null,
            'descuento_item' => null,
            'tipo_afectacion' => null,
            "idSucursal" => $_POST['idSucursal'],

        );
        $respuesta = ControladorPos::ctrLlenarCarritoPos($item, $valor, $datosCarrito);
    }

    public function ajaxEditarPrecioUnitario()
    {
        if (isset($_POST['idproductoPS'])) {
            $carrito =  $_SESSION['carrito'];
            $detalle = array();
            foreach ($carrito as $k => $v) {

                if ($v['id'] == @$_POST['idproductoPS']) {
                    $itemx = $v;
                }
            }
            $itemx;
            $detalle[] = $itemx;

            // var_dump($detalle);
            $emisorigv = new ControladorEmpresa();
            $emisorigv->ctrEmisorIgv();
            $valor_unitario = round($_POST['precio_unitario'] / $emisorigv->igv_uno, 2);
            $igv = round($_POST['precio_unitario'] - $valor_unitario, 2);
            $item =   'id';
            $valor = $_POST['idproductoPS'];

            foreach ($detalle as $k => $detalles)
                $datosCarrito = array(
                    'cantidad' =>  $_POST['cantidaditem'],
                    'cantidaditem' => $_POST['cantidaditem'],
                    'precio_unitario' => $_POST['precio_unitario'],
                    'valor_unitario' => $valor_unitario,
                    'descuentoG' =>  null,
                    'moneda' =>  null,
                    'tipo_desc' =>  null,
                    'descuento_item' => $detalles['descuento_item'],
                    'descuentoGP' =>  null,
                    'tipo_cambio' => null,
                    'tipo_afectacion' => $detalles['codigoafectacion'],
                    'codigoafectacion' => $detalles['codigoafectacion'],
                    'igv' => $igv,
                    'icbper' => $detalles['icbper'],
                    "idSucursal" => $_POST['idSucursal'],

                );

            $respuesta = ControladorPos::ctrLlenarCarritoPos($item, $valor, $datosCarrito);
        }
    }
    public function ajaxMostrarCorrelativo()
    {

        $item = 'tipocomp';
        $valor = $_POST['idSerie'];
        $id_sucursal = $_SESSION['id_sucursal'];
        $seriex = ControladorSunat::ctrMostrarCorrelativoPos($item, $valor, $id_sucursal);
        foreach ($seriex as $k => $v) :
            echo '
            <option value="' . $v['id'] . '">' . $v['serie'] . '</option>
        ';
        endforeach;
    }

    // DESCUENTO GLOBAL
    public $descontar;
    public function ajaxDescuento($descuentoGlobal, $moneda)
    {
        $item =   null;
        $valor = null;
        $datosCarrito = array(
            'cantidad' =>  null,
            'descuentoG' =>  $descuentoGlobal,
            'moneda' =>      $moneda,
            'tipo_desc' =>  $_POST['tipo_desc'],
            'descuentoGP' =>  $_POST['descuentoGlobalP'],
            'tipo_cambio' => $_POST['tipo_cambio'],
            'descuento_item' => null,
            'tipo_afectacion' => null,
            "idSucursal" => $_POST['idSucursal'],

        );

        $respuesta = ControladorPos::ctrLlenarCarritoPos($item, $valor, $datosCarrito);
    }

    // LOAD CARRITO CARGAR CARRITO
    public $loadCarrito;
    public function ajaxLoadCarrito($descuentoGlobal, $moneda)
    {
        $loadCarrito = $this->loadCarrito;
        $item =   null;
        $valor = null;
        $datosCarrito = array(
            'cantidad' =>  null,
            'descuentoG' =>  $descuentoGlobal,
            'moneda' =>  $moneda,
            'tipo_desc' =>  $_POST['tipo_desc'],
            'descuentoGP' =>  $_POST['descuentoGlobalP'],
            'tipo_cambio' => $_POST['tipo_cambio'],
            'descuento_item' => null,
            'tipo_afectacion' => null,
            "idSucursal" => $_POST['idSucursal'],
        );
        $respuesta = ControladorPos::ctrLlenarCarritoPos($item, $valor, $datosCarrito);
    }

    //     GUARDAR VENTA
    // ===============================================================
    //public $moneda;
    public function ajaxGuardarVentaPos()
    {
        if (isset($_SESSION['id_sucursal'])) {
        } else {
            session_destroy();
            echo "<script>
                    window.location = 'ingreso';
                    </script>";
        }
        if (isset($_POST['bienesSelva'])) {
            $bienesSelva = $_POST['bienesSelva'];
        } else {
            $bienesSelva = '';
        }
        if (isset($_POST['serviciosSelva'])) {
            $serviciosSelva = $_POST['serviciosSelva'];
        } else {
            $serviciosSelva = '';
        }
        if (isset($_POST['detraccion'])) {
            $detraccion = $_POST['detraccion'];
        } else {
            $detraccion = '';
        }
        if (isset($_POST['pordetraccion']) && !empty($_POST['pordetraccion'])) {
            $pordetraccion = $_POST['pordetraccion'];
        } else {
            $pordetraccion = 0;
        }
           if (isset($_POST['totaldetraccion']) && !empty($_POST['totaldetraccion'])) {
            $totaldetraccion = $_POST['totaldetraccion'];
        } else {
            $totaldetraccion = 0;
        }
        if (isset($_POST['tipo_pago_detraccion']) && !empty($_POST['tipo_pago_detraccion'])) {
            $tipo_pago_detraccion = $_POST['tipo_pago_detraccion'];
        } else {
            $tipo_pago_detraccion = '';
        }
        //$moneda = $this->moneda;
        $doc = array(
            'moneda' => $_POST['monedaPos'],
            'idSerie' => $_POST['serie'],
            'fechaDoc' => $_POST['fechaDoc'],
            'numDoc' =>  $_POST['docIdentidad'],
            'descuento' =>  $_POST['descuentoGlobalpos'],
            'fechaVence' => $_POST['fechaVence'],
            'ruta_comprobante' => $_POST['ruta_comprobante'],
            'tipo_cambio' => $_POST['tipo_cambio'],
            'envioSunat' => $_POST['envioSunat'],
            'metodopago' => $_POST['metodopago'],
            'comentario' => trim($_POST['comentario']),
            'email' => $_POST['email'],
            'modoemail' => $_POST['modoemail'],
            "bienesSelva" => $bienesSelva,
            "serviciosSelva" => $serviciosSelva,
            "tipopago" => $_POST['tipopago'],
            "fecha_cuota" => json_encode($_POST['fecha_cuota']),
            "cuotas" => json_encode($_POST['cuotas']),
            "idSucursal" => $_POST['idSucursal'],
            'detraccion' => $detraccion,
            'tipo_detraccion' => $_POST['tipodetraccion'],
            'pordetraccion' => $pordetraccion,
            'cuentadetraccion' => $_POST['cuentadetraccion'],
            'totaldetraccion' => $totaldetraccion,
            'tipo_pago_detraccion' => $tipo_pago_detraccion,
            'valor_sucursal' => $_POST['valor_sucursal'],
        );

        if ($_POST['tipoDoc'] == 1) {

            $clienteBd =   array(
                "nombre" => $_POST['razon_social'],
                "documento" => $_POST['docIdentidad'],
                "tipodoc" => $_POST['tipoDoc'],
                "email" => $_POST['email'],
                "telefono" => $_POST['celular'],
                "direccion" => $_POST['direccion'],
                "ruc" => '',
                "razon_social" => '',
                'ubigeo' => $_POST['ubigeo'],
                "pais" => 'PE',
                "id" => $_POST['idCliente'],
            );
        } else {
            $clienteBd = array(
                "nombre" => '',
                "documento" => '',
                "tipodoc" => $_POST['tipoDoc'],
                "email" => $_POST['email'],
                "telefono" => $_POST['celular'],
                "direccion" => $_POST['direccion'],
                "ruc" => $_POST['docIdentidad'],
                "razon_social" => $_POST['razon_social'],
                'ubigeo' => $_POST['ubigeo'],
                "pais" => 'PE',
                "id" => $_POST['idCliente'],
            );
        }


        $respuesta = ControladorVentas::ctrGuardarVenta($doc, $clienteBd);
    }
}
if (isset($_POST['idpro'])) {

    $objFoto = new AjaxPos();
    $objFoto->ajaxCambiarFoto();
}
if (isset($_POST['idProServ'])) {

    $objProduct = new AjaxPos();
    $objProduct->ajaxTraerProducto();
}
if (isset($_POST['cantidadPos'])) {

    $objCarro = new AjaxPos();
    $objCarro->ajaxAgregarCarrito();
}
// AGREGAR ITEM AL CARRO CON ESCANER
if (isset($_POST['escanear'])) {
    $objCarrito = new AjaxPos();
    $objCarrito->idProducto = $_POST['idProducto'];
    $objCarrito->cantidad = $_POST['cantidad'];
    $objCarrito->descuentoGlobal = $_POST['descuentoGlobal'];
    $objCarrito->moneda = $_POST['moneda'];
    $objCarrito->ajaxAgregarCarritoEscaner();
}
if (isset($_POST['idproductoServicio'])) {
    $objLoadCarro = new AjaxPos();
    $objLoadCarro->ajaxEditarCantidadItemCarrito();
}
if (isset($_POST['precio_unitario'])) {
    $objLoadCarro = new AjaxPos();
    $objLoadCarro->ajaxEditarPrecioUnitario();
}
// ELIMINAR ITEM DEL CARRO
if (isset($_POST['idEliminarCarroPos'])) {
    $objEliminarItemCarro = new AjaxPos();
    $objEliminarItemCarro->idEliminarCarro = $_POST['idEliminarCarroPos'];
    $objEliminarItemCarro->ajaxEliminarItemCarrito();
}
if (isset($_POST['idSerie'])) {

    $objCarro = new AjaxPos();
    $objCarro->ajaxMostrarCorrelativo();
}

// DESCUENTO GLOBAL
if (isset($_POST['descontar'])) {
    $objDescontar = new AjaxPos();
    $objDescontar->descontar = $_POST['descontar'];
    @$objDescontar->ajaxDescuento($_POST['descuentoGlobal'],  $_POST['moneda']);
}
// LOAD CARRITO
if (isset($_POST['loadCarrito'])) {
    $objLoadCarro = new AjaxPos();
    $objLoadCarro->loadCarrito = $_POST['loadCarrito'];
    @$objLoadCarro->ajaxLoadCarrito($_POST['descuentoGlobal'], $_POST['moneda']);
}

// GUARDAR VENTA
if (isset($_POST['ruta_comprobante']) && $_POST['ruta_comprobante'] == 'pos') {
    $objGuardarVenta = new AjaxPos();
    $objGuardarVenta->ajaxGuardarVentaPos();
}

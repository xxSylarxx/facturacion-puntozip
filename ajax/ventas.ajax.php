<?php
session_start();

require_once "../vendor/autoload.php";

use Controladores\ControladorProductos;
use Controladores\ControladorVentas;
use Controladores\ControladorCotizaciones;
use Controladores\ControladorClientes;
use Controladores\ControladorSunat;
use Controladores\ControladorEnvioSunat;
use Controladores\ControladorEmpresa;

class AjaxVentas
{


    // AGREGAR PRODUCTOS AL CARRITO
    public $idProducto;
    public $cantidad;
    public $descuentoGlobal;
    public $moneda;
    //  public $descuento_item;
    //  public $tipo_afectacion;
    public function ajaxAgregarCarrito()
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



        $respuesta = ControladorVentas::ctrLlenarCarrito($item, $valor, $datosCarrito);
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
        $respuesta = ControladorVentas::ctrLlenarCarrito($item, $valor, $datosCarrito);
    }
    //  ELIMINAR TODOS LOS ITEMS DEL CARRITO
    public $eliminarCarro;
    public function ajaxEliminarCarrito()
    {
        $eliminarCarro = $this->eliminarCarro;

        //Como antes, usamos extract() por comodidad, pero podemos no hacerlo tranquilamente
        $carrito = $_SESSION['carrito'];
        //Asignamos a la variable $carro los valores guardados en la sessión
        unset($carrito);
        //la función unset borra el elemento de un array que le pasemos por parámetro. En este
        //caso la usamos para borrar el elemento cuyo id le pasemos a la página por la url 
        $_SESSION['carrito'] = $carrito;
        //Finalmente, actualizamos la sessión, como hicimos cuando agregamos un producto y volvemos al catálogo
        //header("Location:catalogo.php?".SID);

    }

    //ELIMINAR UN ITEM DEL CARRITO
    public $idEliminarCarro;
    public function ajaxEliminarItemCarrito($moneda)
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
            'cantidad' =>  $this->cantidad,
            'descuentoG' =>  $_POST['descuentoGlobal'],
            'moneda' =>   $moneda,
            'tipo_desc' =>  $_POST['tipo_desc'],
            'descuentoGP' =>  $_POST['descuentoGlobalP'],
            'tipo_cambio' => $_POST['tipo_cambio'],
            'descuento_item' => null,
            'tipo_afectacion' => null,
            "idSucursal" => $_POST['idSucursal'],

        );
        $respuesta = ControladorVentas::ctrLlenarCarrito($item, $valor, $datosCarrito);
    }



    //=====================================================
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
            'tipo_desc' =>  null,
            'descuentoGP' =>  null,
            'tipo_cambio' => $_POST['tipo_cambio'],
            'descuento_item' => null,
            'tipo_afectacion' => null,
            "idSucursal" => $_POST['idSucursal'],
        );
        $respuesta = ControladorVentas::ctrLlenarCarrito($item, $valor, $datosCarrito);
    }
    //=====================================================
    // DESCUENTO GLOBAL
    public $descontar;
    public function ajaxDescuento($descuentoGlobal, $moneda)
    {
        $item =   null;
        $valor = null;
        $datosCarrito = array(
            'cantidad' =>  $this->cantidad,
            'descuentoG' =>  $descuentoGlobal,
            'moneda' =>      $moneda,
            'tipo_desc' =>  $_POST['tipo_desc'],
            'descuentoGP' =>  $_POST['descuentoGlobalP'],
            'tipo_cambio' => $_POST['tipo_cambio'],
            'descuento_item' => null,
            'tipo_afectacion' => null,
            "idSucursal" => $_POST['idSucursal'],

        );

        $respuesta = ControladorVentas::ctrLlenarCarrito($item, $valor, $datosCarrito);
    }

    /*================================================================
GUARDAR VENTA
===============================================================*/
    //public $moneda;
    public function ajaxGuardarVenta()
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
            'moneda' => $_POST['moneda'],
            'idSerie' => $_POST['serie'],
            'fechaDoc' => $_POST['fechaDoc'],
            'numDoc' =>  $_POST['docIdentidad'],
            'descuento' =>  $_POST['descuentoGlobal'],
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

        if ($_POST['tipoDoc'] == 1  || $_POST['tipoDoc'] == 4 || $_POST['tipoDoc'] == 7) {

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
        if ($doc['ruta_comprobante'] == 'crear-cotizacion') {

            $respuesta = ControladorCotizaciones::ctrGuardarCotizacion($doc, $clienteBd);
        } else {

            $respuesta = ControladorVentas::ctrGuardarVenta($doc, $clienteBd);
        }
    }

    public function ajaxComprobantesNoEnviados()
    {
        $respuesta = ControladorVentas::ctrComprobantesNoEnviados();
        echo $respuesta[0];
    }

    public function ajaxAnularNotasVenta()
    {
        $idventa = $_POST['idventa'];
        $respuesta = ControladorVentas::ctrActualizarRechazadoVenta($idventa);
        echo $respuesta;
    }
    public function ajaxEditarPrecioUnitario()
    {
        $carrito =  $_SESSION['carrito'];
        $detalle = array();
        foreach ($carrito as $k => $v) {

            if ($v['id'] == $_POST['idproductoPS']) {
                $itemx = $v;
            }
        }
        $itemx;
        $detalle[] = $itemx;

        // var_dump($detalle);
        $emisorigv = new ControladorEmpresa();
        $emisorigv->ctrEmisorIgv();
        $valor_unitario = round($_POST['precio_unitariocomp'] / $emisorigv->igv_uno, 2);
        $igv = round($_POST['precio_unitariocomp'] - $valor_unitario, 2);
        $item =   'id';
        $valor = $_POST['idproductoPS'];

        foreach ($detalle as $k => $detalles)
            $datosCarrito = array(
                'cantidad' =>  $_POST['cantidaditem'],
                'cantidaditem' => $_POST['cantidaditem'],
                'precio_unitario' => $_POST['precio_unitariocomp'],
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

        $respuesta = ControladorVentas::ctrLlenarCarrito($item, $valor, $datosCarrito);
    }
}

// AGREGAR ITEM AL CARRO
if (isset($_POST['idProducto'])) {
    $objCarrito = new AjaxVentas();
    $objCarrito->idProducto = $_POST['idProducto'];
    $objCarrito->cantidad = $_POST['cantidad'];
    $objCarrito->descuentoGlobal = $_POST['descuentoGlobal'];
    $objCarrito->moneda = $_POST['moneda'];
    $objCarrito->ajaxAgregarCarrito();
}

if (isset($_POST['idproductoServicio'])) {
    $objLoadCarro = new AjaxVentas();
    $objLoadCarro->ajaxEditarCantidadItemCarrito();
}
// ELIMINAR CARRO
if (isset($_POST['eliminarCarro'])) {
    $objEliminarCarro = new AjaxVentas();
    $objEliminarCarro->eliminarCarro = $_POST['eliminarCarro '];
    $objEliminarCarro->ajaxEliminarCarrito();
}
// ELIMINAR ITEM DEL CARRO
if (isset($_POST['idEliminarCarro'])) {
    $objEliminarItemCarro = new AjaxVentas();
    $objEliminarItemCarro->idEliminarCarro = $_POST['idEliminarCarro'];
    $objEliminarItemCarro->ajaxEliminarItemCarrito($_POST['moneda']);
}
// LOAD CARRITO
if (isset($_POST['loadCarrito'])) {
    $objLoadCarro = new AjaxVentas();
    $objLoadCarro->loadCarrito = $_POST['loadCarrito'];
    @$objLoadCarro->ajaxLoadCarrito($_POST['descuentoGlobal'], $_POST['moneda']);
}
// DESCUENTO GLOBAL
if (isset($_POST['descontar'])) {
    $objDescontar = new AjaxVentas();
    $objDescontar->descontar = $_POST['descontar'];
    @$objDescontar->ajaxDescuento($_POST['descuentoGlobal'],  $_POST['moneda']);
}
// GUARDAR VENTA
if (isset($_POST['ruta_comprobante']) && ($_POST['ruta_comprobante'] == 'crear-factura' || $_POST['ruta_comprobante'] == 'crear-boleta' || $_POST['ruta_comprobante'] == 'crear-nota' || $_POST['ruta_comprobante'] == 'crear-cotizacion')) {
    $objGuardarVenta = new AjaxVentas();
    //$objGuardarVenta-> moneda = $_POST['moneda'];
    $objGuardarVenta->ajaxGuardarVenta();
}

// COMPROBANTES NO ENVIADOS
if (isset($_POST['noEnviados'])) {
    $objNoEnviados = new AjaxVentas();
    $objNoEnviados->ajaxComprobantesNoEnviados();
}
if (isset($_POST['idventa'])) {
    $objNotas = new AjaxVentas();
    $objNotas->ajaxAnularNotasVenta();
}

if (isset($_POST['precio_unitariocomp'])) {
    $objLoadCarro = new AjaxVentas();
    $objLoadCarro->ajaxEditarPrecioUnitario();
}

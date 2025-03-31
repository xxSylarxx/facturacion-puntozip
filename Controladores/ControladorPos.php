<?php

namespace Controladores;

use Modelos\ModeloVentas;
use Modelos\ModeloProductos;
use Modelos\ModeloClientes;
use api\GeneradorXML;
use api\ApiFacturacion;
// use Controladores\ControladorVentas as  ControladoresControladorVentas;
use Controladores\ControladorInventarios;
use Controladores\ControladorEmpresa;


require_once "cantidad_en_letras.php";

class ControladorPos
{


    // LISTAR VENTAS BOLETAS FACTURAS
    public static function ctrLlenarCarritoPos($item, $valor, $datosCarrito)
    {
        $simboloMoneda = '';
        if ($datosCarrito['moneda'] == "PEN") {
            $simboloMoneda = "S/ ";
        }
        if ($datosCarrito['moneda'] == "USD") {
            $simboloMoneda = '$USD ';
        }


        $tabla = "productos";
        $idsucursal = $datosCarrito['idSucursal'];
        $producto = ControladorProductos::ctrMostrarProductos($item, $valor, $idsucursal);

        if (!isset($_SESSION['carrito'])) {
            $_SESSION['carrito'] = array();
        }

        $carrito = $_SESSION['carrito'];

        //$item = count($carrito)+1;
        if ($datosCarrito['cantidad'] != null) {
            $item = count($carrito) + 1;
            $datosCarrito['cantidad'] = $datosCarrito['cantidad'];
            $existe = false;
            foreach ($carrito as $k => $v) {

                if ($v['id'] == $producto['id']) {
                    $item = $k;
                    $existe = true;
                    break;
                }
            }
            $cantidad = $datosCarrito['cantidad'];
            if (isset($_POST["editar_item"]) && $_POST["editar_item"] == 'edita') {
                $carrito[$item] = array(
                    'id' => $producto['id'],
                    'codigo' => $producto['codigo'],
                    'descripcion' => $producto['descripcion'],
                    'valor_unitario' => $datosCarrito['valor_unitario'],
                    'precio_unitario' => $datosCarrito['precio_unitario'],
                    'igv' => $datosCarrito['igv'],
                    'unidad' => $producto['codunidad'],
                    'codigoafectacion' => $datosCarrito['codigoafectacion'],
                    'cantidad' => $datosCarrito['cantidad'],
                    'descuento_item'    => $datosCarrito['descuento_item'],
                    'tipo_afectacion'    => $datosCarrito['codigoafectacion'],
                    'icbper'    => $datosCarrito['icbper']
                );
            } else {
                if (!$existe) {
                    $carrito[$item] = array(
                        'id' => $producto['id'],
                        'codigo' => $producto['codigo'],
                        'descripcion' => $producto['descripcion'],
                        'valor_unitario' => $datosCarrito['valor_unitario'],
                        'precio_unitario' => $datosCarrito['precio_unitario'],
                        'igv' => $datosCarrito['igv'],
                        'unidad' => $producto['codunidad'],
                        'codigoafectacion' => $datosCarrito['codigoafectacion'],
                        'cantidad' => $datosCarrito['cantidad'],
                        'descuento_item'    => $datosCarrito['descuento_item'],
                        'tipo_afectacion'    => $datosCarrito['codigoafectacion'],
                        'icbper'    => $datosCarrito['icbper']
                    );
                } else {

                    if (isset($datosCarrito['cantidaditem'])) {
                        $carrito[$item]['cantidad'] = $datosCarrito['cantidad'];
                    } else {
                        $carrito[$item]['cantidad'] = $carrito[$item]['cantidad'] + $datosCarrito['cantidad'];
                    }
                }
            }
        }

        $_SESSION['carrito'] = $carrito;
        $valor_unitario = 0;
        $precio_unitario = 0;
        $total_c = 0;
        $emisor = new ControladorEmpresa();
        $emisor->ctrEmisorIgv();
        foreach ($carrito as $k => $v) {

            if ($datosCarrito['moneda'] == "USD") {
                $v['valor_unitario'] = $v['valor_unitario'] / $datosCarrito['tipo_cambio'];
                $v['precio_unitario'] = $v['precio_unitario'] / $datosCarrito['tipo_cambio'];
                $v['igv'] = $v['igv'] / $datosCarrito['tipo_cambio'];
                $v['descuento_item'] = $v['descuento_item'] / $datosCarrito['tipo_cambio'];
            }

            $valor_unitario = $v['precio_unitario'] / $emisor->igv_uno;
            $precio_unitario = $v['precio_unitario'];
            if ($v['codigoafectacion'] == '10') {

                @$total_c = $precio_unitario * $v['cantidad'] - $v['descuento_item'];
            }
            if ($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16') {

                @$total_c = $precio_unitario * $v['cantidad'];
                $valor_unitario = $valor_unitario;
                $precio_unitario = $valor_unitario;
            }
            if ($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36') {

                @$total_c = $precio_unitario * $v['cantidad'];
                $valor_unitario = $valor_unitario;
                $precio_unitario = $valor_unitario;
            }
            if ($v['codigoafectacion'] == '20') {
                @$total_c = $precio_unitario * $v['cantidad'] - $v['descuento_item'];
            }
            if ($v['codigoafectacion'] == '30') {
                @$total_c = $precio_unitario * $v['cantidad'] - $v['descuento_item'];
            }

            //var_dump($carrito);
            @$total_comp = $total_c;

            echo "<tr class='id-eliminar" . $k . "'>";
            echo "<td>" . $v['codigo'] . "</td>
        <td><input type='text' class='cantidaditempos' name='cantidaditempos' id='cantidaditempos" . $v['id'] . "' value='" . $v['cantidad'] . "' idproductoServicio='" . $v['id'] . "'></td>
       
        <td>" . $v['descripcion'] . "</td>";
            $item = 'rol';
            $valor = $_SESSION['perfil'];
            $roles = ControladorEmpresa::ctrRoles($item, $valor);

            $item = 'id_rol';
            $valor = $roles['id'];
            $accesos = ControladorEmpresa::ctrAccesos($item, $valor);

            foreach ($accesos as $key => $value) {
                if ($value['linkacceso'] == 'cambio-precio' && $value['activo'] == 's') {
                    $readonly = "";
                } else {
                    $readonly = 'readonly';
                }
            }
        
        echo "<td><input type='text' class='preciounitariopos' name='preciounitariopos' id='preciounitariopos" . $v['id'] . "' value='" . round($v['precio_unitario'], 2) . "' idproductoServicio='" . $v['id'] . "' $readonly>
        </td>
        <td>" . round($total_c, 2) . "</td>";
            echo "<td><button type='button' class='btn btn-danger btn-xs btnEliminarItemCarroPos' itemEliminar='" . $k . "'><i class='fas fa-trash-alt'></i></button>
				
				</td>
				</tr>";
        }
        //-------------- INICIO DE CALCULO DE TOTALES -------//
        $op_gravadas = 0.00;
        $op_exoneradas = 0.00;
        $op_inafectas = 0.00;
        $op_gratuitas = 0.00;
        $igv = 0.00;
        $igv_porcentaje = $emisor->igv_dos;
        $descuento_item_total = 0.00;
        $icbper = 0.00;
        $total_icbper = 0.00;


        foreach ($carrito as $K => $v) {

            if ($datosCarrito['moneda'] == "USD") {
                $v['valor_unitario'] = $v['valor_unitario'] / $datosCarrito['tipo_cambio'];
                $v['precio_unitario'] = $v['precio_unitario'] / $datosCarrito['tipo_cambio'];
                $v['igv'] = $v['igv'] / $datosCarrito['tipo_cambio'];
                $v['descuento_item'] = $v['descuento_item'] / $datosCarrito['tipo_cambio'];
            }
            if ($v['codigoafectacion'] == '10') {
                $valor_uni = $v['precio_unitario'] / $emisor->igv_uno;
                $op_gravadas += ($valor_uni * $v['cantidad']) - $v['descuento_item'];
            }
            if ($v['codigoafectacion'] == '11' || $v['codigoafectacion'] == '12' || $v['codigoafectacion'] == '13' || $v['codigoafectacion'] == '14' || $v['codigoafectacion'] == '15' || $v['codigoafectacion'] == '16') {

                $op_gratuitas += $v['valor_unitario'] * $v['cantidad'];
            }
            if ($v['codigoafectacion'] == '31' || $v['codigoafectacion'] == '32' || $v['codigoafectacion'] == '33' || $v['codigoafectacion'] == '34' || $v['codigoafectacion'] == '35' || $v['codigoafectacion'] == '36') {

                $op_gratuitas += $v['valor_unitario'] * $v['cantidad'];
            }

            if ($v['codigoafectacion'] == '20') {
                $op_exoneradas += $v['precio_unitario'] * $v['cantidad'] - $v['descuento_item'];
            }

            if ($v['codigoafectacion'] == '30') {
                $op_inafectas += $v['precio_unitario'] * $v['cantidad'] - $v['descuento_item'];
            }
            $igv = $op_gravadas * $emisor->igv_dos;
            $descuento_item_total += $v['descuento_item'];

            $total_icbper += $v['icbper'];
        }
        // $igv = round($igv,2);



        $sub_total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv;
        $sub_to = $op_gravadas + $op_exoneradas + $op_inafectas;
        $op_gr = $op_gravadas;
        //----- FIN DEL CALCULO DE TOTALES --------//
        // ALGORITMO DESCUENTO
        $descuentoGlobal = $datosCarrito['descuentoG'];
        $descuentoGlobalP = $datosCarrito['descuentoGP'];

        if ($datosCarrito['tipo_desc'] == 'S/' && $descuentoGlobal > 0 && $op_gravadas > 0) {
            @$desc_porcentaje = ($descuentoGlobal / $op_gravadas);
            @$convertir = (($descuentoGlobal * 100) / $sub_total);
            $op_desc = $op_gravadas * ($convertir / 100);
            $op_gravadas =  $op_gravadas - $descuentoGlobal;
            $op_exoneradas = $op_exoneradas;
            $op_inafectas = $op_inafectas;
            $igv = $op_gravadas * $emisor->igv_dos;
            $descuentoGlobal = $descuentoGlobal;
            echo "<script>
                        $('#descuentoGlobalPpos').val('" . (round($desc_porcentaje * 100, 5)) . "');
                        </script>";
        }
        if ($datosCarrito['tipo_desc'] == '%' && $descuentoGlobalP > 0 &&  $op_gravadas > 0) {

            $desc_porcentaje = $descuentoGlobalP / 100;
            // $desc_factor =($desc_porcentaje * $sub_to);		
            $opg = $op_gravadas * $desc_porcentaje;
            $op_desc = $op_gravadas * $desc_porcentaje;
            $op_gravadas =  $op_gravadas - $opg;
            $op_exoneradas = $op_exoneradas;
            $op_inafectas = $op_inafectas;
            $igv = $op_gravadas * $emisor->igv_dos;
            $descuentoGlobal = $op_desc;
            echo "<script>
                $('#descuentoGlobalpos').val('" . (round($desc_porcentaje * $op_gr, 2)) . "');
                </script>";
        }


        $total = $op_gravadas + $op_exoneradas + $op_inafectas + $igv + $total_icbper;

        $op_gravadas = number_format($op_gravadas, 2);
        $op_exoneradas = number_format($op_exoneradas, 2);
        $op_inafectas = number_format($op_inafectas, 2);
        $descuentoGlobal = number_format($descuentoGlobal, 2);
        $igv = number_format($igv, 2);

        $total = $total;

        if ($op_gravadas > 0) {
            echo "<script>
                                $('#descuentoGlobal').prop('readonly',false);
                                $('#descuentoGlobalP').prop('readonly',false);
                                </script>";
        }

        if (($op_exoneradas > 0 || $op_inafectas > 0) && $op_gravadas == 0) {
            echo "<script>
                                $('#descuentoGlobal').prop('readonly',true);
                                $('#descuentoGlobalP').prop('readonly',true);
                                $('#descuentoGlobal').val(0);
                                $('#descuentoGlobalP').val(0);
                                </script>";
        }
        if (($op_exoneradas > 0 || $op_inafectas > 0) && $op_gravadas > 0) {
            echo "<script>
                                $('#descuentoGlobal').prop('readonly',false);
                                $('#descuentoGlobalP').prop('readonly',false);
                                </script>";
        }

        if ($descuentoGlobal > 0) {
            echo "<script>
                                $('.op-subt').show();
                                </script>";
        } else {
            echo "<script>
                                $('.op-subt').hide();
                                </script>";
        }
        if ($total > 0) {
            echo "<script>
                                $('.op-subt').show();
                                </script>";
        } else {
            echo "<script>
                                $('.op-subt').hide();
                                </script>";
        }
        if ($op_gravadas > 0) {
            echo "<script>
                            $('.op-gravadas').show();
                            </script>";
        } else {
            echo "<script>
                            $('.op-gravadas').hide();
                            </script>";
        }
        if ($descuento_item_total > 0) {
            echo "<script>
                            $('.op-descuento-item').show();
                            </script>";
        } else {
            echo "<script>
                            $('.op-descuento-item').hide();
                            </script>";
        }
        if ($op_exoneradas > 0) {
            echo "<script>
                            $('.op-exoneradas').show();
                            </script>";
        } else {
            echo "<script>
                            $('.op-exoneradas').hide();
                            </script>";
        }
        if ($op_inafectas > 0) {
            echo "<script>
                            $('.tabla-totales .totales .op-inafectas').show();
                            </script>";
        } else {
            echo "<script>
                            $('.tabla-totales .totales .op-inafectas').hide();
                            </script>";
        }
        if ($op_gratuitas > 0) {
            echo "<script>
                            $('.tabla-totales .totales .op-gratuitas').show();
                            </script>";
        } else {
            echo "<script>
                            $('.tabla-totales .totales .op-gratuitas').hide();
                            </script>";
        }
        if ($total_icbper > 0) {
            echo "<script>
                            $('.tabla-totales .totales .icbper').show();
                            </script>";
        } else {
            echo "<script>
                            $('.tabla-totales .totales .icbper').hide();
                            </script>";
        }
        if (empty($carrito)) {
            echo "<script> 
                        $('.tabla-totales .totales .op-igv').children().next().html('0.00');
                        $('.tabla-totales .totales .op-total').children().next().html('0.00');
                        $('.tabla-totales .totales .op-descuento').children().next().html('0.00');
                        </script>";
        } else {

            echo "<script>
                            $('#totalOperacion').val($total);
                            $('.tabla-totales .totales .op-subt').children().next().html('" . $simboloMoneda . number_format($sub_total, 2) . "');
                            $('.tabla-totales .totales .op-descuento-item').children().next().html('" . $simboloMoneda . number_format($descuento_item_total, 2) . "');
                            $('.tabla-totales .totales .op-gravadas').children().next().html('" . $simboloMoneda . $op_gravadas . "');
                            $('.tabla-totales .totales .op-exoneradas').children().next().html('" . $simboloMoneda . $op_exoneradas . "');
                            $('.tabla-totales .totales .op-inafectas').children().next().html('" . $simboloMoneda . $op_inafectas . "');
                            $('.tabla-totales .totales .op-gratuitas').children().next().html('" . $simboloMoneda . $op_gratuitas . "');
                            $('.tabla-totales .totales .op-descuento').children().next().html('" . $simboloMoneda . $descuentoGlobal . "');
                            $('.tabla-totales .totales .icbper').children().next().html('" . $simboloMoneda . $total_icbper . "');
                            $('.tabla-totales .totales .op-igv').children().next().html('" . $simboloMoneda . $igv . "');
                            $('.tabla-totales .totales .op-total').children().next().html('" . $simboloMoneda . number_format($total, 2) . "');
                            
                            
                    
                        
                        </script>";
        }
    }
}

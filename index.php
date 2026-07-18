
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estado de Cuenta - Gestión Tarjeta</title>
    <style>
        /* Selector Universal para resetear márgenes [3] */
        * {
            box-sizing: border-box;
            font-family: Arial, sans-serif; /* Uso de fuentes legibles [6, 7] */
        }

        body {
            background-color: #f4f7f6;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        /* Modelo de Caja para el contenedor principal [5, 8] */
        .container {
            background-color: white;
            max-width: 600px;
            width: 100%;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #ddd;
        }

        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            text-align: center;
        }

        /* Estilo para la lista de transacciones [9] */
        ul {
            list-style: none;
            padding: 0;
        }

        li {
            background: #fff;
            margin-bottom: 8px;
            padding: 10px;
            border-left: 4px solid #3498db;
            font-size: 0.9em;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        /* Estilo para el resumen financiero [4, 9] */
        .resumen-box {
            background-color: #ebf5fb;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        pre {
            white-space: pre-wrap;
            color: #2c3e50;
            font-weight: bold;
            line-height: 1.6;
        }

        .success-msg {
            color: #27ae60;
            font-size: 0.8em;
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">

        <?php

        /**
         * Sistema de estado de cuenta para tarjeta de crédito
         * 
         * Permite registrar transacciones y genera un estado de cuenta con:
         * - Detalle de cada transacción
         * - Monto total de contado
         * - Monto con interés del 2.6%
         * - Cashback del 0.1% sobre el monto de contado
         * - Monto final a pagar
         * 
         * Además, guarda la información en un archivo de texto: estado_cuenta.txt
         */

        // 1. Arreglo Global de Transacciones 
        $transacciones = [];

        /*Función para registrar una nueva transacción*/

        function registrarTransaccion($id, $descripcion, $monto) {
            global $transacciones;
            
            array_push($transacciones, [
                "id" => $id,
                "descripcion" => $descripcion,
                "monto" => $monto
            ]);
        }

        /* Función para generar el estado de cuenta en pantalla y en archivo TXT*/
        function generarEstadoDeCuenta() {
            global $transacciones;
            
            $montoContado = 0;
            $detalleTXT = "ESTADO DE CUENTA - DETALLE DE TRANSACCIONES\n";
            $detalleTXT .= str_repeat("-", 50) . "\n";

            // Recorrer el arreglo para sumar montos y preparar el texto
            foreach ($transacciones as $t) {
                $montoContado += $t['monto'];
                $linea = "ID: {$t['id']} | Desc: {$t['descripcion']} | Monto: ₡" . number_format($t['monto'], 2) . "\n";
                $detalleTXT .= $linea;
                // Imprimir en pantalla
                echo "<li>$linea</li>";
            }

            // Cálculos financieros estipulados
            $interes = $montoContado * 0.026; // 2.6%
            $montoConInteres = $montoContado + $interes;
            $cashback = $montoContado * 0.001; // 0.1%
            $montoFinal = $montoConInteres - $cashback;

            // Consolidar resultados finales
            $resumen = "\nRESUMEN FINANCIERO\n";
            $resumen .= "Total Contado: ₡" . number_format($montoContado, 2) . "\n";
            $resumen .= "Monto con Interés (2.6%): ₡" . number_format($montoConInteres, 2) . "\n";
            $resumen .= "Cashback Aplicado (0.1%): ₡" . number_format($cashback, 2) . "\n";
            $resumen .= "MONTO FINAL A PAGAR: ₡" . number_format($montoFinal, 2) . "\n";

            echo "<br><strong>Resultados:</strong><pre>" . $resumen . "</pre>";

            // 2. Generación del archivo de texto (estado_cuenta.txt)
            $archivo = fopen("estado_cuenta.txt", "w") or die("No se pudo crear el archivo.");
            fwrite($archivo, $detalleTXT . $resumen);
            fclose($archivo);
            
            echo "<p style='color: green;'>Archivo 'estado_cuenta.txt' generado exitosamente.</p>";
        }

        // --- Simulación de Transacciones ---
        registrarTransaccion(1, "Compra en Supermercado", 45000);
        registrarTransaccion(2, "Pago de Electricidad", 12500);
        registrarTransaccion(3, "Suscripción Streaming", 6800);
        registrarTransaccion(4, "Cena Restaurante", 22000);

        // Generar el reporte
        echo "<h1>Sistema de Gestión de Tarjeta de Crédito</h1>";
        echo "<ul>";
        generarEstadoDeCuenta();
        echo "</ul>";
        ?>
</div>

</body>
</html>
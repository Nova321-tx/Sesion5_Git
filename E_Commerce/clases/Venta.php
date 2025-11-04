<?php
// clases/Venta.php
require_once __DIR__ . '/../supabase.php';

class Venta {
    private $endpoint = '/rest/v1/ventas';
    private $detalleEndpoint = '/rest/v1/venta_detalle';
    private $productoEndpoint = '/rest/v1/productos';

    // Registrar una venta con sus detalles y actualizar stock
    public function registrar($usuario_id, $total, $items, $metodo = 'normal') {
        if (!$usuario_id || !$items || !is_array($items)) {
            throw new InvalidArgumentException("Datos de venta incompletos");
        }

        // 1. Crear la venta
        $ventaBody = [[
            'usuario_id' => intval($usuario_id),
            'total' => round(floatval($total), 2),
            'fecha' => date('c'),
        ]];

        $rVenta = supabaseRequest($this->endpoint, 'POST', $ventaBody, true, ['Prefer: return=representation']);
        if ($rVenta['status'] >= 400) throw new RuntimeException($rVenta['body']);
        $venta = json_decode($rVenta['body'], true)[0];

        // 2. Registrar los detalles y actualizar stock
        foreach ($items as $it) {
            $cantidad = intval($it['qty']);
            $subtotal = floatval($it['subtotal'] ?? 0);

            // Registrar detalle
            $detalleBody = [[
                'venta_id' => intval($venta['id']),
                'producto_id' => intval($it['id']),
                'cantidad' => $cantidad,
                'subtotal' => $subtotal
            ]];
            $rDet = supabaseRequest($this->detalleEndpoint, 'POST', $detalleBody, true);
            if ($rDet['status'] >= 400) throw new RuntimeException($rDet['body']);

            // Reducir stock del producto
            $rProd = supabaseRequest($this->productoEndpoint . "?id=eq." . intval($it['id']), 'GET');
            if ($rProd['status'] >= 400) throw new RuntimeException($rProd['body']);
            $prod = json_decode($rProd['body'], true)[0] ?? null;

            if ($prod && isset($prod['stock'])) {
                $nuevoStock = max(0, $prod['stock'] - $cantidad);
                $updateStock = [['stock' => $nuevoStock]];
                $rUpd = supabaseRequest($this->productoEndpoint . "?id=eq." . intval($it['id']), 'PATCH', $updateStock, true);
                if ($rUpd['status'] >= 400) throw new RuntimeException($rUpd['body']);
            }
        }

        return $venta;
    }

    public function obtenerPorUsuario($usuario_id) {
        $path = $this->endpoint . "?select=*,venta_detalle(*)&usuario_id=eq." . intval($usuario_id) . "&order=id.desc";
        $r = supabaseRequest($path, 'GET');
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return json_decode($r['body'], true);
    }

    public function obtenerTodas() {
        $path = $this->endpoint . "?select=*,usuarios(nombre,email)&order=id.desc";
        $r = supabaseRequest($path, 'GET');
        if ($r['status'] >= 400) throw new RuntimeException($r['body']);
        return json_decode($r['body'], true);
    }

     public function obtenerResumen() {
        require_once __DIR__.'/../supabase.php';

        // ✅ Ventas de PayPal / sistema actual
        $ventas = $this->obtenerTodas(); 

        // ✅ Ventas manuales desde Supabase
        $resManuales = supabaseRequest('/rest/v1/ventas_manuales?select=total,fecha', 'GET');
        $ventasManuales = json_decode($resManuales['body'], true) ?? [];

        // ✅ Combinar todas las ventas
        $todasVentas = array_merge($ventas, $ventasManuales);

        $totalVentas = count($todasVentas);
        $facturacionTotal = array_sum(array_map(fn($v) => floatval($v['total']), $todasVentas));

        $ventasPorDia = [];
        foreach($todasVentas as $v){
            $dia = date('Y-m-d', strtotime($v['fecha']));
            if(!isset($ventasPorDia[$dia])) $ventasPorDia[$dia] = 0;
            $ventasPorDia[$dia] += floatval($v['total']);
        }

        return [
            'total_ventas' => $totalVentas,
            'facturacion_total' => $facturacionTotal,
            'ventas_por_dia' => $ventasPorDia
        ];
    }
}
?>

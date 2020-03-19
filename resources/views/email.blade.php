<h5>Reporte clientes proxima entrega.</h5>
<table class="table table-hover table-sm">
    <thead>
        <tr>
        <th>Cliente</th>
        <th>Vivienda</th>
        <th>Prototipo</th>
        <th>Torre</th>
        <th>Desarrollo</th>
        <th>Fecha de entrega</th>
        <th>Monto</th>
        </tr>
    </thead>
    <tbody>
        @foreach($layoutReporteEntrega as $key => $dato)
        
            <tr class="table-success">
                <td>{{ $dato['cliente'] }}</td>
                <td>{{ $dato['vivienda'] }}</td>
                <td>{{ $dato['prototipo'] }}</td>
                <td>{{ $dato['torre'] }}</td>
                <td>{{ $dato['desarrollo'] }}</td>
                <td>{{ $dato['fecha_entrega'] }}</td>
                <td>${{ number_format($dato['monto_pago'], 2) }}</td>
            </tr>

        @endforeach
    </tbody>
</table>

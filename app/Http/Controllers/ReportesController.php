<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;  // Usamos la fachada HTTP para hacer las peticiones
use Carbon\Carbon;

class ReportesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     * 
     * 
     */
    protected $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'http://localhost:3000/reportes/';
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Se usa el endpoint MostrarReporte con el parámetro 0 para listar todos los reportes
        $response = Http::get($this->baseUrl . 'MostrarReporte/0');

        if ($response->successful()) {
            $reportes = $response->json();
            return view('Reportes.reportes', compact('reportes'));
        } else {
            return back()->withErrors('Error al obtener la lista de reportes.');
        }
        
    }

    


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Obtenemos los datos del request
        $data = $request->only(['idTipoReporte', 'formato', 'fechaInicio', 'fechaFin']);
        
        // Realizamos la petición POST para registrar el reporte
        $response = Http::post('http://localhost:3000/reportes/RegistrarReporte', $data);

        // Verificamos si la petición fue exitosa
        if ($response->successful()) {
            return redirect()->route('reportes.index')->with('success', 'Reporte registrado correctamente');
        } else {
            return redirect()->route('reportes.index')->with('error', 'Error al registrar el reporte');
        }
    }

    /**
     * Display the specified resource.
     */
// Controlador (ReporteController.php)
public function show(string $id)
{
    // Obtenemos los parámetros idTipoReporte y formato desde la query string
    $idTipoReporte = request()->query('idTipoReporte');
    $formato = request()->query('formato');

    // Realizamos la petición GET para obtener el reporte
    $response = Http::get("http://localhost:3000/reportes/MostrarReporte/{$id}");
    $response1 = Http::get($this->baseUrl . 'MostrarReporte/0');

    // Verificamos si la petición fue exitosa
    if ($response->successful() && $response1->successful()) {
        $reporte = $response->json();
        $reportes = $response1->json();
        
        // Retornamos el reporte, idTipoReporte, el id recibido y el formato a la vista
        return view('Reportes.reportes', [
            'reporte'            => $reporte,
            'reportes'           => $reportes,
            'idTipoReporte'      => $idTipoReporte,
            'idReportegenerado'  => $id,
            'formato'            => $formato,
        ]);
    } else {
        return redirect()->route('reportes.index')->with('error', 'Error al mostrar el reporte');
    }
}





    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Obtenemos los datos del request
        $data = $request->only(['idTipoReporte', 'formato', 'fechaInicio', 'fechaFin']);

        // Realizamos la petición PUT para actualizar el reporte
        $response = Http::put("http://localhost:3000/reportes/ActualizarReporte/{$id}", $data);

        // Verificamos si la petición fue exitosa
        if ($response->successful()) {
            return redirect()->route('reportes.index')->with('success', 'Reporte actualizado correctamente');
        } else {
            return redirect()->route('reportes.index')->with('error', 'Error al actualizar el reporte');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Realizamos la petición DELETE para eliminar el reporte
        $response = Http::delete("http://localhost:3000/reportes/EliminarReporte/{$id}");

        // Verificamos si la petición fue exitosa
        if ($response->successful()) {
            return redirect()->route('reportes.index')->with('success', 'Reporte eliminado correctamente');
        } else {
            return redirect()->route('reportes.index')->with('error', 'Error al eliminar el reporte');
        }
    }

    public function download(Request $request)
{
    // Recuperamos los parámetros 'reporte' y 'formato' desde la query string
    $reporteJson = $request->query('reporte');
    $formato = $request->query('formato');

    // Validamos que ambos parámetros existan
    if (!$reporteJson || !$formato) {
        return redirect()->back()->with('error', 'Faltan parámetros necesarios.');
    }

    // Convertimos el reporte de JSON a array
    $reporte = json_decode($reporteJson, true);

    // Según el formato, generamos la descarga
    switch (strtolower($formato)) {
        case 'pdf':
            // Generamos el PDF utilizando una vista (por ejemplo: reportes.pdf)
            // Asegúrate de tener instalado y configurado barryvdh/laravel-dompdf
            $pdf = \PDF::loadView('reportes.pdf', compact('reporte'));
            return $pdf->download('reporte.pdf');

        case 'excel':
            // Generamos el Excel utilizando una clase de exportación (ReporteExport)
            // Asegúrate de tener instalado y configurado Maatwebsite/Excel y haber creado la clase ReporteExport
            return \Excel::download(new \App\Exports\ReporteExport($reporte), 'reporte.xlsx');

        case 'html':
            // Generamos el HTML y lo enviamos como archivo descargable
            $html = view('reportes.html', compact('reporte'))->render();
            $headers = [
                'Content-Type' => 'text/html',
                'Content-Disposition' => 'attachment; filename="reporte.html"',
            ];
            return response()->make($html, 200, $headers);

        default:
            return redirect()->back()->with('error', 'Formato no soportado.');
    }
}

}


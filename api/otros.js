module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();


    
// Endpoint para obtener datos para selects dinámicos
router.get("/selects/:tipo", (req, res) => {
    const tipo = req.params.tipo.toLowerCase();
    const tiposValidos = [
        'categorias', 
        'marcas', 
        'puestos', 
        'departamentos', 
        'proveedores', 
        'productos',
        'tiposreportes',
        'reportes'
    ];
    
    // Validar que el tipo sea uno de los permitidos
    if (!tiposValidos.includes(tipo)) {
        return res.status(400).json({ 
            error: "Tipo no válido",
            tipos_permitidos: tiposValidos,
            mensaje: "Use alguno de los tipos listados en 'tipos_permitidos'"
        });
    }

    const sql = "CALL sp_ObtenerDatosSelects(?)";
    
    mysqlConnection.query(
        sql,
        [tipo],
        (err, rows, fields) => {
            if (!err) {
                // Filtrar resultados vacíos (cuando el tipo es válido pero no hay datos)
                if (rows[0].length === 0) {
                    return res.status(404).json({
                        message: "No se encontraron registros para este tipo",
                        tipo: tipo
                    });
                }
                
                res.status(200).json(rows[0]);
            } else {
                console.error("Error al obtener datos para selects:", err);
                res.status(500).json({ 
                    error: "Error al obtener datos para selects",
                    details: err.message,
                    tipo_solicitado: tipo
                });
            }
        }
    );
});
    


    
    return router;
};
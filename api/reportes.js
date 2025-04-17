module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para seleccionar reportes
    router.get("/MostrarReporte/:id", (req, res) => { 
        const reporteId = req.params.id;
        const sql = "CALL sp_MostrarReporte(?)"; 
        mysqlConnection.query(sql, [reporteId], (err, rows, fields) => {
            if (!err) {
                res.status(200).json(rows[0]);
            } else {
                res.status(500).send("Error al seleccionar reportes.");
            }
        });
    });

    // Endpoint para insertar reportes
    router.post("/RegistrarReporte", (req, res) => {
        const reporte = req.body;
        const sql = "CALL sp_RegistrarReporte(?, ?, ?, ?)";
        console.log("Datos recibidos:", reporte);
        mysqlConnection.query(
            sql,
            [
                reporte.idTipoReporte,
                reporte.formato,
                reporte.fechaInicio,
                reporte.fechaFin,
            ],
            (err, rows, fields) => {
                if (!err) {
                    console.log("Respuesta de la base de datos:", rows);
                    res.send("Reporte ingresado correctamente!");
                } else {
                    console.log("Error al insertar reporte:", err);
                    res.status(500).send("Error al insertar reporte.");
                }
            }
        );
    });

    // Endpoint para actualizar un reporte
    router.put("/ActualizarReporte/:id", (req, res) => {
        const reporte = req.body;
        const reporteId = req.params.id;
        const sql = "CALL sp_ActualizarReporte(?, ?, ?, ?, ?)";
        mysqlConnection.query(
            sql,
            [
                reporteId,
                reporte.idTipoReporte, 
                reporte.formato, 
                reporte.fechaInicio, 
                reporte.fechaFin, 
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send("Reporte actualizado correctamente!");
                } else {
                    res.status(500).send("Error al actualizar reporte.");
                }
            }
        );
    });

    // Endpoint para eliminar un reporte
    router.delete("/EliminarReporte/:id", (req, res) => {
        const id = req.params.id;
        const sql = "CALL sp_EliminarReporte(?);";
        mysqlConnection.query(sql, [id], (err, rows, fields) => {
            if (!err) {
                res.status(200).send(`Reporte con ID ${id} eliminado correctamente!`);
            } else {
                res.status(500).send("Error al eliminar reporte.");
            }
        });
    });

    return router;
};







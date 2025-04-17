module.exports = function (mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para seleccionar ventas o detalles según el parámetro tipo
    router.get("/MostrarVentas/:id/:tipo", (req, res) => {
        const idVenta = req.params.id;
        const tipo = req.params.tipo; // 'ventas' o 'detalles'
        const sql = "CALL sp_MostrarVentas(?, ?)";

        mysqlConnection.query(sql, [idVenta, tipo], (err, rows, fields) => {
            if (!err) {
                res.status(200).json(rows[0]);
            } else {
                res.status(500).send("Error al seleccionar ventas.");
            }
        });
    });

    // Endpoint para registrar una venta
    router.post("/RegistrarVenta", (req, res) => {
        const venta = req.body;
        const sql = "CALL sp_RegistrarVenta(?, ?)";

        console.log("Datos recibidos:", venta);

        mysqlConnection.query(
            sql,
            [
                venta.idCliente,
                JSON.stringify(venta.detallesVenta)  // Convertir a JSON string
            ],
            (err, rows, fields) => {
                if (!err) {
                    console.log("Respuesta de la base de datos:", rows);
                    res.send("Venta ingresada correctamente!");
                } else {
                    console.log("Error al insertar venta:", err);
                    res.status(500).send("Error al insertar venta.");
                }
            }
        );
    });

    // Endpoint para actualizar una venta
    router.put("/ActualizarVenta/:id", (req, res) => {
        const venta = req.body;
        const idVenta = req.params.id;
        const sql = "CALL sp_ActualizarVenta(?, ?, ?)";

        mysqlConnection.query(
            sql,
            [
                idVenta,
                venta.idCliente,
                JSON.stringify(venta.detallesVenta)  // Convertir a JSON string
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send("Venta actualizada correctamente!");
                } else {
                    res.status(500).send("Error al actualizar venta.");
                }
            }
        );
    });

    // Endpoint para eliminar una venta
    router.get("/EliminarVenta/:id", (req, res) => {
        const id = req.params.id;
        const sql = "CALL sp_EliminarVenta(?);";

        mysqlConnection.query(sql, [id], (err, rows, fields) => {
            if (!err) {
                res.status(200).send(`✅ Venta con ID ${id} eliminada correctamente!`);
            } else {
                console.error("❌ Error SQL:", err);
                res.status(500).send("Error al eliminar venta.");
            }
        });
    });


    return router;
};

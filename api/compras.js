module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para seleccionar compras o detalles según el parámetro tipo
    router.get("/MostrarCompra/:id/:tipo", (req, res) => {
        const idCompra = req.params.id;
        const tipo = req.params.tipo; // 'compras' o 'detalles'
        const sql = "CALL sp_MostrarCompras(?, ?)";

        mysqlConnection.query(sql, [idCompra, tipo], (err, rows, fields) => {
            if (!err) {
                res.status(200).json(rows[0]);
            } else {
                res.status(500).send("Error al seleccionar compras.");
            }
        });
    });

    // Endpoint para seleccionar una compra en especifico según su ID
    router.get("/MostrarCompraById/:id", (req, res) => {
        const idCompra = req.params.id;
        const sql = "CALL sp_MostrarComprasById(?)";

        mysqlConnection.query(sql, [idCompra], (err, rows, fields) => {
            if (!err) {
                res.status(200).json(rows[0]);
            } else {
                res.status(500).send("Error al seleccionar la compra.");
            }
        });
    });

    // Endpoint para registrar una compra
    router.post("/RegistrarCompra", (req, res) => {
        const compra = req.body;
        const sql = "CALL sp_RegistrarCompra(?, ?, ?)";

        console.log("Datos recibidos:", compra);

        mysqlConnection.query(
            sql,
            [
                compra.fechaCompra,
                compra.idProveedor,
                JSON.stringify(compra.detallesCompra)  // Convertir a JSON string
            ],
            (err, rows, fields) => {
                if (!err) {
                    console.log("Respuesta de la base de datos:", rows);
                    res.send("Compra ingresada correctamente!");
                } else {
                    console.log("Error al insertar compra:", err);
                    res.status(500).send("Error al insertar compra.");
                }
            }
        );
    });

    // Endpoint para actualizar una compra
    router.put("/ActualizarCompra/:id", (req, res) => {
        const compra = req.body;
        const idCompra = req.params.id;
        const sql = "CALL sp_ActualizarCompra(?, ?, ?, ?)";

        mysqlConnection.query(
            sql,
            [
                idCompra,
                compra.fechaCompra,
                compra.idProveedor,
                JSON.stringify(compra.detallesCompra)  // Convertir a JSON string
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send("Compra actualizada correctamente!");
                } else {
                    res.status(500).send("Error al actualizar compra.");
                }
            }
        );
    });

    // Endpoint para eliminar una compra
    router.get("/EliminarCompra/:id", (req, res) => {
        const id = req.params.id;
        const sql = "CALL sp_EliminarCompra(?);";

        mysqlConnection.query(sql, [id], (err, rows, fields) => {
            if (!err) {
                res.status(200).send(`✅ Compra con ID ${id} eliminada correctamente!`);
            } else {
                console.error("❌ Error SQL:", err);
                res.status(500).send("Error al eliminar compra.");
            }
        });
    });

    return router;
};

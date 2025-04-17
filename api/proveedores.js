module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para insertar un proveedor
    router.post("/RegistrarProveedor", (req, res) => {
        const proveedor = req.body;
        const sql = "CALL sp_RegistrarProveedor(?, ?, ?, ?)";
        mysqlConnection.query(
            sql,
            [
                proveedor.nombreProveedor,
                proveedor.email,
                proveedor.telefono,
                proveedor.direccion
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.send("Proveedor ingresado correctamente!");
                } else {
                    console.log("Error al insertar proveedor:", err);
                    res.status(500).send("Error al insertar proveedor.");
                }
            }
        );
    });

    // Endpoint para seleccionar proveedores
    router.get("/MostrarProveedor/:id", (req, res) => { 
        const idProveedor = req.params.id;
        const sql = "CALL sp_MostrarProveedor(?)";
        mysqlConnection.query(
            sql,
            [idProveedor],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).json(rows[0]);
                } else {
                    res.status(500).send("Error al seleccionar proveedores.");
                }
            }
        );
    });

    // Endpoint para actualizar un proveedor
    router.put("/ActualizarProveedor/:id", (req, res) => {
        const proveedor = req.body;
        const proveedorId = req.params.id;
        const sql = "CALL sp_ActualizarProveedor(?, ?, ?, ?, ?)";
        mysqlConnection.query(
            sql,
            [
                proveedorId,
                proveedor.nombreProveedor,
                proveedor.email,
                proveedor.telefono,
                proveedor.direccion
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send("Proveedor actualizado correctamente!");
                } else {
                    console.log("Error al actualizar proveedor:", err);
                    res.status(500).send("Error al actualizar proveedor.");
                }
            }
        );
    });

    // Endpoint para eliminar un proveedor
    router.delete("/EliminarProveedor/:id", (req, res) => {
        const idProveedor = req.params.id;
        const sql = "CALL sp_EliminarProveedor(?)";
        mysqlConnection.query(
            sql,
            [idProveedor],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send(`Proveedor con ID ${idProveedor} eliminado correctamente!`);
                } else {
                    console.log("Error al eliminar proveedor:", err);
                    res.status(500).send("Error al eliminar proveedor.");
                }
            }
        );
    });

    return router;
};

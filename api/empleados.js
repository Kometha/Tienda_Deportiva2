module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para insertar un empleado
    router.post("/RegistrarEmpleado", (req, res) => {
        const empleado = req.body;
        const sql = "CALL sp_RegistrarEmpleado(?, ?, ?, ?, ?)";
        
        mysqlConnection.query(
            sql,
            [
                empleado.dni,
                empleado.fechaContratacion,
                empleado.salario,
                empleado.idPuesto,
                empleado.idDepartamento
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.send("Empleado ingresado correctamente!");
                } else {
                    console.log("Error al insertar empleado:", err);
                    res.status(500).send("Error al insertar empleado.");
                }
            }
        );
    });

    // Endpoint para seleccionar empleados
    router.get("/MostrarEmpleado/:id", (req, res) => { 
        const idEmpleado = req.params.id;
        const sql = "CALL sp_MostrarEmpleado(?)"; 
        mysqlConnection.query(sql, [idEmpleado], (err, rows, fields) => {
            if (!err) {
                res.status(200).json(rows[0]);
            } else {
                res.status(500).send("Error al seleccionar empleado.");
            }
        });
    });

    // Endpoint para actualizar un empleado
    router.put("/ActualizarEmpleado/:id", (req, res) => {
        const empleado = req.body;
        const empleadoId = req.params.id;
        const sql = "CALL sp_ActualizarEmpleado(?, ?, ?, ?, ?)";
        
        mysqlConnection.query(
            sql,
            [
                empleadoId,
                empleado.fechaContratacion,
                empleado.salario,
                empleado.idPuesto,
                empleado.idDepartamento
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send("Empleado actualizado correctamente!");
                } else {
                    console.log("Error al actualizar empleado:", err);
                    res.status(500).send("Error al actualizar empleado.");
                }
            }
        );
    });

    // Endpoint para eliminar un empleado
    router.delete("/EliminarEmpleado/:id", (req, res) => {
        const idEmpleado = req.params.id;
        const sql = "CALL sp_EliminarEmpleado(?)";
        
        mysqlConnection.query(sql, [idEmpleado], (err, rows, fields) => {
            if (!err) {
                res.status(200).send(`Empleado con ID ${idEmpleado} eliminado correctamente!`);
            } else {
                console.log("Error al eliminar empleado:", err);
                res.status(500).send("Error al eliminar empleado.");
            }
        });
    });

    return router;
};

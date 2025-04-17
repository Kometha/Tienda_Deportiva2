module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para seleccionar personas
    router.get("/MostrarPersona/:id", (req, res) => { 
        const idPersona = req.params.id;
        const sql = "CALL sp_MostrarPersona(?)"; 
        mysqlConnection.query(sql, [idPersona], (err, rows, fields) => {
            if (!err) {
                res.status(200).json(rows[0]);
            } else {
                res.status(500).send("Error al seleccionar personas.");
            }
        });
    });

    // Endpoint para insertar personas
    router.post("/RegistrarPersona", (req, res) => {
        const persona = req.body;
        const sql = "CALL sp_RegistrarPersona(?, ?, ?, ?, ?, ?, ?, ?)";
        
        console.log("Datos recibidos:", persona);  // Depuración

        mysqlConnection.query(
            sql,
            [
                persona.nombre,
                persona.apellido,
                persona.fechaNacimiento,
                persona.dni,
                persona.sexo,
                persona.email,
                persona.telefono,
                persona.direccion,
            ],
            (err, rows, fields) => {
                if (!err) {
                    console.log("Respuesta de la base de datos:", rows);  // Depuración
                    res.send("Persona ingresada correctamente!");
                } else {
                    console.log("Error al insertar persona:", err);
                    res.status(500).send("Error al insertar persona.");
                }
            }
        );
    });

    // Endpoint para actualizar persona
    router.put("/ActualizarPersona/:id", (req, res) => {
        const persona = req.body;
        const idPersona = req.params.id;
        const sql = "CALL sp_ActualizarPersona(?, ?, ?, ?, ?, ?, ?, ?, ?)";
        mysqlConnection.query(
            sql,
            [
                idPersona,
                persona.nombre,
                persona.apellido,
                persona.fechaNacimiento,
                persona.dni,
                persona.sexo,
                persona.email,
                persona.telefono,
                persona.direccion,
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send("Persona actualizada correctamente!");
                } else {
                    res.status(500).send("Error al actualizar persona.");
                }
            }
        );
    });

    // Endpoint para eliminar una persona
    router.delete("/EliminarPersona/:id", (req, res) => {
        const id = req.params.id;
        const sql = "CALL sp_EliminarPersona(?);";
        mysqlConnection.query(sql, [id], (err, rows, fields) => {
            if (!err) {
                res.status(200).send(`Persona con ID ${id} eliminada correctamente!`);
            } else {
                res.status(500).send("Error al eliminar persona.");
            }
        });
    });

    return router;
};

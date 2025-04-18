const express = require('express');

module.exports = (mysqlConnection) => {
    const router = express.Router();

    // Ruta para obtener clientes usando la stored procedure
    router.get('/', (req, res) => {
        mysqlConnection.query('CALL sp_obtenerClientes()', (err, rows) => {
            if (err) {
                console.error('Error al ejecutar la stored procedure:', err);
                return res.status(500).json({ error: err.message });
            }

            // Los resultados del procedimiento almacenado vienen en un array de arrays
            res.json(rows[0]);
        });
    });

    return router;
};

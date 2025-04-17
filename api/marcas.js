module.exports = function (mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Obtener todas las marcas
    router.get('/', (req, res) => {
        const sql = 'SELECT * FROM Marcas';
        mysqlConnection.query(sql, (err, rows) => {
            if (!err) {
                res.json(rows);
            } else {
                res.status(500).send('Error al obtener marcas.');
            }
        });
    });

    // Crear nueva marca
    router.post('/', (req, res) => {
        const { nombreMarca } = req.body;
        const sql = 'INSERT INTO Marcas (nombreMarca) VALUES (?)';
        mysqlConnection.query(sql, [nombreMarca], (err, result) => {
            if (!err) {
                res.status(201).send('Marca creada correctamente.');
            } else {
                res.status(500).send('Error al crear marca.');
            }
        });
    });

    // Actualizar marca
    router.put('/:id', (req, res) => {
        const { id } = req.params;
        const { nombreMarca } = req.body;
        const sql = 'UPDATE Marcas SET nombreMarca = ? WHERE idMarca = ?';
        mysqlConnection.query(sql, [nombreMarca, id], (err, result) => {
            if (!err) {
                res.send('Marca actualizada correctamente.');
            } else {
                res.status(500).send('Error al actualizar marca.');
            }
        });
    });

    // Eliminar marca
    router.delete('/:id', (req, res) => {
        const { id } = req.params;
        const sql = 'DELETE FROM Marcas WHERE idMarca = ?';
        mysqlConnection.query(sql, [id], (err, result) => {
            if (!err) {
                res.send('Marca eliminada correctamente.');
            } else {
                res.status(500).send('Error al eliminar marca.');
            }
        });
    });

    return router;
};

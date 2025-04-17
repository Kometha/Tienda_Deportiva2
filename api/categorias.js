module.exports = function (mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Obtener todas las categorías
    router.get('/', (req, res) => {
        mysqlConnection.query('SELECT * FROM Categorias', (err, rows) => {
            if (!err) res.json(rows);
            else res.status(500).send('Error al obtener categorías.');
        });
    });

    // Crear una categoría
    router.post('/', (req, res) => {
        const { nombreCategoria } = req.body;
        mysqlConnection.query('INSERT INTO Categorias (nombreCategoria) VALUES (?)', [nombreCategoria], (err) => {
            if (!err) res.status(201).send('Categoría creada.');
            else res.status(500).send('Error al crear categoría.');
        });
    });

    // Actualizar una categoría
    router.put('/:id', (req, res) => {
        const { nombreCategoria } = req.body;
        mysqlConnection.query('UPDATE Categorias SET nombreCategoria = ? WHERE idCategoria = ?', [nombreCategoria, req.params.id], (err) => {
            if (!err) res.send('Categoría actualizada.');
            else res.status(500).send('Error al actualizar categoría.');
        });
    });

    // Eliminar una categoría
    router.delete('/:id', (req, res) => {
        mysqlConnection.query('DELETE FROM Categorias WHERE idCategoria = ?', [req.params.id], (err) => {
            if (!err) res.send('Categoría eliminada.');
            else res.status(500).send('Error al eliminar categoría.');
        });
    });

    return router;
};

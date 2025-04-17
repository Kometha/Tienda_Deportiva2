module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para insertar productos
    router.post("/RegistrarProducto", (req, res) => {
        const producto = req.body;
        const sql = "CALL sp_RegistrarProducto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
        console.log("Datos recibidos:", producto);  // Depuración
    
        mysqlConnection.query(
            sql,
            [
                producto.nombreProducto,
                producto.descripcion,
                producto.idProveedor,
                producto.idCategoria,
                producto.idMarca,
                producto.precioCompra,
                producto.precioVenta,
                producto.stockInicial,
                producto.bajoStock,
                producto.imagen
            ],
            (err, rows, fields) => {
                if (!err) {
                    console.log("Respuesta de la base de datos:", rows);  // Depuración
                    res.send("Producto ingresado correctamente!");
                } else {
                    console.log("Error al insertar producto:", err);
                    res.status(500).send("Error al insertar producto.");
                }
            }
        );
    });
    
    // Endpoint para seleccionar productos
    router.get("/MostrarProducto/:id", (req, res) => { 
        const idProducto = req.params.id;
        const sql = "CALL sp_MostrarProducto(?)"; 
        mysqlConnection.query(
            sql,
            [idProducto],
            (err, rows, fields) => {
            if (!err) {
                res.status(200).json(rows[0]);
            } else {
                    res.status(500).send("Error al seleccionar productos.");
                }
            }
        );
    });
    
    // Endpoint para actualizar un producto
    router.put("/ActualizarProducto/:id", (req, res) => {
        const productos = req.body;
        const idProducto = req.params.id;
        const sql = "CALL sp_ActualizarProducto(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        mysqlConnection.query(
            sql,
            [
                idProducto,
                productos.nombreProducto,
                productos.descripcion,
                productos.idProveedor,
                productos.idCategoria,
                productos.idMarca,
                productos.precioCompra,
                productos.precioVenta,
                productos.stock,
                productos.bajoStock,
                productos.imagen
                
            ],
            (err, rows, fields) => {
                if (!err) {
                    res.status(200).send("Producto actualizado correctamente!");
                } else {
                    res.status(500).send("Error al actualizar producto.");
                }
            }
        );
    });
    
    // Endpoint para eliminar un producto
    router.delete("/EliminarProducto/:id", (req, res) => {
        const id = req.params.id;
        const sql = "CALL sp_EliminarProducto(?);";
        mysqlConnection.query(sql, [id], (err, rows, fields) => {
            if (!err) {
                res.status(200).send(`Producto con ID ${id} eliminado correctamente!`);
            } else {
                res.status(500).send("Error al eliminar producto.");
            }
        });
    });
    
    return router;
};

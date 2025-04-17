module.exports = function(mysqlConnection) {
    const express = require('express');
    const router = express.Router();

    // Endpoint para login
    router.post("/login", (req, res) => {
        const { usuario, clave } = req.body;

        if (!usuario || !clave) {
            return res.status(400).json({ error: "Usuario y clave son requeridos." });
        }

        const sql = "CALL sp_login_usuario(?, ?)";
        mysqlConnection.query(sql, [usuario, clave], (err, rows, fields) => {
            if (!err) {
                if (rows[0].length > 0) {
                    // Login exitoso
                    res.status(200).json({
                        mensaje: "Login correcto",
                        usuario: rows[0][0]  // Enviar los datos del usuario (sin clave si querés proteger)
                    });
                } else {
                    // Credenciales inválidas
                    res.status(401).json({ error: "Credenciales incorrectas" });
                }
            } else {
                console.error("Error en login:", err);
                res.status(500).json({ error: "Error interno en el servidor" });
            }
        });
    });

    return router;
};

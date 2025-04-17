// Importar paquetes necesarios
const express = require('express');
const mysql = require('mysql');
const app = express();

// Configurar el parseo de JSON y URL-encoded
app.use(express.json());
app.use(express.urlencoded({ extended: true }));

// Crear la conexión a la base de datos en index.js
const mysqlConnection = mysql.createConnection({
    host: '142.44.161.115',
    user: '1700PAC12025Equi4',
    password: '1700PAC12025Equi4#92',
    database: '1700PAC12025Equi4',
    port: 3306,
    multipleStatements: true
});

// Verificar la conexión
mysqlConnection.connect((err) => {
    if (!err) {
        console.log('Conexión exitosa');
    } else {
        console.log('Error al conectar a la base de datos', err);
    }
});

// Inyectar la conexión en los módulos de rutas
const reportesRoutes = require('./reportes')(mysqlConnection);
app.use('/reportes', reportesRoutes);

// Si tienes otros módulos, puedes inyectarles la conexión de la misma manera
const personasRoutes = require('./personas')(mysqlConnection);
app.use('/personas', personasRoutes);

const empleadosRoutes = require('./empleados')(mysqlConnection);
app.use('/empleados', empleadosRoutes);

const proveedoresRoutes = require('./proveedores')(mysqlConnection);
app.use('/proveedores', proveedoresRoutes);

const productosRoutes = require('./productos')(mysqlConnection);
app.use('/productos', productosRoutes);

const ventasRoutes = require('./ventas')(mysqlConnection);
app.use('/ventas', ventasRoutes);

const marcasRoutes = require('./marcas')(mysqlConnection);
app.use('/marcas', marcasRoutes);

const categoriasRoutes = require('./categorias')(mysqlConnection);
app.use('/categorias', categoriasRoutes);


const comprasRoutes = require('./compras')(mysqlConnection);
app.use('/compras', comprasRoutes);

const otrosRoutes = require('./otros')(mysqlConnection);
app.use('/otros', otrosRoutes);


// Ruta principal
app.get('/', (req, res) => res.send('Servidor en ejecución'));

// Iniciar el servidor
app.listen(3000, () => console.log('Servidor en puerto 3000'));




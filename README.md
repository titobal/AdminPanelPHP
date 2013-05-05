AdminPanelPHP
=============

Panel de administrador en php, con login y recuperación de contraseña, hace falta enviar los emails

La interfaz está hecha con Twitter Bootstrap y está en HTML5, la base de datos está con MySql y sólo necesita dos tablas, la del administrador y la de los códigos, para recuperar la contraseña

El código está muy bien identado, es muy fácil de leer y sólo utiliza JSON para la entrega de datos.

Está orientado a trabajar mayormente con javascript.


Ver el archivo script bd.txt para crear las tablas necesarias,

para insertar un administrador lo puede hacer con:

--Esta línea de código aparece en el archivo script bd.txt
INSERT INTO Admin VALUES (1,1,0,'admin@admin.cl',NULL,'c894c894c898aad8aad8aae5bce5bce5bc27bc27bc2731573157315');

Las credenciales son:
Usuario:    admin@admin.cl
Contraseña: PASSword.1

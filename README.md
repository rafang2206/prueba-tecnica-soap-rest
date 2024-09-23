# **Prueba Técnica de Epayco**

Este proyecto consiste en dos servicios: un **servicio SOAP** y un **servicio REST** que actúa como puente entre el cliente y el servicio SOAP. A continuación, se detallan los aspectos clave de la implementación, así como los pasos necesarios para la inicialización y uso del proyecto.

---

## **Descripción**
- **Servicio SOAP**:  
  Desarrollado en **Symfony** con **Doctrine ORM**, aplicando principios de **DDD (Domain-Driven Design)** y el patrón **Repository**. Este servicio se conecta a una base de datos **MongoDB** y expone tres rutas principales:
  - **/users**: Registro de usuarios.
  - **/wallets**: Verificación de saldo y recarga de saldo.
  - **/buy**: Simulación de compra de productos.

- **Servicio REST**:  
  Construido en **Node.js** con **TypeScript** y **Express**, siguiendo los principios de **DDD**, **Repository** y **SOLID**. Este servicio replica las rutas del servicio SOAP y actúa como un intermediario entre el cliente y el SOAP, procesando la lógica necesaria a través de controladores.

---

## **Requerimientos**
Antes de iniciar el proyecto, asegúrate de tener instalados los siguientes programas:

- **Node.js** v20.x
- **PHP 8**
- **Docker**
- **Symfony CLI**

---

Sigue los siguientes pasos para inicializar el proyecto:

1. **Configurar las variables de entorno del proyecto:**
   - Copia el archivo `.env.example` ubicado en la raíz del proyecto y renómbralo como `.env`.
   - Reemplaza las variables de entorno según la configuración de tu base de datos generada por Docker.

2. **Construir y levantar los servicios con Docker:**
   ```bash
   docker compose build
   docker compose up -d

Esto creara la Base de datos con Docker.

3. dirigirse a la carpeta services-soap
4. Copiar las variables de Entorno del archivo .env.example a un archivo .env

MONGO_URL = Url de la base de Datos Mongo
MONGO_DB_NAME = Nombre de la base de Datos Mongo

5. Crear una clave de Apps de Gmail y agregar a la variable de entorno
MAILER_DSN = gmail+smtp://CORREO-GMAIL:PASSWORD@default 

6. Añadir la url del Frontend que se recibira en el Correo Electronico, 
FRONTEND_URL_ROUTE_CODE = Ejemplo: http://localhost:3000/verify?code=

7. Una vez realizado estos Pasos, Ejecutar los comandos 
    ```bash
    composer install 
    symfony server:start

Esto iniciara el Servicio SOAP.

8. Posteriormente ingresar a la carpeta api-rest

9. Copiar las variables de Entorno del archivo .env.example a un archivo .env

10. Indicar el Puerto de la Aplicación PORT, por defecto es el 3000

11. Indicar la url del servicio SOAP ejemplo: SOAP_URL=http://localhost:8000/api

12. Ejecutar el comando npm install

13. Ejecutar el comando npm run dev

Una vez realizado estos pasos podremos inicar el uso de nuestros servicios.


RUTAS:
--------------------------------------------------------------------------
```bash
POST Register user
http://localhost:3000/api/user/register
Body:
{
    "document": "C1943300",
    "name": "Rafael",
    "email": "soliditydevpro@gmail.com",
    "phone": 3237419189
}
--------------------------------------------------------------------------
GET Get Balance
http://localhost:3000/api/wallet/balance?document=C1943300&phone=3237419189

Query Params:
document C1943300
phone 3237419189

--------------------------------------------------------------------------
POST Recharge Wallet
http://localhost:3000/api/wallet/recharge-wallet
Body:
{
    "document": "C1943300",
    "phone": 3237419189,
    "amount": 150
}

--------------------------------------------------------------------------
POST Get Code
http://localhost:3000/api/buy/get-code
Body:
{
    "document": "C1943300",
    "phone": 3237419189
}

--------------------------------------------------------------------------
Get Confirm Buy
http://localhost:3000/api/buy/confirm/27174/:code

Params:
code

Authorization
Bearer {sessionId}


Link de la Documentación https://documenter.getpostman.com/view/25258133/2sAXqtc2Zk
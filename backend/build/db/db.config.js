"use strict";

// module.exports = {
//   development: {
//     "username": "admin",
//     "password": "admin_pass",
//     "database": "nodejs_backend_db",
//     "host": "127.0.0.1",
//     "dialect": "postgres"
//   },
//   production: {
//     "use_env_variable": "DATABASE_URL",
//     "dialect": "postgres"
//   }
// };
module.exports = {
  development: {
    "username": "yuriy",
    "password": "Test1234!",
    "database": "antey",
    "host": "127.0.0.1",
    "port": "3306",
    "dialect": "mysql" // "username": "root",
    // "password": "",
    // "database": "antey",
    // "host": "127.0.0.1",
    // "port": "3306",
    // "dialect": "mysql"

  },
  production: {
    "use_env_variable": "DATABASE_URL",
    "dialect": "mysql"
  }
};
'use strict';

var bcrypt = require("bcryptjs");

var config = require("../../config");

module.exports = {
  up: function up(queryInterface, Sequelize) {
    var hash = bcrypt.hashSync(config.admin_pass, config.bcrypt.saltRounds);
    return queryInterface.bulkInsert('Users', [{
      email: 'admin@flatlogic.com',
      password: hash,
      name: 'admin',
      createdAt: new Date(),
      updatedAt: new Date()
    }], {});
  },
  down: function down(queryInterface, Sequelize) {
    return queryInterface.bulkDelete('Users', null, {});
  }
};
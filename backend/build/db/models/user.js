'use strict';

module.exports = function (sequelize, DataTypes) {
  var User = sequelize.define('User', {
    email: DataTypes.STRING,
    password: DataTypes.STRING
  }, {});

  User.associate = function (models) {// associations can be defined here
  };

  return User;
};
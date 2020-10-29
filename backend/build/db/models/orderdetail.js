'use strict';

module.exports = function (sequelize, DataTypes) {
  var Orderdetail = sequelize.define('Orderdetail', {
    OrderDetailID: {
      type: DataTypes.INTEGER,
      primaryKey: true
    },
    OrderID: DataTypes.INTEGER,
    ProductID: DataTypes.INTEGER,
    OrderNumber: DataTypes.INTEGER,
    Price: DataTypes.STRING,
    Quantity: DataTypes.INTEGER,
    Discount: DataTypes.STRING,
    Total: DataTypes.STRING,
    IDSKU: DataTypes.INTEGER,
    Size: DataTypes.STRING,
    Color: DataTypes.STRING,
    Fulfilled: DataTypes.TINYINT(1),
    ShipDate: DataTypes.STRING,
    BillDate: DataTypes.DATE
  }, {});

  Orderdetail.associate = function (models) {// associations can be defined here
  };

  return Orderdetail;
};
"use strict";

var _express = _interopRequireDefault(require("express"));

var _models = require("../db/models");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var router = _express["default"].Router();

router.post('/', function (req, res) {
  var product = req.body;

  if (product.id) {
    delete product.id;
  }

  _models.Orderdetail.create(req.body).then(function (product) {
    res.json(product);
  });
});
module.exports = router;
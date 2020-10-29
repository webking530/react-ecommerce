"use strict";

var _express = _interopRequireDefault(require("express"));

var _mock = _interopRequireDefault(require("../mock"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var router = _express["default"].Router();

router.get('/', function (req, res) {
  res.json(_mock["default"].analytics);
});
module.exports = router;
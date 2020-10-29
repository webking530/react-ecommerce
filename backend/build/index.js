"use strict";

require("@babel/polyfill");

var _express = _interopRequireDefault(require("express"));

var _cors = _interopRequireDefault(require("cors"));

var _bodyParser = _interopRequireDefault(require("body-parser"));

var _passport = _interopRequireDefault(require("passport"));

var _products = _interopRequireDefault(require("./routes/products"));

var _analytics = _interopRequireDefault(require("./routes/analytics"));

var _user = _interopRequireDefault(require("./routes/user"));

var _customer = _interopRequireDefault(require("./routes/customer"));

var _orderdetail = _interopRequireDefault(require("./routes/orderdetail"));

require("./auth/auth");

var _models = _interopRequireDefault(require("./db/models"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

// import cron from "node-cron";
var _require = require('child_process'),
    exec = _require.exec;

var app = (0, _express["default"])();
var port = process.env.PORT || 8080;
app.use(_bodyParser["default"].json());
app.use(_bodyParser["default"].urlencoded({
  extended: true
}));
app.use((0, _cors["default"])());
app.use(_express["default"]["static"]('public'));
app.use('/products', _products["default"]);
app.use('/analytics', _passport["default"].authenticate("jwt", {
  session: false
}), _analytics["default"]);
app.use('/user', _user["default"]);
app.use('/orderdetail', _orderdetail["default"]);
app.use('/customer', _passport["default"].authenticate("jwt", {
  session: false
}), _customer["default"]);

_models["default"].sequelize.sync().then(function () {
  app.listen(port, function () {
    console.log("b2c antey - listening on port ".concat(port, "!!"));
  }); // cron.schedule('* */2 * * *', () => {
  //   exec('yarn products:update', (err) => {
  //     if (err) {
  //       console.error(err);
  //     }
  //   });
  // });
});
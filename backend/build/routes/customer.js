"use strict";

var _express = _interopRequireDefault(require("express"));

var _models = require("../db/models");

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

// import config from "../config";
var router = _express["default"].Router();

router.post('/', function (req, res) {
  res.json(req.user);
});
router.post('/update/billing', function (req, res) {
  _models.Customer.findOne({
    where: {
      CustomerID: req.user.id,
      Email: req.user.email
    }
  }).then(function (cus) {
    _models.Customer.update(_objectSpread({}, req.body), {
      where: {
        CustomerID: cus.CustomerID
      }
    }).then(function (customer) {
      res.json(customer);
    })["catch"](function (err) {
      res.status(err.statusCode).send(err.message);
    });
  })["catch"](function () {
    res.status(err.statusCode).send(err.message);
  });
});
router.post('/update', function (req, res) {
  _models.Customer.update(_objectSpread({}, req.body, {}, {
    FirstName: req.body.FirstName.split(' ')[0],
    LastName: req.body.FirstName.split(' ')[1] ? req.body.FirstName.split(' ')[1] : ''
  }), {
    where: {
      CustomerID: req.user.id
    }
  }).then(function (user) {
    res.json(user);
  })["catch"](function (err) {
    res.status(err.statusCode).send(err.message);
  });
});
router.get('/get/account', function (req, res) {
  _models.Customer.findOne({
    where: {
      CustomerID: req.user.id
    }
  }).then(function (customer) {
    if (customer) {
      res.json(customer);
    } else {
      res.status(err.statusCode).send('Invalid Account');
    }
  })["catch"](function (err) {
    res.status(err.statusCode).send(err.message);
  });
});
router.get('/get/address', function (req, res) {
  console.log(req.user);

  _models.Customer.findOne({
    where: {
      CustomerID: req.user.id
    }
  }).then(function (customer) {
    if (customer) {
      res.json(customer);
    } else {
      res.status(err.statusCode).send('No Address yet.');
    }
  })["catch"](function (err) {
    res.status(err.statusCode).send(err.message);
  });
});
module.exports = router;
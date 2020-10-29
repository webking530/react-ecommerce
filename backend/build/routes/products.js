"use strict";

var _express = _interopRequireDefault(require("express"));

var _models = require("../db/models");

var _config = _interopRequireDefault(require("../config"));

var _fs = _interopRequireDefault(require("fs"));

var _path = _interopRequireDefault(require("path"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

var router = _express["default"].Router();

router.get('/images-list', function (req, res) {
  _fs["default"].readdir(_path["default"].resolve(process.env.PWD + '/public/assets/products/'), function (err, files) {
    files = files.filter(function (item) {
      return !/(^|\/)\.[^\/\.]/g.test(item);
    }).map(function (f) {
      return _config["default"].apiUrl + '/assets/products/' + f;
    });
    res.json(files);
  });
});
router.get('/', function (req, res) {
  _models.Product.findAll().then(function (products) {
    res.json(products);
  });
});
router.get('/:id', function (req, res) {
  _models.Product.findByPk(req.params.id).then(function (product) {
    res.json(product);
  });
});
router.put('/:id', function (req, res) {
  _models.Product.update(req.body, {
    where: {
      id: req.params.id
    },
    returning: true,
    plain: true
  }).then(function (_ref) {
    var _ref2 = _slicedToArray(_ref, 2),
        model = _ref2[1];

    res.json(model.dataValues);
  });
});
router.post('/', function (req, res) {
  var product = req.body;

  if (product.id) {
    delete product.id;
  }

  _models.Product.create(req.body).then(function (product) {
    res.json(product);
  });
});
router["delete"]('/:id', function (req, res) {
  _models.Product.destroy({
    where: {
      id: req.params.id
    }
  }).then(function (response) {
    res.json(response);
  });
});
module.exports = router;
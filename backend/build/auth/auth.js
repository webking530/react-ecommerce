"use strict";

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

var _config = _interopRequireDefault(require("../config"));

var _helpers = _interopRequireDefault(require("../helpers/helpers"));

var _models = _interopRequireWildcard(require("../db/models"));

function _getRequireWildcardCache() { if (typeof WeakMap !== "function") return null; var cache = new WeakMap(); _getRequireWildcardCache = function _getRequireWildcardCache() { return cache; }; return cache; }

function _interopRequireWildcard(obj) { if (obj && obj.__esModule) { return obj; } if (obj === null || _typeof(obj) !== "object" && typeof obj !== "function") { return { "default": obj }; } var cache = _getRequireWildcardCache(); if (cache && cache.has(obj)) { return cache.get(obj); } var newObj = {}; var hasPropertyDescriptor = Object.defineProperty && Object.getOwnPropertyDescriptor; for (var key in obj) { if (Object.prototype.hasOwnProperty.call(obj, key)) { var desc = hasPropertyDescriptor ? Object.getOwnPropertyDescriptor(obj, key) : null; if (desc && (desc.get || desc.set)) { Object.defineProperty(newObj, key, desc); } else { newObj[key] = obj[key]; } } } newObj["default"] = obj; if (cache) { cache.set(obj, newObj); } return newObj; }

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(n); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) { try { var info = gen[key](arg); var value = info.value; } catch (error) { reject(error); return; } if (info.done) { resolve(value); } else { Promise.resolve(value).then(_next, _throw); } }

function _asyncToGenerator(fn) { return function () { var self = this, args = arguments; return new Promise(function (resolve, reject) { var gen = fn.apply(self, args); function _next(value) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value); } function _throw(err) { asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err); } _next(undefined); }); }; }

var passport = require('passport');

var JWTstrategy = require('passport-jwt').Strategy;

var GoogleStrategy = require('passport-google-oauth2').Strategy;

var MicrosoftStrategy = require('passport-microsoft').Strategy;

var FacebookStrategy = require('passport-facebook').Strategy;

var ExtractJWT = require('passport-jwt').ExtractJwt;

passport.use(new JWTstrategy({
  secretOrKey: _config["default"].secret_key,
  jwtFromRequest: ExtractJWT.fromAuthHeaderAsBearerToken()
}, /*#__PURE__*/function () {
  var _ref = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(token, done) {
    return regeneratorRuntime.wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            _context.prev = 0;
            return _context.abrupt("return", done(null, token.user));

          case 4:
            _context.prev = 4;
            _context.t0 = _context["catch"](0);
            done(_context.t0);

          case 7:
          case "end":
            return _context.stop();
        }
      }
    }, _callee, null, [[0, 4]]);
  }));

  return function (_x, _x2) {
    return _ref.apply(this, arguments);
  };
}()));
passport.use(new GoogleStrategy({
  clientID: _config["default"].google.clientId,
  clientSecret: _config["default"].google.clientSecret,
  callbackURL: _config["default"].apiUrl + "/user/signin/google/callback",
  passReqToCallback: true
}, function (request, accessToken, refreshToken, profile, done) {
  console.log('google login', profile);

  _models.User.findOrCreate({
    where: {
      email: profile.email
    }
  }).then(function (_ref2) {
    var _ref3 = _slicedToArray(_ref2, 2),
        user = _ref3[0],
        created = _ref3[1];

    var body = {
      id: user.id,
      email: user.email,
      name: profile.displayName // avatar: profile.picture

    };

    var token = _helpers["default"].jwtSign({
      user: body
    });

    return done(null, {
      token: token
    });
  });
}));
passport.use(new FacebookStrategy({
  clientID: _config["default"].facebook.appid,
  clientSecret: _config["default"].facebook.secrete,
  callbackURL: _config["default"].apiUrl + "/user/signin/facebook/callback"
}, function (accessToken, refreshToken, profile, done) {
  _models.User.findOrCreate({
    where: {
      email: profile.email
    }
  }).then(function (_ref4) {
    var _ref5 = _slicedToArray(_ref4, 2),
        user = _ref5[0],
        created = _ref5[1];

    var body = {
      id: user.id,
      email: user.email,
      name: profile.displayName // avatar: profile.picture

    };

    var token = _helpers["default"].jwtSign({
      user: body
    });

    return done(null, {
      token: token
    });
  });
}));
passport.use(new MicrosoftStrategy({
  clientID: _config["default"].microsoft.clientId,
  clientSecret: _config["default"].microsoft.clientSecret,
  callbackURL: _config["default"].apiUrl + "/user/signin/microsoft/callback",
  passReqToCallback: true
}, function (request, accessToken, refreshToken, profile, done) {
  var email = profile._json.mail || profile._json.userPrincipalName;

  _models["default"].User.findOrCreate({
    where: {
      email: email
    }
  }).then(function (_ref6) {
    var _ref7 = _slicedToArray(_ref6, 2),
        user = _ref7[0],
        created = _ref7[1];

    var body = {
      id: user.id,
      email: user.email,
      name: profile.displayName
    };

    var token = _helpers["default"].jwtSign({
      user: body
    });

    return done(null, {
      token: token
    });
  });
}));
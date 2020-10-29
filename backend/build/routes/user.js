"use strict";

var _express = _interopRequireDefault(require("express"));

var _bcryptjs = _interopRequireDefault(require("bcryptjs"));

var _passport = _interopRequireDefault(require("passport"));

var _models = require("../db/models");

var _config = _interopRequireDefault(require("../config"));

var _helpers = _interopRequireDefault(require("../helpers/helpers"));

function _interopRequireDefault(obj) { return obj && obj.__esModule ? obj : { "default": obj }; }

var router = _express["default"].Router();

router.post('/signup', function (req, res) {
  _models.Customer.findOne({
    where: {
      Email: req.body.email
    }
  }).then(function (customer) {
    if (customer) {
      res.status(400).send("Customer with this email is already exists");
    } else {
      _bcryptjs["default"].hash(req.body.password, _config["default"].bcrypt.saltRounds, function (err, hash) {
        _models.Customer.create({
          Email: req.body.email,
          Password: hash
        }).then(function (customer) {
          res.json(customer);
        });
      });
    }
  })["catch"](function (err) {
    res.status(err.statusCode).send(err.message);
  });
});
router.post('/signin/local', function (req, res) {
  _models.Customer.findOne({
    where: {
      Email: req.body.email
    }
  }).then(function (user) {
    _bcryptjs["default"].compare(req.body.password, user.Password).then(function (equal) {
      if (equal) {
        var body = {
          id: user.CustomerID,
          email: user.Email
        };

        var token = _helpers["default"].jwtSign({
          user: body
        });

        res.json({
          user: user,
          success: true,
          token: token
        });
      } else {
        res.status(400).send("Wrong password");
      }
    });
  })["catch"](function () {
    res.status(400).send("Customer with this email does not exist");
  });
});
router.get('/signin/google', function (req, res, next) {
  _passport["default"].authenticate("google", {
    scope: ["profile", "email"],
    state: req.query.app
  })(req, res, next);
});
router.get('/signin/google/callback', _passport["default"].authenticate("google", {
  failureRedirect: "/login",
  session: false
}), function (req, res) {
  socialRedirect(res, req.query.state, req.user.token, _config["default"]);
});
router.get('/signin/facebook', function (req, res, next) {
  _passport["default"].authenticate("facebook", {
    scope: ["profile", "email"],
    state: req.query.app
  })(req, res, next);
});
router.get('/signin/facebook/callback', _passport["default"].authenticate("facebook", {
  failureRedirect: "/login",
  session: false
}), function (req, res) {
  socialRedirect(res, req.query.state, req.user.token, _config["default"]);
});
router.get('/signin/microsoft', function (req, res, next) {
  _passport["default"].authenticate("microsoft", {
    scope: ["https://graph.microsoft.com/user.read openid"],
    state: req.query.app
  })(req, res, next);
});
router.get('/signin/microsoft/callback', _passport["default"].authenticate("microsoft", {
  failureRedirect: "/login",
  session: false
}), function (req, res) {
  socialRedirect(res, req.query.state, req.user.token, _config["default"]);
});

function socialRedirect(res, state, token, config) {
  var url;
  var fullPath = /^http(s?):\/\//.test(state);

  if (fullPath) {
    url = state;
  } else {
    url = config.hostUI + "".concat(config.portUI ? ":".concat(config.portUI) : "") + "".concat(state ? "/".concat(state) : "");
  }

  res.redirect(url + "/#/login?token=" + token);
}

module.exports = router;
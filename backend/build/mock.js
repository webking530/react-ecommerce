"use strict";

Object.defineProperty(exports, "__esModule", {
  value: true
});
exports["default"] = void 0;
var _default = {
  analytics: {
    visits: {
      count: 4.332,
      logins: 830,
      sign_out_pct: 0.5,
      rate_pct: 4.5
    },
    performance: {
      sdk: {
        this_period_pct: 60,
        last_period_pct: 30
      },
      integration: {
        this_period_pct: 40,
        last_period_pct: 55
      }
    },
    server: {
      1: {
        pct: 60,
        temp: 37,
        frequency: 3.3
      },
      2: {
        pct: 54,
        temp: 31,
        frequency: 3.3
      }
    },
    revenue: getRevenueData(),
    mainChart: getMainChartData()
  }
};
exports["default"] = _default;

function getRevenueData() {
  var data = [];
  var seriesCount = 3;
  var accessories = ['SMX', 'Direct', 'Networks'];

  for (var i = 0; i < seriesCount; i += 1) {
    data.push({
      label: accessories[i],
      data: Math.floor(Math.random() * 100) + 1
    });
  }

  return data;
}

function getMainChartData() {
  function getRandomData(length, min, max) {
    var multiplier = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : 10;
    var maxDiff = arguments.length > 4 && arguments[4] !== undefined ? arguments[4] : 10;
    var array = new Array(length).fill();
    var lastValue;
    return array.map(function (item, index) {
      var randomValue = Math.floor(Math.random() * multiplier + 1);

      while (randomValue <= min || randomValue >= max || lastValue && randomValue - lastValue > maxDiff) {
        randomValue = Math.floor(Math.random() * multiplier + 1);
      }

      lastValue = randomValue;
      return [index, randomValue];
    });
  }

  var d1 = getRandomData(31, 3500, 6500, 7500, 1000);
  var d2 = getRandomData(31, 1500, 7500, 7500, 1500);
  var d3 = getRandomData(31, 1500, 7500, 7500, 1500);
  return [d1, d2, d3];
}
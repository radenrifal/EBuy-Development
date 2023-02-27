$(function () {

  /*
  * ---------------------------------------
  * Charts
  * ---------------------------------------
  */
  var Charts = function() {
    var e, a = $('[data-toggle="chart"]'),
      t = "light",
      n = {
        base: "Open Sans"
      },
      i = {
        gray: {
          100: "#f6f9fc",
          200: "#e9ecef",
          300: "#dee2e6",
          400: "#ced4da",
          500: "#adb5bd",
          600: "#8898aa",
          700: "#525f7f",
          800: "#32325d",
          900: "#212529"
        },
        theme: {
          default: "#172b4d",
          primary: "#5e72e4",
          secondary: "#f4f5f7",
          info: "#11cdef",
          success: "#2dce89",
          danger: "#f5365c",
          warning: "#fb6340"
        },
        black: "#12263F",
        white: "#FFFFFF",
        transparent: "transparent"
      };

    function o(e, a) {
      for (var t in a) "object" != typeof a[t] ? e[t] = a[t] : o(e[t], a[t])
    }

    function s(e) {
      var a = e.data("add"),
        t = $(e.data("target")).data("chart");
      e.is(":checked") ? (! function e(a, t) {
        for (var n in t) Array.isArray(t[n]) ? t[n].forEach(function(e) {
          a[n].push(e)
        }) : e(a[n], t[n])
      }(t, a), t.update()) : (! function e(a, t) {
        for (var n in t) Array.isArray(t[n]) ? t[n].forEach(function(e) {
          a[n].pop()
        }) : e(a[n], t[n])
      }(t, a), t.update())
    }

    function l(e) {
      var a = e.data("update"),
        t = $(e.data("target")).data("chart");
      o(t, a),
        function(e, a) {
          if (void 0 !== e.data("prefix") || void 0 !== e.data("prefix")) {
            var t = e.data("prefix") ? e.data("prefix") : "",
              n = e.data("suffix") ? e.data("suffix") : "";
            a.options.scales.yAxes[0].ticks.callback = function(e) {
              if (!(e % 10)) return t + e + n
            }, a.options.tooltips.callbacks.label = function(e, a) {
              var i = a.datasets[e.datasetIndex].label || "",
                o = e.yLabel,
                s = "";
                if ( a.datasets.length ) {
                    s += i + ' ';
                }
              return s += t + o + n
            }
          }
        }(e, t), t.update()
    }
    return window.Chart && o(Chart, (e = {
      defaults: {
        global: {
          responsive: !0,
          maintainAspectRatio: !1,
          defaultColor: "dark" == t ? i.gray[700] : i.gray[600],
          defaultFontColor: "dark" == t ? i.gray[700] : i.gray[600],
          defaultFontFamily: n.base,
          defaultFontSize: 13,
          layout: {
            padding: 0
          },
          legend: {
            display: !1,
            position: "bottom",
            labels: {
              usePointStyle: !0,
              padding: 16
            }
          },
          elements: {
            point: {
              radius: 0,
              backgroundColor: i.theme.primary
            },
            line: {
              tension: .4,
              borderWidth: 4,
              borderColor: i.theme.primary,
              backgroundColor: i.transparent,
              borderCapStyle: "rounded"
            },
            rectangle: {
              backgroundColor: i.theme.warning
            },
            arc: {
              backgroundColor: i.theme.primary,
              borderColor: "dark" == t ? i.gray[800] : i.white,
              borderWidth: 4
            }
          },
          tooltips: {
            enabled: !0,
            mode: "index",
            intersect: !1
          }
        },
        doughnut: {
          cutoutPercentage: 83,
          legendCallback: function(e) {
            var a = e.data,
              t = "";
            return a.labels.forEach(function(e, n) {
              var i = a.datasets[0].backgroundColor[n];
              t += '<span class="chart-legend-item">', t += '<i class="chart-legend-indicator" style="background-color: ' + i + '"></i>', t += e, t += "</span>"
            }), t
          }
        }
      }
    }, Chart.scaleService.updateScaleDefaults("linear", {
      gridLines: {
        borderDash: [2],
        borderDashOffset: [2],
        color: "dark" == t ? i.gray[900] : i.gray[300],
        drawBorder: !1,
        drawTicks: !1,
        drawOnChartArea: !0,
        zeroLineWidth: 0,
        zeroLineColor: "rgba(0,0,0,0)",
        zeroLineBorderDash: [2],
        zeroLineBorderDashOffset: [2]
      },
      ticks: {
        beginAtZero: !0,
        padding: 10,
        callback: function(e) {
          if (!(e % 10)) return e
        }
      }
    }), Chart.scaleService.updateScaleDefaults("category", {
      gridLines: {
        drawBorder: !1,
        drawOnChartArea: !1,
        drawTicks: !1
      },
      ticks: {
        padding: 20
      },
      maxBarThickness: 10
    }), e)), a.on({
      change: function() {
        var e = $(this);
        e.is("[data-add]") && s(e)
      },
      click: function() {
        var e = $(this);
        e.is("[data-update]") && l(e)
      }
    }), {
      colors: i,
      fonts: n,
      mode: t
    }
  }();

  var SalesChart = function() {
    var e, a, t = $("#chart-sales-dark");
    t.length && (e = t, a = new Chart(e, {
      type: "line",
      options: {
        scales: {
          yAxes: [{
            gridLines: {
              color: Charts.colors.gray[700],
              zeroLineColor: Charts.colors.gray[700]
            },
            ticks: {}
          }]
        }
      },
      data: {
        labels: [""],
        datasets: [{
          label: "Sales",
          data: [0]
        }]
      }
    }), e.data("chart", a))
  }();

  var BarsChart = (
    SalesChart = function() {
      var e, a, t = $("#chart-sales");
      t.length && (e = t, a = new Chart(e, {
        type: "line",
        options: {
          scales: {
            yAxes: [{
              gridLines: {
                color: Charts.colors.gray[200],
                zeroLineColor: Charts.colors.gray[200]
              },
              ticks: {}
            }]
          }
        },
        data: {
          labels: [],
          datasets: [{
            label: "Sales",
            data: []
          }]
        }
      }), e.data("chart", a))
    }(), function() {
      var e = $("#chart-bars");
      e.length && function(e) {
        var a = new Chart(e, {
          type: "bar",
          data: {
            labels: [""],
            datasets: [{
              label: "Sales",
              data: [0]
            }]
          }
        });
        e.data("chart", a)
      }(e)
    }
  )();

  $('.sales-dark').trigger('click');
  $('.sales-canvas').trigger('click');
});
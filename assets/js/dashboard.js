(function($) {
  'use strict';
  $(function() {
    Chart.defaults.global.legend.labels.usePointStyle = true;
    if ($("#sales-chart").length) {
      var ctx = document.getElementById('sales-chart').getContext("2d");

      var gradientStroke1 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke1.addColorStop(0, 'rgba(83, 227 ,218, 0.9)');
      gradientStroke1.addColorStop(1, 'rgba(45, 180 ,235, 0.9)');

      var gradientStroke2 = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStroke2.addColorStop(0, 'rgba(132, 179 ,247, 0.9)');
      gradientStroke2.addColorStop(1, 'rgba(164, 90 ,249, 0.9)');

      var myChart = new Chart(ctx, {
          type: 'line',
          data: {
              labels: [1, 2, 3, 4, 5, 6, 7, 8],
              datasets: [
                {
                  label: "Audi",
                  borderColor: gradientStroke2,
                  backgroundColor: gradientStroke2,
                  pointRadius: 0,
                  fill: false,
                  borderWidth: 1,
                  fill: 'origin',
                  data: [0, 30, 60, 25, 60, 25, 50, 0]
                },
                {
                  label: "BMW",
                  borderColor: gradientStroke1,
                  borderColor: gradientStroke1,
                  backgroundColor: gradientStroke1,
                  pointRadius: 0,
                  fill: false,
                  borderWidth: 1,
                  fill: 'origin',
                  data: [0, 60, 25, 80, 35, 75, 30, 0]
                }
            ]
          },
          options: {
              legend: {
                  position: "top"
              },
              scales: {
                xAxes: [{
                  ticks: {
                    display: true,
                    beginAtZero:true,
                    fontColor: 'rgba(0, 0, 0, 1)'
                  },
                  gridLines: {
                    display:false,
                    drawBorder: false,
                    color: 'transparent',
                    zeroLineColor: '#eeeeee'
                  }
                }],
                yAxes: [{
                  gridLines: {
                    drawBorder: false,
                    display:true,
                    color: '#eeeeee',
                  },
                  categoryPercentage: 0.5,
                  ticks: {
                    display: true,
                    beginAtZero: true,
                    stepSize: 20,
                    max: 100,
                    fontColor: 'rgba(0, 0, 0, 1)'
                  }
                }]
              },
              },
              elements: {
                point: {
                  radius: 0
                }
              }
            })
    }
    if ($("#satisfaction-chart").length) {
      var ctx = document.getElementById('satisfaction-chart').getContext("2d");

      var gradientStrokeBluePurple = ctx.createLinearGradient(0, 0, 0, 250);
      gradientStrokeBluePurple.addColorStop(0, '#5607fb');
      gradientStrokeBluePurple.addColorStop(1, '#9425eb');
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: [11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26],
              datasets: [
                {
                  label: "Audi",
                  borderColor: gradientStrokeBluePurple,
                  backgroundColor: gradientStrokeBluePurple,
                  hoverBackgroundColor: gradientStrokeBluePurple,
                  pointRadius: 0,
                  fill: false,
                  borderWidth: 1,
                  fill: 'origin',
                  data: [50, 45, 25, 35, 40, 25, 15, 40, 20, 15, 30, 50, 26, 45, 37, 26]
                }
            ]
          },
          options: {
              legend: {
                  display: false
              },
              scales: {
                  yAxes: [{
                      ticks: {
                          display: false,
                          min: 0,
                          stepSize: 10
                      },
                      gridLines: {
                        drawBorder: false,
                        display: true
                      }
                  }],
                  xAxes: [{
                      gridLines: {
                        display:false,
                        drawBorder: false,
                        color: 'rgba(0,0,0,1)',
                        zeroLineColor: '#eeeeee'
                      },
                      ticks: {
                          padding: 20,
                          fontColor: "rgba(0,0,0,1)",
                          autoSkip: true,
                          maxTicksLimit: 6
                      },
                      barPercentage: 0.7
                  }]
                }
              },
              elements: {
                point: {
                  radius: 0
                }
              }
            })
    }
    if ($("#serviceSaleProgress").length) {
      var bar = new ProgressBar.Circle(serviceSaleProgress, {
        color: 'url(#gradient)',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 6,
        trailWidth: 6,
        easing: 'easeInOut',
        duration: 1400,
        text: {
          autoStyleContainer: false
        },
        from: { color: '#aaa', width: 6 },
        to: { color: '#57c7d4', width: 6 },
        // Set default step function for all animate calls
        step: function(state, circle) {
          var value = Math.round(circle.value() * 100);
          circle.setText('$234M');
        }
      });

      bar.text.style.fontSize = 'http://www.bootstrapdash.com/demo/purple/js/1.5rem';
      bar.animate(.85);  // Number from 0.0 to 1.0
      let linearGradient = '<defs><linearGradient id="gradient" x1="0%" y1="0%" x2="100%" y2="0%" gradientUnits="userSpaceOnUse"><stop offset="20%" stop-color="#f09819"/><stop offset="50%" stop-color="#ff5858"/></linearGradient></defs>';
      bar.svg.insertAdjacentHTML('afterBegin', linearGradient);
    }
    if ($("#productSaleProgress").length) {
      var bar = new ProgressBar.Circle(productSaleProgress, {
        color: 'url(#productGradient)',
        // This has to be the same size as the maximum width to
        // prevent clipping
        strokeWidth: 6,
        trailWidth: 6,
        easing: 'easeInOut',
        duration: 1400,
        text: {
          autoStyleContainer: false
        },
        from: { color: '#aaa', width: 6 },
        to: { color: '#57c7d4', width: 6 },
        // Set default step function for all animate calls
        step: function(state, circle) {
          var value = Math.round(circle.value() * 100);
          circle.setText('$123M');
        }
      });

      bar.text.style.fontSize = 'http://www.bootstrapdash.com/demo/purple/js/1.5rem';
      bar.animate(.6);  // Number from 0.0 to 1.0
      let linearGradient = '<defs><linearGradient id="productGradient" x1="0%" y1="0%" x2="100%" y2="0%" gradientUnits="userSpaceOnUse"><stop offset="40%" stop-color="#f5d100"/><stop offset="70%" stop-color="#50cc7f"/></linearGradient></defs>';
      bar.svg.insertAdjacentHTML('afterBegin', linearGradient);
    }
    if ($("#category-sale-chart").length) {
      var ctx = document.getElementById('category-sale-chart').getContext("2d");

      var gradientStrokeBlueVariant = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStrokeBlueVariant.addColorStop(0, 'rgba(4, 190 ,254, 0.7)');
      gradientStrokeBlueVariant.addColorStop(1, 'rgba(68, 129 ,235, 0.7)');

      var gradientStrokeMagentaPink = ctx.createLinearGradient(0, 0, 0, 300);
      gradientStrokeMagentaPink.addColorStop(0, 'rgba(255, 5 ,124, 0.7)');
      gradientStrokeMagentaPink.addColorStop(0.5, 'rgba(141, 11 ,147, 0.7)');
      var myChart = new Chart(ctx, {
          type: 'bar',
          data: {
              labels: [2008, 2010, 2012, 2014],
              datasets: [
                {
                  label: "Clothing",
                  borderColor: gradientStrokeBlueVariant,
                  backgroundColor: gradientStrokeBlueVariant,
                  hoverBackgroundColor: gradientStrokeBlueVariant,
                  pointRadius: 0,
                  fill: false,
                  borderWidth: 1,
                  fill: 'origin',
                  data: [50, 40, 30, 20]
                },
                {
                  label: "Electronics",
                  borderColor: gradientStrokeMagentaPink,
                  backgroundColor: gradientStrokeMagentaPink,
                  hoverBackgroundColor: gradientStrokeMagentaPink,
                  pointRadius: 0,
                  fill: false,
                  borderWidth: 1,
                  fill: 'origin',
                  data: [40, 30, 20, 10]
                }
            ]
          },
          options: {
              legend: {
                  position: 'bottom'
              },
              scales: {
                  yAxes: [{
                      ticks: {
                          display: false,
                          min: 0,
                          stepSize: 10
                      },
                      gridLines: {
                        drawBorder: false,
                        display: true
                      }
                  }],
                  xAxes: [{
                      gridLines: {
                        display:false,
                        drawBorder: false,
                        color: 'rgba(0,0,0,1)',
                        zeroLineColor: '#eeeeee'
                      },
                      ticks: {
                          padding: 20,
                          fontColor: "rgba(0,0,0,1)",
                          autoSkip: true,
                      },
                      barPercentage: 0.7
                  }]
                }
              },
              elements: {
                point: {
                  radius: 0
                }
              }
            })
    }
    if ($("#inline-datepicker").length) {
      $('#inline-datepicker').datepicker({
        enableOnReadonly: true,
        todayHighlight: true,
      });
    }
  });
})(jQuery);
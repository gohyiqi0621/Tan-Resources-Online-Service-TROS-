$(function () {
    "use strict";




// chart 1


      var options = {
      series: [customerGrowth], // Use PHP value
      chart: {
          height: 180,
          type: 'radialBar',
          toolbar: { show: false }
      },
      plotOptions: {
          radialBar: {
              startAngle: -110,
              endAngle: 110,
              hollow: { size: '70%' },
              track: { background: 'rgba(255, 255, 255, 0.1)', strokeWidth: '100%' },
              dataLabels: {
                  name: { show: false }, // Hide the label
                  value: {
                      fontSize: '28px',
                      fontWeight: 'bold',
                      color: '#fff', // Adjust for dark backgrounds
                      offsetY: 5,
                      formatter: function (val) {
                          return Math.round(val) + "%"; // Show only the percentage
                      }
                  }
              }
          }
      },
      fill: {
          type: 'gradient',
          gradient: {
              shade: 'dark',
              type: 'horizontal',
              gradientToColors: ['#ff416c'],
              opacityFrom: 1,
              opacityTo: 1,
              stops: [0, 100]
          }
      },
      colors: ["#ffd200"],
      stroke: { lineCap: 'round' }
  };

  var chart = new ApexCharts(document.querySelector("#chart1"), options);
  chart.render();




 // chart 2

 var options = {
    series: [{
        name: "Net Sales",
        data: [4, 10, 25, 12, 25, 18, 40, 22, 7]
    }],
    chart: {
        //width:150,
        height: 105,
        type: 'area',
        sparkline: {
            enabled: !0
        },
        zoom: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: 3,
        curve: 'smooth'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            gradientToColors: ['#0866ff'],
            shadeIntensity: 1,
            type: 'vertical',
            opacityFrom: 0.5,
            opacityTo: 0.0,
            //stops: [0, 100, 100, 100]
        },
    },

    colors: ["#02c27a"],
    tooltip: {
        theme: "dark",
        fixed: {
            enabled: !1
        },
        x: {
            show: !1
        },
        y: {
            title: {
                formatter: function (e) {
                    return ""
                }
            }
        },
        marker: {
            show: !1
        }
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    }
};

var chart = new ApexCharts(document.querySelector("#chart2"), options);
chart.render();




    // chart 3

    var options = {
        series: [{
            name: "Net Sales",
            data: [4, 10, 12, 17, 25, 30, 40, 55, 68]
        }],
        chart: {
            //width:150,
            height: 120,
            type: 'bar',
            sparkline: {
                enabled: !0
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            curve: 'smooth',
            color: ['transparent']
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: ['#7928ca'],
                shadeIntensity: 1,
                type: 'vertical',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        colors: ["#ff0080"],
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 4,
                borderRadiusApplication: 'around',
                borderRadiusWhenStacked: 'last',
                columnWidth: '45%',
            }
        },

        tooltip: {
            theme: "dark",
            fixed: {
                enabled: !1
            },
            x: {
                show: !1
            },
            y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            },
            marker: {
                show: !1
            }
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart3"), options);
    chart.render();




    // chart 4

    var options = {
        series: [{
            name: "Net Sales",
            data: [4, 25, 14, 34, 10, 39]
        }],
        chart: {
            //width:150,
            height: 105,
            type: 'line',
            sparkline: {
                enabled: !0
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 3,
            curve: 'straight'
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: ['#00f2fe'],
                shadeIntensity: 1,
                type: 'vertical',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },

        colors: ["#ee0979"],
        tooltip: {
            theme: "dark",
            fixed: {
                enabled: !1
            },
            x: {
                show: !1
            },
            y: {
                title: {
                    formatter: function (e) {
                        return ""
                    }
                }
            },
            marker: {
                show: !1
            }
        },
        markers: {
            show: !1,
            size: 5,
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart4"), options);
    chart.render();



    
    // chart 5
/** 
    var options = {
        series: [{
            name: "Desktops",
            data: [14, 41, 35, 51, 25, 18, 21, 35, 15]
        }],
        chart: {
            foreColor: "#9ba7b2",
            height: 280,
            type: 'bar',
            toolbar: {
                show: !1
            },
            sparkline: {
                enabled: !1
            },
            zoom: {
                enabled: false
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            width: 1,
            curve: 'smooth'
        },
        plotOptions: {
            bar: {
                horizontal: false,
                borderRadius: 4,
                borderRadiusApplication: 'around',
                borderRadiusWhenStacked: 'last',
                columnWidth: '45%',
            }
        },
        fill: {
            type: 'gradient',
            gradient: {
                shade: 'dark',
                gradientToColors: ['#009efd'],
                shadeIntensity: 1,
                type: 'vertical',
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100, 100, 100]
            },
        },
        colors: ["#2af598"],
        grid: {
            show: true,
            borderColor: 'rgba(255, 255, 255, 0.1)',
        },
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
        },
        tooltip: {
            theme: "dark",
            marker: {
                show: !1
            }
        },
    };

    var chart = new ApexCharts(document.querySelector("#chart5"), options);
    chart.render();

    */


/** 
    // chart 6
    document.addEventListener("DOMContentLoaded", function () {
        fetch('/TROS%20NEW/staff%26adminPage/backend/graph/get_service_counts.php')
            .then(response => {
                if (!response.ok) throw new Error(`❌ API Request Failed: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log("Fetched Data:", data); // Debugging
    
                if (!data || typeof data.plumbing === "undefined") {
                    throw new Error("❌ Invalid API Response");
                }
    
                var options = {
                    series: [data.plumbing, data.renovation, data.electrical], // Use fetched counts
                    chart: { height: 290, type: 'donut' },
                    legend: { position: 'bottom', show: false },
                    fill: { type: 'gradient' },
                    colors: ["#ff6a00", "#98ec2d", "#3494e6"],
                    dataLabels: { enabled: false },
                    plotOptions: { pie: { donut: { size: "85%" } } }
                };
    
                var chart = new ApexCharts(document.querySelector("#chart6"), options);
                chart.render();
            })
            .catch(error => console.error("🚨 Error fetching data:", error));
    });
**/
    
    



 // chart 7
 var options = {
    series: [{
        name: "Total Accounts",
        data: [4, 10, 25, 12, 25, 18, 40, 22, 7]
    }],
    chart: {
        //width:150,
        height: 105,
        type: 'area',
        sparkline: {
            enabled: !0
        },
        zoom: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: 3,
        curve: 'smooth'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            gradientToColors: ['#fc185a'],
            shadeIntensity: 1,
            type: 'vertical',
            opacityFrom: 0.8,
            opacityTo: 0.2,
            //stops: [0, 100, 100, 100]
        },
    },

    colors: ["#ffc107"],
    tooltip: {
        theme: "dark",
        fixed: {
            enabled: !1
        },
        x: {
            show: !1
        },
        y: {
            title: {
                formatter: function (e) {
                    return ""
                }
            }
        },
        marker: {
            show: !1
        }
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    }
};

var chart = new ApexCharts(document.querySelector("#chart7"), options);
chart.render();



 // chart 8

 var options = {
    series: [{
        name: "Total Sales",
        data: [4, 10, 25, 12, 25, 18, 40, 22, 7]
    }],
    chart: {
        //width:150,
        height: 210,
        type: 'area',
        sparkline: {
            enabled: !0
        },
        zoom: {
            enabled: false
        }
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        width: 3,
        curve: 'straight'
    },
    fill: {
        type: 'gradient',
        gradient: {
            shade: 'dark',
            gradientToColors: ['#17ad37'],
            shadeIntensity: 1,
            type: 'vertical',
            opacityFrom: 0.7,
            opacityTo: 0.0,
            //stops: [0, 100, 100, 100]
        },
    },
    colors: ["#98ec2d"],
    tooltip: {
        theme: "dark",
        fixed: {
            enabled: !1
        },
        x: {
            show: !1
        },
        y: {
            title: {
                formatter: function (e) {
                    return ""
                }
            }
        },
        marker: {
            show: !1
        }
    },
    markers: {
        show: !1,
        size: 5,
    },
    xaxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
    }
};

var chart = new ApexCharts(document.querySelector("#chart8"), options);
chart.render();






});
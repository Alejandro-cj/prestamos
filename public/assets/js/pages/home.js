const year = document.querySelector('#year');
let myChart;
document.addEventListener("DOMContentLoaded", function () {
  movimientoGrafico(year.value);

  year.addEventListener('change', function(e){
    movimientoGrafico(e.target.value);
  })
});

function movimientoGrafico(anio) {
  if (myChart) {
    myChart.destroy();
  }
  const url = base_url + "prestamosMes/" + anio;
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      var options = {
        chart: {
          height: 350,
          type: "line",
          shadow: {
            enabled: true,
            color: "#000",
            top: 18,
            left: 7,
            blur: 10,
            opacity: 1,
          },
          toolbar: {
            show: false,
          },
        },
        colors: ["#77B6EA", "#545454"],
        dataLabels: {
          enabled: true,
        },
        stroke: {
          curve: "smooth",
        },
        series: [
          {
            name: "Total",
            data: [res.total.ene, res.total.feb, res.total.mar, res.total.abr, res.total.may, 
              res.total.jun, res.total.jul, res.total.ago, res.total.sep, res.total.oct, res.total.nov, res.total.dic],
          }
        ],
        title: {
          text: "Prestamos por mes",
          align: "left",
        },
        grid: {
          borderColor: "#e7e7e7",
          row: {
            colors: ["#f3f3f3", "transparent"], // takes an array which will be repeated on columns
            opacity: 0.5,
          },
        },
        markers: {
          size: 6,
        },
        xaxis: {
          categories: [
            "Ene",
            "Feb",
            "Mar",
            "Abr",
            "May",
            "Jun",
            "Jul",
            "Ago",
            "Sep",
            "Oct",
            "Nov",
            "Dic",
          ],
          title: {
            text: "Meses",
          },
          labels: {
            style: {
              colors: "#9aa0ac",
            },
          },
        },
        yaxis: {
          title: {
            text: "Importe",
          },
          labels: {
            style: {
              color: "#9aa0ac",
            },
          },
          min: 5,
          max: parseFloat(res.max.importe),
        },
        legend: {
          position: "top",
          horizontalAlign: "right",
          floating: true,
          offsetY: -25,
          offsetX: -5,
        },
      };

      myChart = new ApexCharts(document.querySelector("#prestamos"), options);

      myChart.render();
    }
  };
}

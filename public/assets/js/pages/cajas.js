document.addEventListener('DOMContentLoaded', function(){
  movimientoGrafico();
})

function movimientoGrafico() {
  const url = base_url + "cajas/movimientos";
  const http = new XMLHttpRequest();
  http.open("GET", url, true);
  http.send();
  http.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      const res = JSON.parse(this.responseText);
      var ctx = document.getElementById("movimiento").getContext("2d");
      var myChart = new Chart(ctx, {
        type: "pie",
        data: {
          datasets: [
            {
              data: [
                res.inicial,
                res.ingreso,
                res.egreso,
                res.saldo,
              ],
              backgroundColor: [
                "#191d21",
                "#63ed7a",
                "#ffa426",
                "#fc544b"
              ],
              label: "Movimientos",
            },
          ],
          labels: [
            "Monto inicial: " + res.decimales.inicial,
            "Ingresos: " + res.decimales.ingreso,
            "Egresos: " + res.decimales.egreso,
            "Saldo: " + res.decimales.saldo,
          ],
        },
        options: {
          responsive: true,
          legend: {
            position: "bottom",
          },
        },
      });
    }
  };
}

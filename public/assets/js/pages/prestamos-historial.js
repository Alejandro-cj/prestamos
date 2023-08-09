let tblPrestamos;
const fecha_actual = document.querySelector("#fecha_actual");
document.addEventListener("DOMContentLoaded", function () {
  tblPrestamos = $("#tblPrestamos").DataTable({
    ajax: {
      url: base_url + "prestamos/listHistorial",
      dataSrc: "",
    },
    columns: [
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<a class="btn btn-primary" href="${
              base_url + "prestamos/" + data.id + "/detail"
            }"><i class="fas fa-eye"></i></a>
                        <form action="${
                          base_url + "prestamos/" + data.id
                        }" method="post" class="d-inline eliminar">
                            <input type="hidden" name="${csrf_token.getAttribute(
                              "content"
                            )}" value="${csrf_hash.getAttribute(
              "content"
            )}" />    
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </form>`;
          }
          return data;
        },
      },
      { data: "id" },
      //nombre y apellido
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<li class="list-group-item">${
              data.identidad + ": " + data.num_identidad
            }</li>
                        <li class="list-group-item">${
                          data.nombre + " " + data.apellido
                        }</li>
                        <li class="list-group-item">Fecha: ${data.fecha}</li>
                        <li class="list-group-item">Tasa interes: ${
                          data.tasa_interes
                        }</li>
                        <li class="list-group-item">Cuotas: ${
                          data.cuotas
                        }</li>`;
          }
          return data;
        },
      },
      { data: "importe" },
      { data: "modalidad" },
      { data: "vencimiento" },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            if (parseFloat(data.gd) > 0) {
              return `<span class="badge bg-success">${data.ganancia}</span>`;
            } else {
              return `<span class="badge bg-danger">${data.ganancia}</span>`;
            }
          }
          return data;
        },
      },
      { data: "usuario" },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            if(data.estado == 1){
              return `<span class="badge bg-success">Activo</span>`;
            }else{
              return `<span class="badge bg-success">
              <i class="fas fa-check-circle"></i> Completado</span>`;
            }
            
          }
          return data;
        },
      },
    ],
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
    dom,
    buttons,
    createdRow: (row, data, index) => {
      if (data.fecha_venc < fecha_actual.value) {
        document.querySelector("td ");
        $("td", row).css({
          "background-color": "#FC5649",
        });
      } else if (data.fecha_venc == fecha_actual.value) {
        $("td", row).css({
          "background-color": "#FCD349",
        });
      }
    },
    order: [[1, "desc"]],
  });

  tblPrestamos.on("draw", function () {
    let lista = document.querySelectorAll(".eliminar");
    for (let i = 0; i < lista.length; i++) {
      lista[i].addEventListener("submit", function (e) {
        e.preventDefault();
        eliminarRegistro(this);
      });
    }
  });
});

function eliminarRegistro(form) {
  Swal.fire({
    title: "Mensaje?",
    text: "Esta seguro de eliminar!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Eliminar!",
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });
}

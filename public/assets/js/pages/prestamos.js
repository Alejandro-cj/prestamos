const id_cliente = document.querySelector('#id_cliente');
const importe_credito = document.querySelector('#importe_credito');
const tasa_interes = document.querySelector('#tasa_interes');
const cuotas = document.querySelector('#cuotas');
const importe_cuota = document.querySelector('#importe_cuota');
const total_pagar = document.querySelector('#total_pagar');
const interes_generado = document.querySelector('#interes_generado');
const errorCliente = document.querySelector('#errorCliente');
document.addEventListener('DOMContentLoaded', function(){
    $("#cliente").autocomplete({
        source: function( request, response ) {
          $.ajax( {
            url: base_url + 'prestamos/buscarCliente',
            dataType: "json",
            data: {
              term: request.term
            },
            success: function( data ) {
              response( data );
              if (data.length > 0) {
                errorCliente.textContent = '';
              } else {
                errorCliente.textContent = 'NO EXISTE EL CLIENTE';
              }
            }
          } );
        },
        minLength: 2,
        select: function( event, ui ) {
            id_cliente.value = ui.item.id;
            console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
      } );

      //calcular importe
      importe_credito.addEventListener('keyup', function(e){
        if (e.target.value != '') {
            //validacion de interes
            let interes = (tasa_interes.value == '' || tasa_interes.value < 1)
             ? 0 : tasa_interes.value;
            //validacion de cuotas
            let cuotas_total = (cuotas.value == '' || cuotas.value < 0) 
            ? 0 : cuotas.value;
            calcularTotal(e.target.value, cuotas_total, interes);
        } else {
            limpiarCampos()
        }
      })

      // calcular cuotas
      cuotas.addEventListener('change', function(e){
        if (e.target.value != '') {
            //validacion de interes
            let interes = (tasa_interes.value == '' || tasa_interes.value < 1)
             ? 0 : tasa_interes.value;
            //validacion de importe
            let importe = (importe_credito.value == '' || importe_credito.value < 0) 
            ? 0 : importe_credito.value;
            calcularTotal(importe, e.target.value, interes);
        } else {
            limpiarCampos()
        }
      })

      // calcular interes
      tasa_interes.addEventListener('keyup', function(e){
        if (e.target.value != '') {
            //validacion de cuotas
            let cuotas_total = (cuotas.value == '' || cuotas.value < 0) 
            ? 0 : cuotas.value;
            //validacion de importe
            let importe = (importe_credito.value == '' || importe_credito.value < 0) 
            ? 0 : importe_credito.value;
            calcularTotal(importe, cuotas_total, e.target.value);
        } else {
            limpiarCampos();
        }
      })
})

function calcularTotal(importe, cuotas, interes) {
    let ganacia = parseFloat(importe) * (parseInt(interes) / 100);
    //calcular importe por cuotas
    let importeCuota = 0;
    if (cuotas > 0) {
        importeCuota = (parseFloat(importe) / parseInt(cuotas)) + (parseFloat(ganacia) / parseInt(cuotas));
    }
    //asignar el value en el input
    importe_cuota.value = importeCuota.toFixed(2);
    interes_generado.value = ganacia.toFixed(2);

    const totalPagar = parseFloat(importe_cuota.value) * parseInt(cuotas);
    total_pagar.value = totalPagar.toFixed(2);
}

function limpiarCampos() {
    importe_cuota.value = '0.00';
    total_pagar.value = '0.00';
    interes_generado.value = '0.00';
}
import $ from 'jquery';
import select2 from 'select2';
select2($);

document.addEventListener("DOMContentLoaded",  (event) => {
    $('#idUsuario').select2({
    });
})

const tipoCertificado = document.querySelector('#tipoCertificado')
tipoCertificado.addEventListener('change', (event) => {

    const fechas = document.querySelector('#fechas')

    if(event.target.value == 2){
        fechas.classList.remove('hidden')
    }
    else{
        fechas.classList.add('hidden')
    }
})

const form_generar_certificado_asistencia = document.querySelector('#form_generar_certificado_asistencia')
form_generar_certificado_asistencia.addEventListener('formdata', (event) => {
    const idUsuarioSelected = document.querySelector('#idUsuario').value 
    const formData = event.formData; 
    formData.set("idUsuario", idUsuarioSelected);  
})
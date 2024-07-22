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

const form_generar_certificado = document.querySelector('#form_generar_certificado')
form_generar_certificado.addEventListener('submit', function (event) {

    let validar=false

    event.preventDefault()

    const representacionesLength = document.querySelectorAll('input[name="representaciones[]"]:checked').length
    if(representacionesLength>0){
        validar=true
    }
    else{
        validar=false
    }

    const tipoCertificado = document.querySelector('#tipocertificado').value
    const fechaDesde = document.querySelector('#fechaDesde').value
    const fechaHasta = document.querySelector('#fechaHasta').value

    if(tipoCertificado==2)
        if(fechaDesde!='' && fechaHasta!=''){
            const fechaDesdeSplit = fechaDesde.split("/");
            const dateDesde = new Date(parseInt(fechaDesdeSplit[2]),parseInt(fechaDesdeSplit[1]-1),parseInt(fechaDesdeSplit[0])); 

            const fechaHastaSplit = fechaHasta.split("/");
            const dateHasta = new Date(parseInt(fechaHastaSplit[2]),parseInt(fechaHastaSplit[1]-1),parseInt(fechaHastaSplit[0]));

            if(dateHasta<dateDesde){
                validar=true
            }
            else{
                validar=false
            }
        }
        else if(fechaDesde=='' && fechaHasta==''){
            validar=true
        }
        else{
            validar=false
        }

    if(validar==true){
        form_generar_certificado.target = "_blank";
    }
    else{
        form_generar_certificado.target = "";
    }

    form_generar_certificado.submit();
})



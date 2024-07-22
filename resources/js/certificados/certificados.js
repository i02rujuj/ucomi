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

import { ASISTIR_CONVOCATORIA_BBDD } from "./axiosTemplate.js";
import Swal from 'sweetalert2';

const confirmarAsistenciaButtons = document.querySelectorAll('#btn-confirmarAsistencia');

confirmarAsistenciaButtons.forEach(button => {
    button.addEventListener('click', async (event) => {
        event.stopPropagation()
        
        try {
            const asistir = await Swal.fire({
                title:'Confirmar asistencia',
                showDenyButton: true,
                showConfirmButton: true,
                denyButtonText: 'No asistiré',
                confirmButtonText: "Asistiré",
                confirmButtonColor: '#3085d6',
                denyButtonColor: '#d33', 
            });

            let confirmado = 0
            let text, icon, title = ''

            if(asistir.isConfirmed){
                confirmado=1
                text = 'Se ha confirmado tu asistencia a la convocatoria'
                icon = "success"
                title = 'Asistiré'
            }

            if(asistir.isDenied){
                confirmado=0
                text= 'Se ha indicado que no asistirás a la convocatoria'
                icon = "error"
                title = 'No Asistiré'
            }

            if(!asistir.isDismissed){
                const dataToSend = {
                    idConvocatoria:button.dataset.convocatoriaId,
                    asiste:confirmado
                }

                const response = await ASISTIR_CONVOCATORIA_BBDD(dataToSend)

                if (response.status === 200) {
                    await Swal.fire({
                        icon: icon,
                        title: title,
                        text: text,
                    })

                    button.classList.remove('text-green-400','text-red-400')
                    
                    if(confirmado){
                        button.classList.add('text-green-400')
                    }
                    else{
                        button.classList.add('text-red-400')
                    }
                } 
                else {
                    await Swal.fire({
                        icon: "error",
                        title: 'Confirmar asistencia',
                        text: 'Se ha producido un error al confirmar tu asistencia',
                    })
                }
            }

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación al confirmar/denegar la asistencia.",
            });
        }

    }, true)
})
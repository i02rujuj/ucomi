import { ASISTIR_CONVOCATORIA_BBDD } from "./axiosTemplate.js";
import Swal from 'sweetalert2';

const confirmarAsistenciaButtons = document.querySelectorAll('#btn-confirmarAsistencia');

confirmarAsistenciaButtons.forEach(button => {
    button.addEventListener('click', async (event) => {
        event.stopPropagation()
        
        try {
            const asistir = await Swal.fire({
                title:'¿Asistirás a la convocatoria?',
                showDenyButton: true,
                showConfirmButton: true,
                denyButtonText: 'Cancelar asistencia',
                confirmButtonText: "Confirmar asistencia",
                confirmButtonColor: '#3085d6',
                denyButtonColor: '#d33', 
                toast: true,
                position: 'top-right',
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
                    window.location.reload()
                } 
                else {
                    await Swal.fire({
                        icon: "error",
                        title: 'Confirmar asistencia',
                        text: 'Se ha producido un error al confirmar tu asistencia',
                        toast: true,
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        position: 'top-right',
                    })
                }
            }

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación al confirmar/denegar la asistencia.",
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-right',
            });
        }

    }, true)
})
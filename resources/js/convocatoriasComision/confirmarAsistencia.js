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

                switch (response.status) {
                    case 200:
                        localStorage.setItem("notification", JSON.stringify(notification(response.message, 'success')));
                        window.location.reload()   
                        break;
                
                    case 422:
                        Swal.showValidationMessage(response.errors)
                        return false
                    break;
            
                    case 500:
                        localStorage.setItem("notification", JSON.stringify(notification(response.errors, 'error')));
                        window.location.reload()   
                    break;
                
                    default:
                        Swal.showValidationMessage(response.errors)
                        return false
                        break;
                }
            }

        } catch (error) {
            await Swal.fire(notification("Ha ocurrido un error al realizar una operación al confirmar/denegar la asistencia.", 'error'))
        }

    }, true)
})
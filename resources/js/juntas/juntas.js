import { DELETE_JUNTA_BBDD, UPDATE_JUNTA_BBDD, ADD_JUNTA_BBDD, VALIDATE_JUNTA_BBDD } from "./axiosTemplate.js";
import Swal from 'sweetalert2';

let modal_add = null

document.addEventListener("DOMContentLoaded", async (event) => {
    modal_add = document.querySelector('#modal_add')
})

function renderHTMLJunta(response){

    modal_add.querySelector('#idCentro').value=""
    modal_add.querySelector('#fechaConstitucion').value=""
    modal_add.querySelector('#fechaDisolucion').value=""

    if(response){
        let modal_edit = modal_add.cloneNode(true);

        modal_edit.classList.remove('hidden')
        
        modal_edit.querySelector('#idCentro').value=response.centro.id
        modal_edit.querySelector('#idCentro').setAttribute('disabled', 'disabled')
        modal_edit.querySelector('#idCentro').classList.add('bg-red-50')
        modal_edit.querySelector('#fechaConstitucion').value=response.fechaConstitucion
        modal_edit.querySelector('#fechaDisolucion').value=response.fechaDisolucion

        if(response.deleted_at!=null){
            modal_edit.querySelector('#idCentro').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#fechaConstitucion').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#fechaDisolucion').setAttribute('disabled', 'disabled')
        }
        return modal_edit        
    }
    else{
        modal_add.classList.remove('hidden')
        return modal_add
    }
}

const preConfirm = async(accion, id=null) => {
    let valores = {};

    const inputs = document.querySelectorAll(".junta");
    inputs.forEach((input) => {
        valores[input.id] = input.value;
    });

    if(!valores['fechaDisolucion']){
        valores['fechaDisolucion']=null;
    }

    let dataToSend, response, title, text = null

    switch (accion) {
        case 'add':
            dataToSend = {
                data: valores,
            };
            response = await ADD_JUNTA_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido la junta"
            break;
    
        case 'update':

            dataToSend = {
                id: id,
                accion: 'update',
                data: valores,
            };

            response = await VALIDATE_JUNTA_BBDD(dataToSend)

            if (response.status === 200) {
                let confirmarCesarMiembros = false

                // Avisar sobre poner fecha Cese como fecha disolución de la junta
                if(valores['fechaDisolucion']!=null){
                    const result2 = await Swal.fire({
                        text: "Se ha indicado una fecha de disolución para la junta. Todos los miembros de la junta cesarán con la fecha de disolución indicada",
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar",
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '',
                        toast: true,
                        timerProgressBar: true,
                        showConfirmButton: true,
                        position: 'top-right',
                        showLoaderOnConfirm:true,
                        preConfirm: async () => {confirmarCesarMiembros = true},
                    })

                    if(result2.isDismissed){return false}
                }
    
                if(valores['fechaDisolucion']==null || confirmarCesarMiembros){
        
                    response = await UPDATE_JUNTA_BBDD(dataToSend);
                    title="Actualizado"
                    text="Se ha actualizado la junta"
                    confirmarCesarMiembros ? text+=' y se han cesado a todos los miembros de la junta.' : ''
                }
            }
            break

        case 'delete':

            dataToSend = {
                id: id,
                accion: 'delete',
            }

            response = await VALIDATE_JUNTA_BBDD(dataToSend);

            if (response.status === 200) {
                const result = await Swal.fire({
                    title: `¿Seguro que quiere eliminar la junta?`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "",
                    confirmButtonText: "Eliminar",
                    toast: true,
                    timerProgressBar: true,
                    showConfirmButton: true,
                    position: 'top-right',
                    showLoaderOnConfirm:true,
                    preConfirm: async () => {        
                        response = await DELETE_JUNTA_BBDD(dataToSend);
                        title="Eliminado"
                        text="Se ha eliminado la junta"
                    }
                })

                if(result.isDismissed){return false}     
            }
            break
    }

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

/**
 * EVENTO AÑADIR
 */
const addButton = document.querySelector('#btn-add-junta');
if(addButton){
    addButton.addEventListener("click", async (event) => {
        await Swal.fire({
            title: "Añadir Junta",
            html: renderHTMLJunta(null),
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: "Añadir",
            cancelButtonText: "Cancelar",
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showLoaderOnConfirm:true,
            preConfirm: async () => preConfirm('add')
        })
    })
}

// EVENTO EDITAR Y ELIMINAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        try {
            let response = null

            for(let j in juntas.data){
                if(juntas.data[j].id==button.dataset.juntaId){
                    response = juntas.data[j]
                    break;
                }
            }
            if(!response)
                throw "Error, junta no encontrada"

            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Junta eliminada' : 'Editar Junta',
                html: renderHTMLJunta(response),
                focusConfirm: false,
                showDenyButton: (response && response.deleted_at!=null) || !permitirAcciones ? false : true,
                showCancelButton: response && response.deleted_at!=null ? false : true,
                showConfirmButton: response && response.deleted_at!=null ? false : true,
                denyButtonText: 'Eliminar',
                confirmButtonText: "Actualizar",
                cancelButtonText: "Cancelar",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '',
                denyButtonColor: '#d33',
                showLoaderOnConfirm:true,
                showLoaderOnDeny:true,
                preConfirm: async () => preConfirm('update', button.dataset.juntaId),
                preDeny: permitirAcciones ? async () => preConfirm('delete', button.dataset.juntaId) : null,
            });

        } catch (error) {
            await Swal.fire(notification("Ha ocurrido un error al realizar una operación con la junta.", 'error'))
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-junta');

editButtons.forEach(button => {
    addEditEvent(button);
});

const verMiembros = document.querySelectorAll('#btn-ver-miembros')
verMiembros.forEach(button => {
    button.addEventListener('click', async (event) => {
        event.stopPropagation()
    }, true)
})
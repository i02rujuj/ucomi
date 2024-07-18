import { DELETE_CONVOCATORIA_BBDD, UPDATE_CONVOCATORIA_BBDD, ADD_CONVOCATORIA_BBDD, VALIDATE_CONVOCATORIA_BBDD } from "./axiosTemplate.js";
import Swal from 'sweetalert2';

let modal_add = null

document.addEventListener("DOMContentLoaded", async (event) => {
    modal_add = document.querySelector('#modal_add')
})

function renderHTMLConvocatoria(response){

    modal_add.querySelector('#idComision').value=""
    modal_add.querySelector('#idTipo').value=""
    modal_add.querySelector('#lugar').value=""
    modal_add.querySelector('#fecha').value=""
    modal_add.querySelector('#hora').value=""
    modal_add.querySelector('#acta').value=""

    if(response){
        let modal_edit = modal_add.cloneNode(true);

        modal_edit.classList.remove('hidden')
        
        modal_edit.querySelector('#idComision').value=response.idComision
        modal_edit.querySelector('#idComision').setAttribute('disabled', 'disabled')
        modal_edit.querySelector('#idComision').classList.add('bg-red-50')
        modal_edit.querySelector('#idTipo').value=response.idTipo
        modal_edit.querySelector('#lugar').value=response.lugar
        modal_edit.querySelector('#fecha').value=response.fecha
        modal_edit.querySelector('#hora').value=response.hora

        if(response.deleted_at!=null){
            modal_edit.querySelector('#idTipo').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#lugar').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#fecha').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#hora').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#acta').setAttribute('disabled', 'disabled')
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

    const inputs = document.querySelectorAll(".convocatoria");
    inputs.forEach((input) => {
        if(input.id!='acta'){
            valores[input.id] = input.value;
        }
        else{
            valores[input.id] = input.files[0]
        }
    });

    let dataToSend, response, title, text = null

    switch (accion) {
        case 'add':
            dataToSend = {
                data: valores,
            };
            response = await ADD_CONVOCATORIA_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido la convocatoria"
            break;
    
        case 'update':

            dataToSend = {
                id: id,
                accion: 'update',
                data: valores,
            };

            response = await VALIDATE_CONVOCATORIA_BBDD(dataToSend)

            if (response.status === 200) {
                response = await UPDATE_CONVOCATORIA_BBDD(dataToSend);
                title="Actualizado"
                text="Se ha actualizado la convocatoria"
            }
            break

        case 'delete':

            dataToSend = {
                id: id,
                accion: 'delete',
            }

            const result = await Swal.fire({
                title: "¿Seguro que quiere eliminar la convocatoria?",
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
                    response = await DELETE_CONVOCATORIA_BBDD(dataToSend);
                    title="Eliminado"
                    text="Se ha eliminado la convocatoria"
                }
            })

            if(result.isDismissed){return false}
            break  
    }

    if (response.status === 200) {
        window.location.reload()
    } 
    else {
        Swal.showValidationMessage(response.errors)
        return false
    }

}

/**
 * EVENTO AÑADIR
 */
const addButton = document.querySelector('#btn-add-convocatoria');
if(addButton){
    addButton.addEventListener("click", async (event) => {
        await Swal.fire({
            title: "Añadir Convocatoria",
            html: renderHTMLConvocatoria(null),
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

            for(let c in convocatorias.data){
                if(convocatorias.data[c].id==button.dataset.convocatoriaId){
                    response = convocatorias.data[c]
                    break;
                }
            }
            if(!response)
                throw "Error, convocatoria no encontrada"

            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Convocatoria eliminada' : 'Editar Convocatoria',
                html: renderHTMLConvocatoria(response),
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
                preConfirm: async () => preConfirm('update', button.dataset.convocatoriaId),
                preDeny: permitirAcciones ? async () => preConfirm('delete', button.dataset.convocatoriaId) : null,
            });

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con la convocatoria.",
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-right',
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-convocatoria');

editButtons.forEach(button => {
    addEditEvent(button);
});
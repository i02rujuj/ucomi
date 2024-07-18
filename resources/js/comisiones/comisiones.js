import { DELETE_COMISION_BBDD, UPDATE_COMISION_BBDD, ADD_COMISION_BBDD, VALIDATE_COMISION_BBDD } from "./axiosTemplate.js";
import Swal from 'sweetalert2';

let modal_add = null

document.addEventListener("DOMContentLoaded", async (event) => {
    modal_add = document.querySelector('#modal_add')
})

function renderHTMLComision(response){

    modal_add.querySelector('#nombre').value=""
    modal_add.querySelector('#descripcion').value=""
    modal_add.querySelector('#idJunta').value=""
    modal_add.querySelector('#fechaConstitucion').value=""
    modal_add.querySelector('#fechaDisolucion').value=""

    if(response){
        let modal_edit = modal_add.cloneNode(true);

        modal_edit.classList.remove('hidden')
        
        modal_edit.querySelector('#nombre').value=response.nombre
        modal_edit.querySelector('#descripcion').value=response.descripcion
        modal_edit.querySelector('#idJunta').value=response.idJunta
        modal_edit.querySelector('#idJunta').setAttribute('disabled', 'disabled')
        modal_edit.querySelector('#idJunta').classList.add('bg-red-50')
        modal_edit.querySelector('#fechaConstitucion').value=response.fechaConstitucion
        modal_edit.querySelector('#fechaDisolucion').value=response.fechaDisolucion

        if(response.deleted_at!=null){
            modal_edit.querySelector('#nombre').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#descripcion').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#idJunta').setAttribute('disabled', 'disabled')
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

    const inputs = document.querySelectorAll(".comision");
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
            response = await ADD_COMISION_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido la comisión"
            break;
    
        case 'update':

            dataToSend = {
                id: id,
                accion: 'update',
                data: valores,
            };

            response = await VALIDATE_COMISION_BBDD(dataToSend)

            if (response.status === 200) {
                let confirmarCesarMiembros = false

                // Avisar sobre poner fecha Cese como fecha disolución de la comisión
                if(valores['fechaDisolucion']!=null){
                    const result2 = await Swal.fire({
                        text: "Se ha indicado una fecha de disolución para la comisión. Todos los miembros de la comisión cesarán con la fecha de disolución indicada",
                        focusConfirm: false,
                        showCancelButton: true,
                        confirmButtonText: "Aceptar",
                        cancelButtonText: "Cancelar",
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '',
                        showLoaderOnConfirm:true,
                        preConfirm: async () => {confirmarCesarMiembros = true},
                    })

                    if(result2.isDismissed){return false}
                }
    
                if(valores['fechaDisolucion']==null || confirmarCesarMiembros){
        
                    response = await UPDATE_COMISION_BBDD(dataToSend);
                    title="Actualizado"
                    text="Se ha actualizado la comisión"
                    confirmarCesarMiembros ? text+=' y se han cesado a todos los miembros de la comisión.' : ''
                }
            }
            break

        case 'delete':

            dataToSend = {
                id: id,
                accion: 'delete',
            }

            response = await VALIDATE_COMISION_BBDD(dataToSend);

            if (response.status === 200) {
                const result = await Swal.fire({
                    title: `¿Seguro que quiere eliminar la comisión '${valores.nombre}'?`,
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
                        response = await DELETE_COMISION_BBDD(dataToSend);
                        title="Eliminado"
                        text="Se ha eliminado la comisión"
                    }
                })

                if(result.isDismissed){return false}     
            }
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
const addButton = document.querySelector('#btn-add-comision');
if(addButton){
    addButton.addEventListener("click", async (event) => {
        await Swal.fire({
            title: "Añadir Comisión",
            html: renderHTMLComision(null),
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

            for(let c in comisiones.data){
                if(comisiones.data[c].id==button.dataset.comisionId){
                    response = comisiones.data[c]
                    break;
                }
            }
            if(!response)
                throw "Error, comisión no encontrada"

            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Comisión eliminada' : 'Editar Comisión',
                html: renderHTMLComision(response),
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
                preConfirm: async () => preConfirm('update', button.dataset.comisionId),
                preDeny: permitirAcciones ? async () => preConfirm('delete', button.dataset.comisionId) : null,
            });

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con la comisión.",
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-right',
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-comision');

editButtons.forEach(button => {
    addEditEvent(button);
});
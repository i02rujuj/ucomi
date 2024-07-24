import { DELETE_MIEMBROSCOMISION_BBDD, UPDATE_MIEMBROSCOMISION_BBDD, ADD_MIEMBROSCOMISION_BBDD, VALIDATE_MIEMBROSCOMISION_BBDD } from "./axiosTemplate.js";

import $ from 'jquery';
import select2 from 'select2';
//Hook up select2 to jQuery
select2($);
import Swal from 'sweetalert2';

let modal_add = null

document.addEventListener("DOMContentLoaded",  (event) => {
    $('#idUsuario').select2({
        placeholder: 'Selecciona un usuario',
        dropdownParent: $('#modal_add'),
        allowClear: true,
    });

    $('#cargo').select2({
        placeholder: 'Crea o selecciona un cargo',
        dropdownParent: $('#modal_add'),
        tags: true,
        allowClear: true,
    });
      
    modal_add = document.querySelector('#modal_add')
})

function renderHTMLMiembro(response){

    $(modal_add).find("#idUsuario").val('').trigger('change');
    $(modal_add).find("#idUsuario").prop("disabled", false);
    modal_add.querySelector('#idComision').value=""
    modal_add.querySelector('#idComision').removeAttribute('disabled')
    modal_add.querySelector('#idRepresentacion').value=""
    $(modal_add).find("#cargo").find('option:not(:last)').remove().trigger('change');
    $(modal_add).find("#cargo").val('').trigger('change');
    modal_add.querySelector('#fechaTomaPosesion').value=""
    modal_add.querySelector('#fechaCese').value=""
    modal_add.querySelector('#responsable').value=0

    if(response){
        $(modal_add).find("#idUsuario").val(response.usuario.id).trigger('change');
        $(modal_add).find("#idUsuario").prop("disabled", true);

        modal_add.querySelector('#idComision').value=response.comision.id
        modal_add.querySelector('#idComision').setAttribute('disabled', 'disabled')
        modal_add.querySelector('#idRepresentacion').value=response.idRepresentacion

        if(response.cargo!= null){
            if (!$(modal_add).find("#cargo").find("option[value='" + response.cargo + "']").length) { 
                let newOption = new Option(response.cargo, response.cargo, true, true);
                $( newOption ).prependTo(modal_add.querySelector('#cargo')).trigger('change')
            } 
        }

        $(modal_add).find("#cargo").val(response.cargo).trigger('change');

        modal_add.querySelector('#fechaTomaPosesion').value=response.fechaTomaPosesion
        modal_add.querySelector('#fechaCese').value=response.fechaCese
        modal_add.querySelector('#responsable').value=response.responsable

        if(response.deleted_at!=null){
            $(modal_add).find("#idUsuario").prop("disabled", true);
            modal_add.querySelector('#idRepresentacion').setAttribute('disabled', 'disabled')
            $(modal_add).find("#cargo").prop("disabled", true);
            modal_add.querySelector('#fechaTomaPosesion').setAttribute('disabled', 'disabled')
            modal_add.querySelector('#fechaCese').setAttribute('disabled', 'disabled')
            modal_add.querySelector('#responsable').setAttribute('disabled', 'disabled')
        }
       
    }

    modal_add.classList.remove('hidden')
    return modal_add
}

const preConfirm = async(accion, id=null) => {
    let valores = {};

    const inputs = document.querySelectorAll(".miembro");
    inputs.forEach((input) => {
        valores[input.id] = input.value;
    });

    if(!valores['fechaCese']){
        valores['fechaCese']=null;
    }

    let dataToSend, response, title, text = null

    switch (accion) {
        case 'add':
            dataToSend = {
                data: valores,
            };
            response = await ADD_MIEMBROSCOMISION_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido el miembro de comisión"
            break
    
        case 'update':

            dataToSend = {
                id: id,
                accion: 'update',
                data: valores,
            };

            response = await VALIDATE_MIEMBROSCOMISION_BBDD(dataToSend)

            if (response.status === 200) {
                response = await UPDATE_MIEMBROSCOMISION_BBDD(dataToSend);
                title="Actualizado"
                text="Se ha actualizado el miembro de comisión"
            }
            break

        case 'delete':

            const result = await Swal.fire({
                title: "Seguro que quiere eliminar el miembro de comisión?",
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
                    dataToSend = {
                        id: id,
                        accion: 'delete',
                    }
                    
                    response = await DELETE_MIEMBROSCOMISION_BBDD(dataToSend);
                    title="Eliminado"
                    text="Se ha eliminado el miembro de comisión"
                }
            })

            if(result.isDismissed){return false}     
            
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
const addButton = document.querySelector('#btn-add-miembro');
addButton.addEventListener("click", async (event) => {
    await Swal.fire({
        title: "Añadir Miembro",
        html: renderHTMLMiembro(null),
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

// EVENTO EDITAR Y ELIMINAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        try {
            let response = null

            for(let m in miembros.data){
                if(miembros.data[m].id==button.dataset.miembroId){
                    response = miembros.data[m]
                    break;
                }
            }
            if(!response)
                throw "Error, miembro no encontrado"
            
            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Miembro eliminado' : 'Editar Miembro',
                html: renderHTMLMiembro(response),
                focusConfirm: false,
                showDenyButton: response && response.deleted_at!=null ? false : true,
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
                preConfirm: async () => preConfirm('update', button.dataset.miembroId),
                preDeny: async () => preConfirm('delete', button.dataset.miembroId),
            });

        } catch (error) {
            await Swal.fire(notification("Ha ocurrido un error al realizar una operación con el miembro.", 'error'))
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-miembro');

editButtons.forEach(button => {
    addEditEvent(button);
});

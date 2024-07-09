import { DELETE_MIEMBROSGOBIERNO_BBDD, UPDATE_MIEMBROSGOBIERNO_BBDD, ADD_MIEMBROSGOBIERNO_BBDD, VALIDATE_MIEMBROSGOBIERNO_BBDD } from "./axiosTemplate.js";

import $ from 'jquery';
import select2 from 'select2';
//Hook up select2 to jQuery
select2($);
import Swal from 'sweetalert2';

let modal_add = null

document.addEventListener("DOMContentLoaded",  (event) => {
    $('#idUsuario').select2({
        placeholder: 'Selecciona un usuario',
        dropdownParent: $('#modal_add')
    });
   
    modal_add = document.querySelector('#modal_add')
})

function renderHTMLMiembro(response){

    if(response){
        let modal_add_clone = modal_add.cloneNode(true);

        modal_add_clone.classList.remove('hidden')
        modal_add_clone.querySelector('#user').innerHTML= `
            <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full justify-center items-center">
                <label for="usuario" class="block text-sm text-gray-600 w-36 text-right">Usuario: *</label>
                <input class="miembro" type="hidden" id="idUsuario" value="${response.usuario.id}"/>
                <input type="text" id="usuario" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.usuario.name}" disabled>
            </div>`
        modal_add_clone.querySelector('#idCentro').value=response.centro.id
        modal_add_clone.querySelector('#idCentro').setAttribute('disabled', 'disabled')
        modal_add_clone.querySelector('#idCentro').classList.add('bg-red-50')
        modal_add_clone.querySelector('#idRepresentacion').value=response.representacion.id
        modal_add_clone.querySelector('#fechaTomaPosesion').value=response.fechaTomaPosesion
        modal_add_clone.querySelector('#fechaCese').value=response.fechaCese
        modal_add_clone.querySelector('#responsable').value=((response.usuario.roles).find(rol=>{return rol.name === 'responsable_centro'})) ? 1 : 0

        if(response.deleted_at!=null){
            modal_add_clone.querySelector('#idRepresentacion').setAttribute('disabled', 'disabled')
            modal_add_clone.querySelector('#fechaTomaPosesion').setAttribute('disabled', 'disabled')
            modal_add_clone.querySelector('#fechaCese').setAttribute('disabled', 'disabled')
            modal_add_clone.querySelector('#responsable').setAttribute('disabled', 'disabled')
        }
        return modal_add_clone        
    }
    else{
        modal_add.classList.remove('hidden')
        return modal_add
    }
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
    let mostrar=true

    switch (accion) {
        case 'add':
            dataToSend = {
                data: valores,
            };
            response = await ADD_MIEMBROSGOBIERNO_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido el miembro de centro"
            break
    
        case 'update':

            dataToSend = {
                id: id,
                accion: 'update',
                data: valores,
            };

            response = await VALIDATE_MIEMBROSGOBIERNO_BBDD(dataToSend)

            if (response.status === 200) {
                response = await UPDATE_MIEMBROSGOBIERNO_BBDD(dataToSend);
                title="Actualizado"
                text="Se ha actualizado el miembro de centro"
            }
            break

        case 'delete':

            const result = await Swal.fire({
                title: "¿Eliminar el miembro de centro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "",
                confirmButtonText: "Eliminar",
                preConfirm: async () => {  
                    dataToSend = {
                        id: id,
                        accion: 'delete',
                    }
                    
                    response = await DELETE_MIEMBROSGOBIERNO_BBDD(dataToSend);
                    title="Eliminado"
                    text="Se ha eliminado el miembro de centro"
                }
            })

            if(result.isDismissed){mostrar=false}     
            
            break
    }

    if(mostrar){
        if (response.status === 200) {
            await Swal.fire({
                icon: "success",
                title: title,
                text: text,
            })
            window.location.reload()
        } 
        else {
            Swal.showValidationMessage(response.errors)
            return false
        }
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
                preConfirm: async () => preConfirm('update', button.dataset.miembroId),
                preDeny: async () => preConfirm('delete', button.dataset.miembroId),
            });

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con el miembro.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-miembro');

editButtons.forEach(button => {
    addEditEvent(button);
});


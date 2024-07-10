import { DELETE_CENTRO_BBDD, GET_CENTRO_BBDD, UPDATE_CENTRO_BBDD, ADD_CENTRO_BBDD } from "./axiosTemplate.js";
import { GET_TIPOSCENTRO_BBDD } from "../tiposCentro/axiosTemplate";
import Swal from 'sweetalert2';

let modal_add = null
let modal_edit = null

document.addEventListener("DOMContentLoaded", async (event) => {
    modal_add = document.querySelector('#modal_add')
})

function renderHTMLCentro(response){

    modal_add.querySelector('#nombre').value=""
    modal_add.querySelector('#direccion').value=""
    modal_add.querySelector('#idTipo').value=""
    modal_add.querySelector('#logo').value=""
    modal_add.querySelector('#img_logo').src=default_image


    if(response){
        modal_edit = modal_add.cloneNode(true);
        modal_edit.classList.remove('hidden')
        modal_edit.querySelector('#nombre').value=response.nombre
        modal_edit.querySelector('#direccion').value=response.direccion
        modal_edit.querySelector('#idTipo').value=response.idTipo
        modal_edit.querySelector('#img_logo').src=response.logo

        if(response.deleted_at!=null){
            modal_edit.querySelector('#nombre').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#direccion').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#idTipo').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#logo').setAttribute('disabled', 'disabled')
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

    const inputs = document.querySelectorAll(".centro");
    inputs.forEach((input) => {
        if(input.id!='logo'){
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
            response = await ADD_CENTRO_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido el centro"
            break;
    
        case 'update':
            dataToSend = {
                id: id,
                data: valores,
            };
            response = await UPDATE_CENTRO_BBDD(dataToSend)
            title="Actualizado"
            text="Se ha actualizado el centro"
            break;

        case 'delete':

            const result = await Swal.fire({
                title: "¿Eliminar el centro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "",
                confirmButtonText: "Eliminar",
            });

            if (result.isConfirmed) {

                dataToSend = {
                    id: id,
                };

                response = await DELETE_CENTRO_BBDD(dataToSend);
                title="Eliminado"
                text="Se ha eliminado el centro"
            }
            break;
    }

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
    }
}

/**
 * EVENTO AÑADIR
 */
const addButton = document.querySelector('#btn-add-centro');
addButton.addEventListener("click", async (event) => {

    await Swal.fire({
        title: "Añadir Centro",
        html: renderHTMLCentro(null),
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Añadir",
        cancelButtonText: "Cancelar",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        didRender: () => {
            modal_add.querySelector('#logo').addEventListener("change", async (event) => {
                if (event.srcElement.files[0]) {
                    modal_add.querySelector('#img_logo').src = URL.createObjectURL(event.srcElement.files[0])
                }  
            })
        },
        preConfirm: async () => preConfirm('add')
    })
})

// EVENTO EDITAR Y ELIMINAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        try {
            let response = null

            for(let c in centros.data){
                if(centros.data[c].id==button.dataset.centroId){
                    response = centros.data[c]
                    break
                }
            }

            if(!response)
                throw "Error, centro no encontrado"

            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Centro eliminado' : 'Editar Centro',
                html: renderHTMLCentro(response),
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
                didRender: () => {
                    modal_edit.querySelector('#logo').addEventListener("change", async (event) => {
                        if (event.srcElement.files[0]) {
                            modal_edit.querySelector('#img_logo').src = URL.createObjectURL(event.srcElement.files[0])
                        } 
                    })
                },
                preConfirm: async () => preConfirm('update', button.dataset.centroId),
                preDeny: async () => preConfirm('delete', button.dataset.centroId),
            });

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con el centro.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-centro');
editButtons.forEach(button => {
    addEditEvent(button);
});
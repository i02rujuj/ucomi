import { DELETE_JUNTA_BBDD, GET_JUNTA_BBDD, UPDATE_JUNTA_BBDD, ADD_JUNTA_BBDD, VALIDATE_JUNTA_BBDD } from "./axiosTemplate.js";
import {GETALL_CENTRO_BBDD} from '../centros/axiosTemplate';
import Swal from 'sweetalert2';

let centros = null

document.addEventListener("DOMContentLoaded", async (event) => {
    // Obtener centros
    centros = await GETALL_CENTRO_BBDD();
    var select = document.querySelector('#filtroCentro');
 
    // Rellenar select filtro filtroCentro
    const searchParams = new URLSearchParams(window.location.search);
    centros.forEach(tipo => {
        var option = document.createElement("option");
        option.text = tipo.nombre
        option.value = tipo.id
        if(tipo.id==searchParams.get('filtroCentro')){
            option.selected = "selected"
        }
        select.add(option);
    })
})

function renderHTMLJunta(response){

    let options ="";

    return  `
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-4 justify-center items-center">
            <label for="idCentro" class="block text-sm text-gray-600 w-36 pr-6 text-right">Centro asociado: *</label>
            <select id="idCentro" class="swal2-input junta text-sm text-gray-600 border w-60 px-2 py-1 rounded-md outline-none ${response && response.idCentro ? ' bg-red-50' : " bg-blue-50"}" ${response && response.idCentro ? ' disabled' : ""} ${response && response.deleted_at!=null ? ' disabled' : ""}>
                <option value="" selected disabled>Selecciona una junta</option>
                ${centros.forEach(centro => {            
                    options+='<option value="'+centro.id+'" ';
                    if(response && centro.id == response.idCentro) 
                        options+='selected';
                    options+='>'+centro.nombre+'</option>';                                               
                })}
                ${options}
            </select>
        </div>
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-1 justify-center items-center">
            <label for="fechaConstitucion" class="block text-sm text-gray-600 w-36 text-right">Fecha Constitución: *</label>
            <input type="date" id="fechaConstitucion" class="swal2-input junta text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" value="${response ? response.fechaConstitucion : ""}" ${response && response.deleted_at!=null ? ' disabled' : ""}>
        </div>
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-3 justify-center items-center">
            <label for="fechaDisolucion" class="block text-sm text-gray-600 w-36 text-right">Fecha Disolución:</label>
            <input type="date" id="fechaDisolucion" class="junta swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none" value="${response ? response.fechaDisolucion : ""}" ${response && response.deleted_at!=null ? ' disabled' : ""}>
        </div>     
    `
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
    let mostrar=true

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
                        preConfirm: async () => {confirmarCesarMiembros = true},
                    })

                    if(result2.isDismissed){mostrar=false}
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
                    title: "¿Eliminar la junta?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "",
                    confirmButtonText: "Eliminar",
                    preConfirm: async () => {        
                        response = await DELETE_JUNTA_BBDD(dataToSend);
                        title="Eliminado"
                        text="Se ha eliminado la junta"
                    }
                })

                if(result.isDismissed){mostrar=false}     
            }
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
const addButton = document.querySelector('#btn-add-junta');
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
        preConfirm: async () => preConfirm('add')
    })
})

// EVENTO EDITAR Y ELIMINAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        try {
            const dataToSend = {
                id: button.dataset.juntaId,
            };   

            // Obtenemos la junta a editar
            const response = await GET_JUNTA_BBDD(dataToSend)

            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Junta eliminada' : 'Editar Junta',
                html: renderHTMLJunta(response),
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
                preConfirm: async () => preConfirm('update', button.dataset.juntaId),
                preDeny: async () => preConfirm('delete', button.dataset.juntaId),
            });

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con la junta.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-junta');

editButtons.forEach(button => {
    addEditEvent(button);
});
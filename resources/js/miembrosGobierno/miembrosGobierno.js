import { DELETE_MIEMBROSGOBIERNO_BBDD, GET_MIEMBROSGOBIERNO_BBDD, UPDATE_MIEMBROSGOBIERNO_BBDD, ADD_MIEMBROSGOBIERNO_BBDD } from "./axiosTemplate.js";
import {GET_JUNTA_BBDD, GETALL_JUNTA_BBDD} from '../juntas/axiosTemplate';
import {GET_CENTRO_BBDD, GETALL_CENTRO_BBDD} from '../centros/axiosTemplate';
import {GET_USER_BBDD, GETALL_USER_BBDD} from '../users/axiosTemplate';
import {GET_REPRESENTACION_BBDD, GETALL_REPRESENTACION_BBDD} from '../representaciones/axiosTemplate';

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
    modal_add.classList.remove('hidden')
    return modal_add

    return  `
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
            <label for="centro" class="block text-sm text-gray-600 mb-1 w-36 pr-6">Centro asociado: *</label>
            ${!response ? `
                <select id="centro" class="swal2-input miembro text-sm text-gray-600 border w-60 px-2 py-1 rounded-md outline-none bg-blue-50" >
                    <option value="">-----</option>
                    ${options = ""}
                    ${centros.forEach(centro => {            
                        options+='<option value="'+centro.id+'" ';
                        options+='>'+centro.nombre+'</option>';                                               
                    })}
                    ${options}
                </select>`
                : `<input type="hidden" id="idCentro" value="${response.centro.id}"/>
                <input type="text" id="centro" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.centro.nombre}" ${response && response.centro.nombre ? 'disabled' : ""} ${response && response.deleted_at!=null ? 'disabled' : ""}>`
            }
        </div>

        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
            <label for="usuario" class="block text-sm text-gray-600 w-32">Usuario: *</label>
            ${!response ? `
                <select id="usuario" class="swal2-input miembro text-sm text-gray-600 border w-60 px-2 py-1 rounded-md outline-none bg-blue-50" >
                    <option value="">-----</option>
                    ${options = ""}
                    ${usuarios.forEach(user => {            
                        options+='<option value="'+user.id+'" ';
                        options+='>'+user.name+'</option>';                                               
                    })}
                    ${options}
                </select>`
                : `<input type="hidden" id="idUsuario" value="${response.usuario.id}"/>
                <input type="text" id="usuario" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.usuario.name}" ${response && response.usuario.name ? 'disabled' : ""} ${response && response.deleted_at!=null ? 'disabled' : ""}>`
            }
        </div>
            
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
            <label for="representacion" class="block text-sm text-gray-600 w-32">Representación: *</label>
            ${!response ? `
                <select id="representacion" class="swal2-input miembro text-sm text-gray-600 border w-60 px-2 py-1 rounded-md outline-none bg-blue-50" >
                    <option value="">-----</option>
                    ${options = ""}
                    ${console.log(representaciones)}
                    ${representaciones.forEach(rep => {            
                        options+='<option value="'+rep.id+'" ';
                        options+='>'+rep.nombre+'</option>';                                               
                    })}
                    ${options}
                </select>`
                : `<input type="hidden" id="idRepresentacion" value="${representacion.id}"/>
                <input type="text" id="representacion" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.representacion.nombre}" ${response && response.representacion.nombre ? 'disabled' : ""} ${response && response.deleted_at!=null ? 'disabled' : ""}>`
            }
        </div>

        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-1 justify-center items-center">
            <label for="fechaTomaPosesion" class="block text-sm text-gray-600 w-32">Fecha Toma posesión:</label>
            <input type="date" id="fechaTomaPosesion" class="swal2-input miembro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" value="${response.fechaTomaPosesion}" ${response && response.deleted_at!=null ? 'disabled' : ""}>
        </div>
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-5 justify-center items-center">
            <label for="fechaCese" class="block text-sm text-gray-600 w-32">Fecha cese:</label>
            <input type="date" id="fechaCese" class="swal2-input miembro text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.fechaCese}" ${response && response.deleted_at!=null ? 'disabled' : ""}>
        </div>
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
            <label for="responsable" class="block text-sm text-gray-600 mb-1 w-32">Responsable de centro:</label>
            <select id="responsable" class="miembro swal2-input tipo text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none">                     
                <option value="0">No</option>
                <option value="1" ${((response.usuario.roles).find(rol=>{return rol.name === 'responsable_centro'})) ? 'selected' : '' }>Sí</option>
            </select>
        </div>             
    `
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
    
        /*case 'update':

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
            break*/
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
            /*const dataToSend = {
                id: button.dataset.miembroId,
            };   

            // Obtenemos el miembro a editar
            const response = await GET_MIEMBRO_BBDD(dataToSend)*/

            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Miembro eliminado' : 'Editar Miembro',
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
                //preConfirm: async () => preConfirm('update', button.dataset.juntaId),
                //preDeny: async () => preConfirm('delete', button.dataset.juntaId),
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




// EVENTO EDITAR
/*const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {

        var options = "";

        const dataToSend = {
            id: button.dataset.miembroId,
        };

        try {
            // Obtenemos el miembro a editar
            const response = await GET_MIEMBROSGOBIERNO_BBDD(dataToSend);

            const dataToSendCentro = {
                id: response.idCentro,
            };
    
            const dataToSendUsuario = {
                id: response.idUsuario,
            };
    
            const dataToSendRepresentacion = {
                id: response.idRepresentacion,
            };

            const dataToSendJunta = {
                id: response.idJunta,
            };

            const centro = await GET_CENTRO_BBDD(dataToSendCentro); 

            const usuario = await GET_USER_BBDD(dataToSendUsuario); 

            const representacion = await GET_REPRESENTACION_BBDD(dataToSendRepresentacion); 

            const juntas = await GETALL_JUNTA_BBDD(); 

            const result = await Swal.fire({
                title: "Editar Miembro Gobierno",
                html: `
                    <input type="hidden" id="idCentro" value="${centro.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="centro" class="block text-sm text-gray-600 w-32">Centro:</label>
                        <input type="text" id="centro" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${centro.nombre}" disabled>
                    </div>
                    <input type="hidden" id="idUsuario" value="${usuario.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="usuario" class="block text-sm text-gray-600 w-32">Usuario:</label>
                        <input type="text" id="usuario" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${usuario.name}" disabled>
                    </div>
                    <input type="hidden" id="idRepresentacion" value="${representacion.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="representacion" class="block text-sm text-gray-600 w-32">Representación:</label>
                        <input type="text" id="representacion" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${representacion.nombre}" disabled>
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-1 justify-center items-center">
                        <label for="fechaTomaPosesion" class="block text-sm text-gray-600 w-32">Fecha Toma posesión:</label>
                        <input type="date" id="fechaTomaPosesion" class="swal2-input miembro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required value="${response.fechaTomaPosesion}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-5 justify-center items-center">
                        <label for="fechaCese" class="block text-sm text-gray-600 w-32">Fecha cese:</label>
                        <input type="date" id="fechaCese" class="swal2-input miembro text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.fechaCese}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
                        <label for="responsable" class="block text-sm text-gray-600 mb-1 w-32">Responsable de centro:</label>
                        <select id="responsable" class="miembro swal2-input tipo text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" ">                     
                            <option value="0">No</option>
                            <option value="1" ${((usuario.roles).find(rol=>{return rol.name === 'responsable_centro'})) ? 'selected' : '' }>Sí</option>
                        </select>
                    </div>             
                `,
                focusConfirm: false,
                showDenyButton: true,
                showCancelButton: true,
                denyButtonText: 'Eliminar',
                confirmButtonText: "Actualizar",
                cancelButtonText: "Cancelar",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '',
                denyButtonColor: '#d33',
            });

            // BOTÓN ACTUALIZAR
            if (result.isConfirmed) {
                const inputs = document.querySelectorAll(".miembro");
                const valores = {};
                let error = 0;

                inputs.forEach((input) => {
                    if (input.id!='idJunta' && input.id!='fechaCese' && input.value === "") {
                        error++;
                    }

                    valores[input.id] = input.value;
                });

                // Si es vacío fechaCese, colocamos un null
                if(!valores['fechaCese']){
                    valores['fechaCese']=null;
                }
                      
                if (error > 0) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Faltan campos por rellenar.",
                    });

                } else {

                    const dataToSend = {
                        id: button.dataset.miembroId,
                        data: valores,
                    };

                    const response = await UPDATE_MIEMBROSGOBIERNO_BBDD(dataToSend);

                    if (response.status === 200) {

                        await Swal.fire({
                            icon: "success",
                            title: "Actualizado!",
                            text: "Se ha editado el miembro de Gobierno.",
                        });

                        window.location.reload();

                    } else {

                        await Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: response.error,
                        });

                    }
                }
            }
            // BOTÓN ELIMINAR
            else if (result.isDenied) {

                try {
                    const result = await Swal.fire({
                        title: "¿Eliminar el miembro de gobierno?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "",
                        confirmButtonText: "Eliminar",
                    });

                    if (result.isConfirmed) {

                        const response = await DELETE_MIEMBROSGOBIERNO_BBDD(dataToSend);
 
                        await Swal.fire(
                            "Eliminado",
                            "El miembro de gobierno fue eliminado.",
                            "success"
                        );
                        
                        window.location.reload();
                    }

                } catch (error) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Ha ocurrido un error al eliminar el miembro de gobierno",
                    });
                }
            }
        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar el miembro de Gobierno.",
            });
        }
    });
};*/

const editButtons = document.querySelectorAll('#btn-editar-miembro');

editButtons.forEach(button => {
    addEditEvent(button);
});


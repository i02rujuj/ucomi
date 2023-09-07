import { DELETE_MIEMBROSGOBIERNO_BBDD, GET_MIEMBROSGOBIERNO_BBDD, UPDATE_MIEMBROSGOBIERNO_BBDD } from "./axiosTemplate.js";
import {GET_JUNTA_BBDD, GETALL_JUNTA_BBDD} from '../juntas/axiosTemplate';
import {GET_CENTRO_BBDD} from '../centros/axiosTemplate';
import {GET_USER_BBDD} from '../users/axiosTemplate';
import {GET_REPRESENTACION_BBDD} from '../representaciones/axiosTemplate';

import Swal from 'sweetalert2';

import $ from 'jquery';
import select2 from 'select2';
//Hook up select2 to jQuery
select2($);
document.addEventListener("DOMContentLoaded", $('#idUsuario').select2());

// EVENTO EDITAR
const addEditEvent = (button) => {
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
            console.log(usuario);

            const representacion = await GET_REPRESENTACION_BBDD(dataToSendRepresentacion); 

            const juntas = await GETALL_JUNTA_BBDD(); 

            const result = await Swal.fire({
                title: "Editar Miembro Gobierno",
                html: `
                    <input type="hidden" id="idCentro" value="${centro.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="centro" class="block text-sm text-gray-600 w-32">Centro:</label>
                        <input type="text" id="centro" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${centro.nombre}" readonly>
                    </div>
                    <input type="hidden" id="idUsuario" value="${usuario.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="usuario" class="block text-sm text-gray-600 w-32">Usuario:</label>
                        <input type="text" id="usuario" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${usuario.name}" readonly>
                    </div>
                    <input type="hidden" id="idRepresentacion" value="${representacion.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="representacion" class="block text-sm text-gray-600 w-32">Representación:</label>
                        <input type="text" id="representacion" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${representacion.nombre}" readonly>
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
                        <label for="idJunta" class="block text-sm text-gray-600 mb-1 w-32">Junta que representa:</label>
                        <select id="idJunta" class="miembro swal2-input tipo text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" ">
                            <option value="">-----</option>
                            ${juntas.forEach(j => { 
                                if(j.idCentro == response.idCentro){
                                    options+='<option value="'+j.id+'" ';
                                    if(j.id == response.idJunta) 
                                        options+='selected';
                                    options+='>'+j.nombre+' ('+j.fechaConstitucion+')</option>';
                                }
                            })}
                            ${options}
                        </select>
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
            console.error(error);
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar el miembro de Gobierno.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-miembro');

editButtons.forEach(button => {
    addEditEvent(button);
});


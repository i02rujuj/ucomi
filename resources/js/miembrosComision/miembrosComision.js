import { DELETE_MIEMBROSCOMISION_BBDD, GET_MIEMBROSCOMISION_BBDD, UPDATE_MIEMBROSCOMISION_BBDD } from "./axiosTemplate.js";
import {GET_COMISION_BBDD} from '../comisiones/axiosTemplate.js';
import {GET_USER_BBDD} from '../users/axiosTemplate.js';
import {GETALL_REPRESENTACION_BBDD} from '../representacionesGeneral/axiosTemplate.js';

import Swal from 'sweetalert2';

import $ from 'jquery';
import select2 from 'select2';
//Hook up select2 to jQuery
select2($);
document.addEventListener("DOMContentLoaded", $('#idUsuario').select2());

// EVENTO EDITAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        const dataToSend = {
            id: button.dataset.miembroId,
        };

        try {

            var options ="";

            // Obtenemos el miembro a editar
            const response = await GET_MIEMBROSCOMISION_BBDD(dataToSend);

            const dataToSendComision = {
                id: response.idComision,
            };
    
            const dataToSendUsuario = {
                id: response.idUsuario,
            };
    
            const dataToSendRepresentacion = {
                id: response.idRepresentacion,
            };

            const comision = await GET_COMISION_BBDD(dataToSendComision);

            const usuario = await GET_USER_BBDD(dataToSendUsuario); 

            const representaciones = await GETALL_REPRESENTACION_BBDD(dataToSendRepresentacion); 

            const result = await Swal.fire({
                title: "Editar Miembro Comisión",
                html: `
                    <input type="hidden" id="idComision" value="${comision.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="comision" class="block text-sm text-gray-600 w-32">Comisión:</label>
                        <input type="text" id="comision" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${comision.nombre}" readonly>
                    </div>
                    <input type="hidden" id="idUsuario" value="${usuario.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="usuario" class="block text-sm text-gray-600 w-32">Usuario:</label>
                        <input type="text" id="usuario" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${usuario.name}" readonly>
                    </div>

                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
                        <label for="idRepresentacion" class="block text-sm text-gray-600 mb-1 w-32">Tipo:</label>
                        <select id="idRepresentacion" class="miembro swal2-input tipo text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required">
                            <option value="">-----</option>
                            ${representaciones.forEach(rep => {            
                                options+='<option value="'+rep.id+'" ';
                                if(rep.id == response.idRepresentacion) 
                                    options+='selected';
                                options+='>'+rep.nombre+'</option>';                                               
                            })}
                            ${options}
                        </select>
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
                            <option value="1" ${((usuario.roles).find(rol=>{return rol.name === 'responsable_comision'})) ? 'selected' : '' }>Sí</option>
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

            if (result.isConfirmed) {
                const inputs = document.querySelectorAll(".miembro");
                const valores = {};
                let error = 0;

                inputs.forEach((input) => {

                    if (input.id!='fechaCese' && input.value === "") {
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

                    const response = await UPDATE_MIEMBROSCOMISION_BBDD(dataToSend);

                    if (response.status === 200) {
                        await Swal.fire({
                            icon: "success",
                            title: "Updated!",
                            text: "Se ha editado el miembro de Comisión.",
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
                        title: "¿Eliminar el miembro de comisión?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "",
                        confirmButtonText: "Eliminar",
                    });

                    if (result.isConfirmed) {

                        const response = await DELETE_MIEMBROSCOMISION_BBDD(dataToSend);
 
                        await Swal.fire(
                            "Eliminado",
                            "El miembro de comisión fue eliminado.",
                            "success"
                        );
                        
                        window.location.reload();
                    }

                } catch (error) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Ha ocurrido un error al eliminar el miembro de comisión",
                    });
                }
            }
        } catch (error) {
            console.error(error);
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar el miembro de Comisión.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-miembro');

editButtons.forEach(button => {
    addEditEvent(button);
});

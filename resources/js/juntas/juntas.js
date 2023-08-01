import { DELETE_JUNTA_BBDD, GET_JUNTA_BBDD, UPDATE_JUNTA_BBDD } from "./axiosTemplate.js";
import {GET_CENTRO_BBDD} from '../centros/axiosTemplate';

import Swal from 'sweetalert2';

// EVENTO EDITAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        
        const dataToSend = {
            id: button.dataset.juntaId,
        };

        try {

            // Obtenemos la junta a editar
            const response = await GET_JUNTA_BBDD(dataToSend);

            const dataToSendCentro = {
                id: response.idCentro,
            };

            const centro = await GET_CENTRO_BBDD(dataToSendCentro); 

            const dataToSendUsuario = {
                id: response.idUsuario,
            };

            const result = await Swal.fire({
                title: "Editar Junta",
                html: `
                    <input type="hidden" id="idCentro" value="${centro.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="centro" class="block text-sm text-gray-600 w-32">Centro:</label>
                        <input type="text" id="centro" class="swal2-input junta text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${centro.nombre}" readonly>
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-1 justify-center items-center">
                        <label for="fechaConstitucion" class="block text-sm text-gray-600 w-32">Fecha Constitución:</label>
                        <input type="date" id="fechaConstitucion" class="swal2-input junta text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required value="${response.fechaConstitucion}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-5 justify-center items-center">
                        <label for="fechaDisolucion" class="block text-sm text-gray-600 w-32">Fecha Disolución:</label>
                        <input type="date" id="fechaDisolucion" class="junta swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.fechaDisolucion}">
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

                const inputs = document.querySelectorAll(".junta");
                const valores = {};
                let error = 0;

                inputs.forEach((input) => {
                    valores[input.id] = input.value;
                    if (input.id!='fechaDisolucion' && input.value === "") {
                        error++;
                    }
                });

                // Si es vacío fechaDisolución, colocamos un null
                if(!valores['fechaDisolucion']){
                    valores['fechaDisolucion']=null;
                }
                
                if (error > 0) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Faltan campos por rellenar.",
                    });

                } else {

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
                        });

                        // BOTÓN ACTUALIZAR CESANDO A LOS MIEMBROS
                        if (result2.isConfirmed) {

                            const dataToSend = {
                                id: button.dataset.juntaId,
                                data: valores,
                            };

                            const response = await UPDATE_JUNTA_BBDD(dataToSend);

                            if (response.status === 200) {

                                await Swal.fire({
                                    icon: "success",
                                    title: "Updated!",
                                    text: "Se ha editado la junta y se han cesado a todos los miembros de la junta.",
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
                    else{
                        // BOTÓN ACTUALIZAR
                        if (result.isConfirmed) {

                            const dataToSend = {
                                id: button.dataset.juntaId,
                                data: valores,
                            };

                            const response = await UPDATE_JUNTA_BBDD(dataToSend);

                            if (response.status === 200) {

                                await Swal.fire({
                                    icon: "success",
                                    title: "Updated!",
                                    text: "Se ha editado la junta.",
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
                }
            }
            // BOTÓN ELIMINAR
            else if (result.isDenied) {

                try {
                    const result = await Swal.fire({
                        title: "¿Eliminar la junta?",
                        text: "También se eliminarán todos los miembros de la junta",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "",
                        confirmButtonText: "Eliminar",
                    });

                    if (result.isConfirmed) {

                        const response = await DELETE_JUNTA_BBDD(dataToSend);
 
                        await Swal.fire(
                            "Eliminado",
                            "La junta y todos sus miembros fueron eliminados.",
                            "success"
                        );
                        
                        window.location.reload();
                    }

                } catch (error) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Ha ocurrido un error al eliminar la junta",
                    });
                }
            }
        } catch (error) {

            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar la junta.",
            });

        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-junta');

editButtons.forEach(button => {
    addEditEvent(button);
});
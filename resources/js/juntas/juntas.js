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
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "",
                        confirmButtonText: "Eliminar",
                    });

                    if (result.isConfirmed) {

                        const response = await DELETE_JUNTA_BBDD(dataToSend);

                        if (response.status === 200) {

                            await Swal.fire(
                                "Eliminada",
                                "La junta fue eliminada.",
                                "success"
                            );

                            window.location.reload();

                        } 
                        else {
                            await Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Ha ocurrido un error al eliminar la junta. " + response.error,
                            });
                        }
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

// EVENTO COMPROBAR DIRECTOR Y SECRETARIO
const selectJuntas = document.getElementById("idCentro");

selectJuntas.addEventListener("change", async (event) => {

    const idCentro = document.getElementById("idCentro").value;
    const idDirector = document.getElementById("idDirector");
    const idSecretario = document.getElementById("idSecretario");
    const nombreDirector = document.getElementById("nombreDirector");
    const nombreSecretario = document.getElementById("nombreSecretario");
    const errorDirector = document.getElementById("errorDirector");
    const errorSecretario = document.getElementById("errorSecretario");
    const errorDirectorFront = document.getElementById("errorDirectorFront");
    const errorSecretarioFront = document.getElementById("errorSecretarioFront");

    if(errorDirector)
        errorDirector.innerHTML="";
    if(errorSecretario)
        errorSecretario.innerHTML="";

    errorDirectorFront.innerHTML="";
    errorSecretarioFront.innerHTML="";

    $.ajax({
        type: "POST",
        url: '/miembro_gobierno/getDirectivos',
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            idCentro: idCentro,
        },
        success: function (response) {
            //director.value=response['director']?.id ?? "No existe directivo activo para el centro seleccionado";
            //secretario.value=response['secretario']?.id ?? "---";

            if(response['director'] && Object.hasOwn(response['director'], 'id')){
                idDirector.value=response['director'].id;
                nombreDirector.value=response['director'].name;
            }
            else{
                idDirector.value="";
                nombreDirector.value ="";
                errorDirectorFront.innerHTML = '<span class="text-red-500 text-xs mt-1">No existe director/decano vigente para el centro seleccionado. <a class="inline-block w-full mt-1 md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" href="/miembros_gobierno?idCentro='+idCentro+'&idRepresentacion=1">Añadir director</a>';
            }

            if(response['secretario'] && Object.hasOwn(response['secretario'], 'id')){
                idSecretario.value=response['secretario'].id;
                nombreSecretario.value=response['secretario'].name;
            }
            else{
                idSecretario.value="";
                nombreSecretario.value = "";
                errorSecretarioFront.innerHTML = '<span class="text-red-500 text-xs mt-1">No existe secretario vigente para el centro seleccionado. <a class="inline-block w-full mt-1 md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" href="/miembros_gobierno?idCentro='+idCentro+'&idRepresentacion=3">Añadir secretario</a>';
            }
        },
        error: function (errorMessage) {
            $("#errorMessage").append("Error: " + errorMessage);
        }
    });
});
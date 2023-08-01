import { DELETE_COMISION_BBDD, GET_COMISION_BBDD, UPDATE_COMISION_BBDD } from "./axiosTemplate.js";
import {GET_CENTRO_BBDD} from '../centros/axiosTemplate.js';
import {GETALL_JUNTA_BBDD} from '../juntas/axiosTemplate.js';

import Swal from 'sweetalert2';

// EVENTO EDITAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        
        const dataToSend = {
            id: button.dataset.comisionId,
        };

        try {

            var options ="";

            // Obtenemos la comisión a editar
            const response = await GET_COMISION_BBDD(dataToSend);

            const dataToSendJunta = {
                id: response.idJunta,
            };

            const juntas = await GETALL_JUNTA_BBDD(dataToSendJunta); 

            const result = await Swal.fire({
                title: "Editar Comisión",
                html: `
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-1 justify-center items-center">
                        <label for="nombre" class="block text-sm text-gray-600 w-32">Nombre:</label>
                        <input type="text" id="nombre" class="swal2-input comision text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required value="${response.nombre}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-5 justify-center items-center">
                        <label for="descripcion" class="block text-sm text-gray-600 w-32">Descripción:</label>
                        <input type="text" id="descripcion" class="swal2-input comision text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${(response.descripcion == null) ? '' : response.descripcion}">
                    </div>

                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
                        <label for="idJunta" class="block text-sm text-gray-600 mb-1 w-32">Junta que gestiona la comisión:</label>
                        <select id="idJunta" class="comision swal2-input text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required">
                            <option value="">-----</option>
                            ${juntas.forEach(junta => {  
                                options+='<option value="'+junta.id+'" ';
                                if(junta.id == response.idJunta) 
                                    options+='selected';
                                options+='>'+junta.nombre+' ('+junta.fechaConstitucion+')</option>';                                               
                            })}
                            ${options}
                        </select>
                    </div>

                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-1 justify-center items-center">
                        <label for="fechaConstitucion" class="block text-sm text-gray-600 w-32">Fecha Constitución:</label>
                        <input type="date" id="fechaConstitucion" class="swal2-input comision text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required value="${response.fechaConstitucion}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-5 justify-center items-center">
                        <label for="fechaDisolucion" class="block text-sm text-gray-600 w-32">Fecha Disolución:</label>
                        <input type="date" id="fechaDisolucion" class="comision swal2-input text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.fechaDisolucion}">
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

                const inputs = document.querySelectorAll(".comision");
                const valores = {};
                let error = 0;

                inputs.forEach((input) => {
                    valores[input.id] = input.value;
                    if (input.id!='fechaDisolucion' && input.id!='descripcion' && input.value === "") {
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

                    const dataToSend = {
                        id: button.dataset.comisionId,
                        data: valores,
                    };

                    const response = await UPDATE_COMISION_BBDD(dataToSend);

                    if (response.status === 200) {
                        await Swal.fire({
                            icon: "success",
                            title: "Updated!",
                            text: "Se ha editado la comision.",
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
                        title: "¿Eliminar la comisión?",
                        text: "",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "",
                        confirmButtonText: "Eliminar",
                    });

                    if (result.isConfirmed) {

                        const response = await DELETE_COMISION_BBDD(dataToSend);
 
                        await Swal.fire(
                            "Eliminado",
                            "La comisión se ha eliminado.",
                            "success"
                        );
                        
                        window.location.reload();
                    }

                } catch (error) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Ha ocurrido un error al eliminar la comisión",
                    });
                }
            }
        } catch (error) {

            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar la comisión.",
            });

        }

    });
};

const editButtons = document.querySelectorAll('#btn-editar-comision');

editButtons.forEach(button => {
    addEditEvent(button);
});

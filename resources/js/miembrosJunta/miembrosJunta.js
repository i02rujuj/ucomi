import { DELETE_MIEMBROSJUNTA_BBDD, GET_MIEMBROSJUNTA_BBDD, UPDATE_MIEMBROSJUNTA_BBDD } from "./axiosTemplate.js";
import {GET_JUNTA_BBDD} from '../juntas/axiosTemplate.js';
import {GET_CENTRO_BBDD} from '../centros/axiosTemplate.js';
import {GET_USER_BBDD} from '../users/axiosTemplate.js';
import {GETALL_REPRESENTACION_BBDD} from '../representacionesGeneral/axiosTemplate.js';
import Swal from 'sweetalert2';

// EVENTO EDITAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        const dataToSend = {
            id: button.dataset.miembroId,
        };

        try {

            var options ="";

            // Obtenemos el miembro a editar
            const response = await GET_MIEMBROSJUNTA_BBDD(dataToSend);

            const dataToSendJunta = {
                id: response.idJunta,
            };
    
            const dataToSendUsuario = {
                id: response.idUsuario,
            };
    
            const dataToSendRepresentacion = {
                id: response.idRepresentacion,
            };

            const junta = await GET_JUNTA_BBDD(dataToSendJunta);

            const dataToSendCentro = {
                id: junta.idCentro,
            };
            
            const centro = await GET_CENTRO_BBDD(dataToSendCentro);

            const usuario = await GET_USER_BBDD(dataToSendUsuario); 

            const representaciones = await GETALL_REPRESENTACION_BBDD(dataToSendRepresentacion); 

            const result = await Swal.fire({
                title: "Editar Miembro Junta",
                html: `
                    <input type="hidden" id="idJunta" value="${junta.id}"/>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 justify-center items-center">
                        <label for="junta" class="block text-sm text-gray-600 w-32">Junta:</label>
                        <input type="text" id="junta" class="swal2-input miembro text-sm text-gray-600 border bg-red-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${centro.nombre} (${junta.fechaConstitucion})" readonly>
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

                    console.log(dataToSend);
                    const response = await UPDATE_MIEMBROSJUNTA_BBDD(dataToSend);

                    if (response.status === 200) {
                        await Swal.fire({
                            icon: "success",
                            title: "Updated!",
                            text: "Se ha editado el miembro de Junta.",
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
                        title: "¿Eliminar el miembro de junta?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "",
                        confirmButtonText: "Eliminar",
                    });

                    if (result.isConfirmed) {

                        const response = await DELETE_MIEMBROSJUNTA_BBDD(dataToSend);
 
                        await Swal.fire(
                            "Eliminado",
                            "El miembro de junta fue eliminado.",
                            "success"
                        );
                        
                        window.location.reload();
                    }

                } catch (error) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Ha ocurrido un error al eliminar el miembro de junta",
                    });
                }
            }
        } catch (error) {
            console.error(error);
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar el miembro de Junta.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-miembro');

editButtons.forEach(button => {
    addEditEvent(button);
});


// EVENTO ELIMINAR
const addDeleteEvent = (button) => {
    button.addEventListener("click", async (event) => {
        let dataToSend = {};

        if (button.dataset.estado == 0) {
            dataToSend = {
                id: button.dataset.miembroId,
                estado: button.dataset.estado,
            };
        } else {
            dataToSend = {
                id: button.dataset.miembroId,
                estado: button.dataset.estado,
            };
        }
        try {
            const result = await Swal.fire({
                title:
                    button.dataset.estado == 1
                        ? "¿Deshabilitar el miembro?"
                        : "¿Habilitar el miembro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText:
                    button.dataset.estado == 1 ? "Deshabilitar" : "Habilitar",
            });
            if (result.isConfirmed) {
                const response = await DELETE_MIEMBROSJUNTA_BBDD(dataToSend);
                console.log(response);
                if (button.dataset.estado == 1) {
                    await Swal.fire(
                        "Deshabilitado",
                        "El miembro de Junta fue deshabilitado.",
                        "success"
                    );
                } else {
                    await Swal.fire(
                        "Habilitado",
                        "El miembro de Junta fue habilitado.",
                        "success"
                    );
                }
                window.location.reload();
            }
        } catch (error) {
            console.error(error);
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al cambiar el estado del miembro de Junta",
            });
        }
    });
};

const deleteButtons = document.querySelectorAll('#btn-delete-miembro');

deleteButtons.forEach(button => {
    addDeleteEvent(button);
});


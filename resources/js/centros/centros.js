import { DELETE_CENTRO_BBDD, GET_CENTRO_BBDD, UPDATE_CENTRO_BBDD } from "./axiosTemplate.js";
import { GET_TIPOSCENTRO_BBDD } from "../tiposCentro/axiosTemplate";
import Swal from 'sweetalert2';

// EVENTO EDITAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {

        const dataToSend = {
            id: button.dataset.centroId,
        };

        try {
            // Obtenemos los tipos de Centro
            const tiposCentro = await GET_TIPOSCENTRO_BBDD();
            var options ="";

            // Obtenemos el centro a editar
            const response = await GET_CENTRO_BBDD(dataToSend);
            const result = await Swal.fire({
                title: "Editar Centro",
                html: `
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-1 justify-center items-center">
                        <label for="nombre" class="block text-sm text-gray-600 w-32">Nombre:</label>
                        <input type="text" id="nombre" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required value="${response.nombre}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-5 justify-center items-center">
                        <label for="direccion" class="block text-sm text-gray-600 w-32">Direccion:</label>
                        <input type="text" id="direccion" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.direccion}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
                        <label for="idTipo" class="block text-sm text-gray-600 mb-1 w-32">Tipo:</label>
                        <select id="idTipo" class="centro swal2-input tipo text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required">
                            <option value="">-----</option>
                            ${tiposCentro.forEach(tipo => {            
                                options+='<option value="'+tipo.id+'" ';
                                if(tipo.id == response.idTipo) 
                                    options+='selected';
                                options+='>'+tipo.nombre+'</option>';                                               
                            })}
                            ${options}
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

                const inputs = document.querySelectorAll(".centro");
                const valores = {};
                let error = 0;

                inputs.forEach((input) => {
                    valores[input.id] = input.value;
                    if (input.value === "") {
                        error++;
                    }
                });
                
                if (error > 0) {
                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Faltan campos por rellenar.",
                    });
                } 
                else {

                    const dataToSend = {
                        id: button.dataset.centroId,
                        data: valores,
                    };

                    const response = await UPDATE_CENTRO_BBDD(dataToSend);
                    
                    if (response.status === 200) {
                        await Swal.fire({
                            icon: "success",
                            title: "Actualizado!",
                            text: "Se ha editado el centro.",
                        });
                        window.location.reload();
                    } 
                    else {
                        await Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Ha ocurrido un error al actualizar el centro.",
                        });
                    }
                }
            }
            // BOTÓN ELIMINAR
            else if (result.isDenied) {

                try {
                    const result = await Swal.fire({
                        title: "¿Eliminar el centro?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "",
                        confirmButtonText: "Eliminar",
                    });

                    if (result.isConfirmed) {

                        const response = await DELETE_CENTRO_BBDD(dataToSend);
 
                        await Swal.fire(
                            "Eliminado",
                            "El centro fue eliminado.",
                            "success"
                        );
                        
                        window.location.reload();
                    }

                } catch (error) {

                    await Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Ha ocurrido un error al eliminar el centro",
                    });
                }
            }
        } catch (error) {
            console.error(error);
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar el centro.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-centro');

editButtons.forEach(button => {
    addEditEvent(button);
});


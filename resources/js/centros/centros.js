import { DELETE_CENTRO_BBDD, GET_CENTRO_BBDD, UPDATE_CENTRO_BBDD } from "./axiosTemplate.js";
import Swal from 'sweetalert2';

// EVENTO EDITAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        const dataToSend = {
            id: button.dataset.centroId,
        };
        try {
            const response = await GET_CENTRO_BBDD(dataToSend);
            const result = await Swal.fire({
                title: "Editar Centro",
                html: `
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-2 mt-1 justify-center items-center">
                        <label class="block text-sm text-gray-600 w-32">Nombre:</label>
                        <input type="text" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required value="${response.nombre}" id="nombre">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-5 justify-center items-center">
                        <label for="direccion" class="block text-sm text-gray-600 w-32">Direccion:</label>
                        <input type="text" id="domicilio" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-mdoutline-none" value="${response.direccion}">
                    </div>
                    <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
                        <label for="tipo" class="block text-sm text-gray-600 mb-1 w-32">Tipo:</label>
                        <select id="tipo" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none" required value="${response.tipo}">
                            <option value="propio">Propio</option>
                            <option value="adscrito">Adscrito</option>
                        </select>
                    </div>
                `,
                focusConfirm: false,
                showCancelButton: true,
                confirmButtonText: "Actualizar",
                cancelButtonText: "Cancelar",
            });
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
                } else {
                    const dataToSend = {
                        id: button.dataset.centroId,
                        data: valores,
                    };
                    console.log(dataToSend);
                    const response = await UPDATE_CENTRO_BBDD(dataToSend);
                    console.log(response.status);
                    if (response.status === 200) {
                        await Swal.fire({
                            icon: "success",
                            title: "Updated!",
                            text: "Se ha editado el centro.",
                        });
                        window.location.reload();
                    } else {
                        await Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Ha ocurrido un error al actualizar el centro.",
                        });
                    }
                }
            }
        } catch (error) {
            console.error(error);
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al editar la sede.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-centro');

editButtons.forEach(button => {
    addEditEvent(button);
});

// EVENTO ELIMINAR
const addDeleteEvent = (button) => {
    button.addEventListener("click", async (event) => {
        let dataToSend = {};

        if (button.dataset.estado == 0) {
            dataToSend = {
                id: button.dataset.centroId,
                estado: button.dataset.estado,
            };
        } else {
            dataToSend = {
                id: button.dataset.centroId,
                estado: button.dataset.estado,
            };
        }
        try {
            const result = await Swal.fire({
                title:
                    button.dataset.estado == 1
                        ? "¿Deshabilitar el centro?"
                        : "¿Habilitar el centro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText:
                    button.dataset.estado == 1 ? "Deshabilitar" : "Habilitar",
            });
            if (result.isConfirmed) {
                const response = await DELETE_CENTRO_BBDD(dataToSend);
                console.log(response);
                if (button.dataset.estado == 1) {
                    await Swal.fire(
                        "Deshabilitado",
                        "El centro fue deshabilitado.",
                        "success"
                    );
                } else {
                    await Swal.fire(
                        "Habilitado",
                        "El centro fue habilitado.",
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
                text: "Ha ocurrido un error al cambiar el estado del centro",
            });
        }
    });
};

const deleteButtons = document.querySelectorAll('#btn-delete-centro');

deleteButtons.forEach(button => {
    addDeleteEvent(button);
});


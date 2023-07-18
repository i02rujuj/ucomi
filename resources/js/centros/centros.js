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
                    <label>Nombre:</label>
                    <input type="text"  class="swal2-input centro" value="${response.nombre}" id="nombre">
                    <label>Direccion:</label>
                    <input type="text" id="domicilio" class="swal2-input centro" value="${response.direccion}">
                    <label>Tipo:</label>
                    <input type="text" id="tipo" class="swal2-input centro" value="${response.tipo}">
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


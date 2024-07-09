import { DELETE_CENTRO_BBDD, GET_CENTRO_BBDD, UPDATE_CENTRO_BBDD, ADD_CENTRO_BBDD } from "./axiosTemplate.js";
import { GET_TIPOSCENTRO_BBDD } from "../tiposCentro/axiosTemplate";
import Swal from 'sweetalert2';

let tiposCentro = null

document.addEventListener("DOMContentLoaded", async (event) => {
    // Obtener tipos centro
    tiposCentro = await GET_TIPOSCENTRO_BBDD();
    var select = document.querySelector('#filtroTipo');
 
    // Rellenar select filtro tipoCentro
    const searchParams = new URLSearchParams(window.location.search);
    tiposCentro.forEach(tipo => {
        var option = document.createElement("option");
        option.text = tipo.nombre
        option.value = tipo.id
        if(tipo.id==searchParams.get('filtroTipo')){
            option.selected = "selected"
        }
        select.add(option);
    })
})

function renderHTMLCentro(response){

    let options ="";

    return  `
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mt-1 justify-center items-center">
            <label for="nombre" class="block text-sm text-gray-600 w-32 text-right">Nombre *</label>
            <input type="text" id="nombre" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 rounded-md w-60 px-2 py-1 outline-none required" value="${response ? response.nombre : ""}" ${response && response.deleted_at!=null ? 'disabled' : ""}>
        </div>

        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-3 justify-center items-center">
            <label for="direccion" class="block text-sm text-gray-600 w-32 text-right">Direccion *</label>
            <input type="text" id="direccion" class="swal2-input centro text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none required" value="${response ? response.direccion: ""}" ${response && response.deleted_at!=null ? 'disabled' : ""}>
        </div>

        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
            <label for="idTipo" class="block text-sm text-gray-600 mb-1 w-32 pr-7 text-right">Tipo *</label>
            <select id="idTipo" class="swal2-input centro tipo text-sm text-gray-600 border bg-blue-50 w-60 px-2 py-1 rounded-md outline-none required" ${response && response.deleted_at!=null ? 'disabled' : ""}>
                <option value="">-----</option>
                ${tiposCentro.forEach(tipo => {            
                    options+='<option value="'+tipo.id+'" ';
                    if(response && tipo.id == response.idTipo) 
                        options+='selected';
                    options+='>'+tipo.nombre+'</option>';                                               
                })}
                ${options}
            </select>
        </div>
        <div class="flex flex-wrap md:flex-wrap lg:flex-nowrap w-full mb-4 justify-center items-center">
            <label for="img_logo" class="block text-sm text-gray-600 w-32 text-right">
                <img id="img_logo" name="img_logo" src="${response? response.logo : default_image}" alt="Imagen de centro" class="w-16 h-16 ml-1 mb-1 justify-self-center rounded-full object-cover">  
            </label>
            <input id="logo" name="logo" type="file" class="centro w-60 text-sm text-gray-600 border bg-blue-50 rounded-md px-2 py-1 outline-none" autocomplete="off" ${response && response.deleted_at!=null ? 'disabled' : ""}/>
        </div>
    `
}

const preConfirm = async(accion, id=null) => {
    let valores = {};

    const inputs = document.querySelectorAll(".centro");
    inputs.forEach((input) => {
        if(input.id!='logo'){
            valores[input.id] = input.value;
        }
        else{
            valores[input.id] = input.files[0]
        }
    });

    let dataToSend, response, title, text = null

    switch (accion) {
        case 'add':
            dataToSend = {
                data: valores,
            };
            response = await ADD_CENTRO_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido el centro"
            break;
    
        case 'update':
            dataToSend = {
                id: id,
                data: valores,
            };
            response = await UPDATE_CENTRO_BBDD(dataToSend)
            title="Actualizado"
            text="Se ha actualizado el centro"
            break;

        case 'delete':

            const result = await Swal.fire({
                title: "¿Eliminar el centro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "",
                confirmButtonText: "Eliminar",
            });

            if (result.isConfirmed) {

                dataToSend = {
                    id: id,
                };

                response = await DELETE_CENTRO_BBDD(dataToSend);
                title="Eliminado"
                text="Se ha eliminado el centro"
            }
            break;
    }

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
    }
}

/**
 * EVENTO AÑADIR
 */
const addButton = document.querySelector('#btn-add-centro');
addButton.addEventListener("click", async (event) => {

    await Swal.fire({
        title: "Añadir Centro",
        html: renderHTMLCentro(null),
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: "Añadir",
        cancelButtonText: "Cancelar",
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        didRender: () => {
            const logo = document.querySelector('#logo')
            event_change_image(logo)
        },
        preConfirm: async () => preConfirm('add')
    })
})

// EVENTO EDITAR Y ELIMINAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        try {
            const dataToSend = {
                id: button.dataset.centroId,
            }

            const response = await GET_CENTRO_BBDD(dataToSend);
            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Centro eliminado' : 'Editar Centro',
                html: renderHTMLCentro(response),
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
                didRender: function() {
                    const logo = document.querySelector('#logo')
                    event_change_image(logo)
                },
                preConfirm: async () => preConfirm('update', button.dataset.centroId),
                preDeny: async () => preConfirm('delete', button.dataset.centroId),
            });

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con el centro.",
            });
        }
    });
};

const editButtons = document.querySelectorAll('#btn-editar-centro');
editButtons.forEach(button => {
    addEditEvent(button);
});

/**
 * EVENTO CHANGE IMAGE 
 */
const event_change_image = (button) => {
    button.addEventListener("change", async (event) => {

        const logo = event.srcElement.files[0]
        if (logo) {
            document.querySelector('#img_logo').src = URL.createObjectURL(logo)
        } 
    })
}
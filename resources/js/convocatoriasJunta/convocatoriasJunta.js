import { DELETE_CONVOCATORIA_BBDD, UPDATE_CONVOCATORIA_BBDD, ADD_CONVOCATORIA_BBDD, VALIDATE_CONVOCATORIA_BBDD, CONVOCAR_CONVOCATORIA_BBDD } from "./axiosTemplate.js";
import Swal from 'sweetalert2';

let modal_add = null

document.addEventListener("DOMContentLoaded", async (event) => {
    modal_add = document.querySelector('#modal_add')
})

function renderHTMLConvocatoria(response){

    modal_add.querySelector('#idJunta').value=""
    modal_add.querySelector('#idTipo').value=""
    modal_add.querySelector('#lugar').value=""
    modal_add.querySelector('#fecha').value=""
    modal_add.querySelector('#hora').value=""
    modal_add.querySelector('#acta').value=""

    if(response){
        let modal_edit = modal_add.cloneNode(true);

        modal_edit.classList.remove('hidden')
        
        modal_edit.querySelector('#idJunta').value=response.idJunta
        modal_edit.querySelector('#idJunta').setAttribute('disabled', 'disabled')
        modal_edit.querySelector('#idJunta').classList.add('bg-red-50')
        modal_edit.querySelector('#idTipo').value=response.idTipo
        modal_edit.querySelector('#lugar').value=response.lugar
        modal_edit.querySelector('#fecha').value=response.fecha
        modal_edit.querySelector('#hora').value=response.hora

        if(response.deleted_at!=null || !permitirAcciones){
            modal_edit.querySelector('#idTipo').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#lugar').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#fecha').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#hora').setAttribute('disabled', 'disabled')
            modal_edit.querySelector('#acta').setAttribute('disabled', 'disabled')
        }
        return modal_edit        
    }
    else{
        modal_add.classList.remove('hidden')
        return modal_add
    }
}

const preConfirm = async(accion, id=null) => {

    let valores = {};

    const inputs = document.querySelectorAll(".convocatoria");
    inputs.forEach((input) => {
        if(input.id!='acta'){
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
            response = await ADD_CONVOCATORIA_BBDD(dataToSend)
            title="Añadido"
            text="Se ha añadido la convocatoria"
            break;
    
        case 'update':

            dataToSend = {
                id: id,
                accion: 'update',
                data: valores,
            };

            response = await VALIDATE_CONVOCATORIA_BBDD(dataToSend)

            if (response.status === 200) {
                response = await UPDATE_CONVOCATORIA_BBDD(dataToSend);
                title="Actualizado"
                text="Se ha actualizado la convocatoria"
            }
            break

        case 'delete':

            dataToSend = {
                id: id,
                accion: 'delete',
            }

            const result = await Swal.fire({
                title: "¿Seguro que quiere eliminar la convocatoria?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "",
                confirmButtonText: "Eliminar",
                toast: true,
                timerProgressBar: true,
                showConfirmButton: true,
                position: 'top-right',
                showLoaderOnConfirm:true,
                preConfirm: async () => {        
                    response = await DELETE_CONVOCATORIA_BBDD(dataToSend);
                    title="Eliminado"
                    text="Se ha eliminado la convocatoria"
                }
            })

            if(result.isDismissed){return false}
            break  
    }

    if (response.status === 200) {
        window.location.reload()
    } 
    else {
        Swal.showValidationMessage(response.errors)
        return false
    }
}

/**
 * EVENTO AÑADIR
 */
const addButton = document.querySelector('#btn-add-convocatoria');
if(addButton){
    addButton.addEventListener("click", async (event) => {
        await Swal.fire({
            title: "Añadir Convocatoria",
            html: renderHTMLConvocatoria(null),
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonText: "Añadir",
            cancelButtonText: "Cancelar",
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showLoaderOnConfirm:true,
            preConfirm: async () => preConfirm('add')
        })
    })
}

// EVENTO EDITAR Y ELIMINAR
const addEditEvent = (button) => {
    button.addEventListener("click", async (event) => {
        event.stopPropagation()
        try {
            let response = null

            for(let c in convocatorias.data){
                if(convocatorias.data[c].id==button.dataset.convocatoriaId){
                    response = convocatorias.data[c]
                    break;
                }
            }
            if(!response)
                throw "Error, convocatoria no encontrada"

            await Swal.fire({
                title: response && response.deleted_at!=null ? 'Convocatoria eliminada' : permitirAcciones ? 'Editar Convocatoria' : 'Convocatoria',
                html: renderHTMLConvocatoria(response),
                focusConfirm: false,
                showDenyButton: (response && response.deleted_at!=null) || !permitirAcciones ? false : true,
                showCancelButton: response && response.deleted_at!=null || !permitirAcciones ? false : true,
                showConfirmButton: response && response.deleted_at!=null || !permitirAcciones ? false : true,
                denyButtonText: 'Eliminar',
                confirmButtonText: "Actualizar",
                cancelButtonText: "Cancelar",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '',
                denyButtonColor: '#d33',
                showLoaderOnConfirm:true,
                showLoaderOnDeny:true,
                preConfirm: async () => preConfirm('update', button.dataset.convocatoriaId),
                preDeny: permitirAcciones ? async () => preConfirm('delete', button.dataset.convocatoriaId) : null,
            });

        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con la convocatoria.",
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-right',
            });
        }
    });
};

const renderHTMLConvocados = (convocados, tipo) => {

    let doc = null

    if(convocados && convocados.length){

        let html =`
            <div class="relative overflow-x-auto">
            <table class="w-full text-md text-left rtl:text-left text-gray-500 dark:text-gray-400">
                <thead class="text-md text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-1 py-1">
                            Nombre
                        </th>
                        <th scope="col" class="px-1 py-1">
                            ${tipo=='notificados'? 'Email' : 'Representación'}
                        </th>
                        <th scope="col" class="px-1 py-1 text-center">
                            ${tipo=='notificados'? 'Notificado' : 'Confirmado'}
                        </th>
                    </tr>
                </thead>
                <tbody>
            
                </tbody>
            </table>
        </div>
        `
        const domparser = new DOMParser()
        doc = domparser.parseFromString(html, 'text/html')
        let tbody = doc.querySelector('tbody')

        convocados.forEach(miembro => {
            let miembro_tr = document.createElement("tr");
            let miembro_th_name = document.createElement("th");
            miembro_th_name.classList.add('px-1', 'py-1');
            miembro_th_name.innerHTML=miembro.usuario.name
            miembro_tr.appendChild(miembro_th_name);

            switch(tipo){
                case 'notificados':
                    let miembro_td_email = document.createElement("td");
                    miembro_td_email.classList.add('px-1', 'py-1');
                    miembro_td_email.innerHTML = miembro.usuario.email
                    miembro_tr.appendChild(miembro_td_email);      
                    break;
                case 'asistentes':
                    let miembro_td_representacion = document.createElement("td");
                    miembro_td_representacion.classList.add('px-1', 'py-1');
                    miembro_td_representacion.innerHTML = miembro.usuario.miembros_junta[0].representacion.nombre
                    miembro_tr.appendChild(miembro_td_representacion);
                    break;
            }

            let miembro_td_estado = document.createElement("td");
            miembro_td_estado.classList.add('px-1', 'py-1', 'text-center');

            let estadoHTML = ''
            if(miembro.notificado || miembro.asiste){
                estadoHTML=`
                <span class="material-icons-round text-green-400">
                        check_circle
                </span>
                `
            }
            else{
                estadoHTML=`
                <span class="material-icons-round text-red-400">
                        cancel
                </span>
                `
            }

            miembro_td_estado.innerHTML = estadoHTML
            miembro_tr.appendChild(miembro_td_estado);
            
            tbody.appendChild(miembro_tr);
        })
    }
    else{
        let mensaje = ''

        switch(tipo){
            case 'notificados':
                mensaje = 'No existen notificados'
                break;
            case 'asistentes':
                mensaje = 'No existen asistentes'
                break;
        }

        let mensaje_div = document.createElement("div");
        mensaje_div.innerHTML=mensaje
        return mensaje_div  
    }

    return doc.querySelector('table')
}

/**
 * NOTIFICAR
 */
const notificarEvent = (button) => {
    button.addEventListener('click', async (event) => {
        event.stopPropagation()

        let response = null

            for(let c in convocatorias.data){
                if(convocatorias.data[c].id==button.dataset.convocatoriaId){
                    response = convocatorias.data[c]
                    break;
                }
            }
            if(!response)
                throw "Error, convocatoria no encontrada"

        const dataToSend = {
            id:button.dataset.convocatoriaId,
            idJunta: response.idJunta,
            notificado: null,
            asiste:null
        }

        const convocados = await CONVOCAR_CONVOCATORIA_BBDD(dataToSend)

        try {
            await Swal.fire({
                title:'Notificar',
                html: renderHTMLConvocados(convocados, 'notificados'),
                focusConfirm: false,
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: "Enviar email",
                cancelButtonText: "Cancelar",
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '',
                showLoaderOnConfirm:true,
                width: '70vw',
                heightAuto:false, 
                customClass: 'swal-height',
                preConfirm: async () => {

                    const dataToSend = {
                        id:button.dataset.convocatoriaId,
                        idJunta: response.idJunta,
                        notificado: null,
                        asiste:null,
                        notificar:true
                    }
            
                    const notificados = await CONVOCAR_CONVOCATORIA_BBDD(dataToSend)

                    if(notificados.status == 200){
                        window.location.reload()
                    }
                    else{
                        Swal.showValidationMessage(response.errors)
                        return false
                    }

                },
            });
        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con las notificaciones.",
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-right',
            });
        }
    }, true)
}

/**
 * ASISTENTES
 */
const asistentesEvent = (button) => {
    button.addEventListener('click', async (event) => {
        event.stopPropagation()

        let response = null

            for(let c in convocatorias.data){
                if(convocatorias.data[c].id==button.dataset.convocatoriaId){
                    response = convocatorias.data[c]
                    break;
                }
            }
            if(!response)
                throw "Error, convocatoria no encontrada"

        const dataToSend = {
            id:button.dataset.convocatoriaId,
            idJunta: response.idJunta,
            notificado: null,
            asiste:null
        }

        const convocados = await CONVOCAR_CONVOCATORIA_BBDD(dataToSend)
        try {
            await Swal.fire({
                title:'Asistentes convocatoria',
                html: renderHTMLConvocados(convocados, 'asistentes'),
                showConfirmButton:false,
                width: '70vw',
                heightAuto:false, 
                customClass: 'swal-height' 
            });
        } catch (error) {
            await Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Ha ocurrido un error al realizar una operación con los asistentes.",
                toast: true,
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false,
                position: 'top-right',
            });
        }
    }, true)    
}

/**
 * ACTAS
 */
const actasEvent = (button) => {
    button.addEventListener('click', async (event) => {
        event.stopPropagation()

        await Swal.fire({
            html: `
                <iframe src="${button.dataset.acta}" class="w-full h-full"></iframe>
            `,
            focusConfirm: false,
            showConfirmButton: false,
            width: '90vw',
            heightAuto:false, 
            customClass: 'swal-height'   
        })
    }, true)    
}

const editButtons = document.querySelectorAll('#btn-editar-convocatoria');

editButtons.forEach(button => {
    addEditEvent(button);
});

const notificarButtons = document.querySelectorAll('#btn-notificar');

notificarButtons.forEach(button => {
    notificarEvent(button);
});

const asistentesButtons = document.querySelectorAll('#btn-asistentes');

asistentesButtons.forEach(button => {
    asistentesEvent(button);
});

const actasButtons = document.querySelectorAll('#btn-visualizar-acta');

actasButtons.forEach(button => {
    actasEvent(button);
});


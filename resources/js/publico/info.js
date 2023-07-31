import {GETBYCENTRO_MIEMBROSGOBIERNO_BBDD } from "../miembrosGobierno/axiosTemplate"
import {GETBYCENTRO_MIEMBROSJUNTA_BBDD } from "../miembrosJunta/axiosTemplate"
import {GET_CENTRO_BBDD} from '../centros/axiosTemplate';

import Swal from 'sweetalert2';

document.addEventListener("DOMContentLoaded", async (event) => {
    const dataToSend = {
        id: 1,
    };
  
    try {
        const gobierno = await GETBYCENTRO_MIEMBROSGOBIERNO_BBDD(dataToSend);
        const junta = await GETBYCENTRO_MIEMBROSJUNTA_BBDD(dataToSend);
        const centro = await GET_CENTRO_BBDD(dataToSend); 

        console.log(junta);

        var arrayOrgChart = [];
        var cont=5;

        arrayOrgChart.push({
            id:0,
            name: centro.nombre,
            title: centro.direccion,
            photo: asset_global + '/ciencias.png'
        });
    
        arrayOrgChart.push({
            id:1,
            pid:0,
            name: 'Equipo de Gobierno',
            photo: asset_global + '/gobierno.png'
        });
    
        arrayOrgChart.push({
            id:2,
            pid:0,
            name: 'Junta de Facultad/Escuela',
            photo: asset_global + '/junta.png'
        });

        arrayOrgChart.push({
            id:3,
            pid:0,
            name: 'Comisiones',
            photo: asset_global + '/comision.png'
        });

        arrayOrgChart.push({
            id:4,
            pid:3,
            name: 'Convocatorias',
            photo: asset_global + '/convocatoria.png'
        });
    
        gobierno['miembros'].forEach(m => {
    
                arrayOrgChart.push({
                    id:cont,
                    pid: 1,
                    name: m.name,
                    title: m.nombre,
                    email: m.email,
                    photo: 'https://cdn.balkan.app/shared/'+cont+'.jpg'
                });
            
            cont=cont+1;
        });

        junta['miembros'].forEach(m => {
    
            arrayOrgChart.push({
                id:cont,
                pid: 2,
                name: m.name,
                title: m.nombre,
                email: m.email,
                photo: 'https://cdn.balkan.app/shared/'+cont+'.jpg'
            });
        
        cont=cont+1;
    });
    
       
    
        // Pintar gráfico con librería OrgChart JS
   
        var chart = new OrgChart(document.getElementById("equipoGobierno"), {
            template: "diva",
            nodeMouseClick: OrgChart.action.none,
            mouseScrool: OrgChart.action.none,
            enableSearch: false,
    
            nodeBinding: {
                field_0: "name",
                field_1: "title",
                field_2: "email",
                img_0: "photo",
            },
        });
    
        chart.load(arrayOrgChart);
    
    }
    catch (error) {
        console.error(error);
        await Swal.fire({
            icon: "error",
            title: "Oops...",
            text: "Ha ocurrido un error al obtener datos del centro.",
        });
    }
});
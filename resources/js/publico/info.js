import {GETBYCENTRO_MIEMBROSGOBIERNO_BBDD } from "../miembrosGobierno/axiosTemplate"
import {GETBYCENTRO_MIEMBROSJUNTA_BBDD } from "../miembrosJunta/axiosTemplate"
import {GET_CENTRO_BBDD} from '../centros/axiosTemplate';
import {REPRESENTACIONES, TIPOS_CENTRO} from '../constants'


import Swal from 'sweetalert2';

document.addEventListener("DOMContentLoaded", async (event) => {

    const idCentro = document.getElementById('idCentro').innerHTML;

    const dataToSend = {
        id: idCentro,
    };
  
    try {
        const gobierno = await GETBYCENTRO_MIEMBROSGOBIERNO_BBDD(dataToSend);
        const junta = await GETBYCENTRO_MIEMBROSJUNTA_BBDD(dataToSend);
        const centro = await GET_CENTRO_BBDD(dataToSend); 

        console.log(centro);

        var arrayOrgChart = [];
        var cont=10;

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
            name: 'Junta de '+ centro.tipo,
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

        arrayOrgChart.push({
            id:5,
            pid:2,
            name: 'Representantes',
            title: 'Profesorado permanente',
            photo: asset_global + '/miembro_junta.png'
        });

        arrayOrgChart.push({
            id:6,
            pid:2,
            name: 'Representantes',
            title: 'Otro personal docente',
            photo: asset_global + '/miembro_junta.png'
        });

        arrayOrgChart.push({
            id:7,
            pid:2,
            name: 'Representantes',
            title: 'PAS',
            photo: asset_global + '/miembro_junta.png'
        });

        arrayOrgChart.push({
            id:8,
            pid:2,
            name: 'Representantes',
            title: 'Alumnado',
            photo: asset_global + '/miembro_junta.png'
        });

        arrayOrgChart.push({
            id:9,
            pid:2,
            name: 'Libre',
            title: 'Designados por el Director',
            photo: asset_global + '/miembro_junta.png'
        });

    
        gobierno['miembros'].forEach(m => {

            var representacion = m.nombre;

            if(m.idRepresentacion == REPRESENTACIONES['GOBIERNO']['DIRECTOR']){
                if(centro.idTipo == TIPOS_CENTRO['FACULTAD']){
                    representacion='Decano/a';
                }
                else if(centro.idTipo == TIPOS_CENTRO['ESCUELA'] || centro.idTipo == TIPOS_CENTRO['OTRO']){
                    representacion='Director/a';
                }
            }
    
            arrayOrgChart.push({
                id:cont,
                pid: 1,
                name: m.name,
                title: representacion,
                email: m.email,
                photo: 'https://cdn.balkan.app/shared/'+cont+'.jpg'
            });
            
            cont=cont+1;
        });

        junta['miembros'].forEach(m => {
    
            var pid=2;

            switch(m.idRepresentacion){
                case REPRESENTACIONES['GENERAL']['DOCENTE']:
                    pid=5;
                    break;
                case REPRESENTACIONES['GENERAL']['DOCENTE_OTRO']:
                    pid=6;
                    break;
                case REPRESENTACIONES['GENERAL']['PAS']:
                    pid=7;
                break;
                case REPRESENTACIONES['GENERAL']['ALUMNADO']:
                    pid=8;
                break;
                case REPRESENTACIONES['GENERAL']['LIBRE']:
                    pid=9;
                break;
                default:
                    pid=2;
            }

            arrayOrgChart.push({
                id:cont,
                pid: pid,
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
            //mouseScrool: OrgChart.action.none,
            enableSearch: false,
            scaleInitial: 0.8,
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
import {GETBYCENTRO_MIEMBROSGOBIERNO_BBDD } from "../miembrosGobierno/axiosTemplate"
import {GET_CENTRO_BBDD} from '../centros/axiosTemplate';

import Swal from 'sweetalert2';

document.addEventListener("DOMContentLoaded", async (event) => {
  const dataToSend = {
    id: 1,
  };
  
  try {
  
      const miembros = await GETBYCENTRO_MIEMBROSGOBIERNO_BBDD(dataToSend);
      const centro = await GET_CENTRO_BBDD(dataToSend); 
      console.log(centro);
      var arrayOrgChart = [];
      var cont=1;
  
      arrayOrgChart.push({
          id:1,
          Name: 'Equipo de Gobierno',
          Title: centro.nombre,
      });
  
      arrayOrgChart.push({
        id:2,
        Name: 'Junta de Facultad/Escuela',
        Title: centro.nombre,
    });
  
      miembros['miembros'].forEach(m => {
  
            arrayOrgChart.push({
                id:m.id,
                pid: 1,
                Name: m.name,
                Title: m.nombre,
                Email: m.email,
                Photo: 'https://cdn.balkan.app/shared/'+cont+'.jpg'
            });
        
        cont=cont+1;
      });
  
      console.log(arrayOrgChart)
  
      // Pintar gráfico con librería OrgChart JS
  
      var chart = new OrgChart(document.getElementById("equipoGobierno"), {
        template: "ula",
        nodeMouseClick: OrgChart.action.none,
        mouseScrool: OrgChart.action.none,
        enableSearch: false,
  
        nodeBinding: {
            field_0: "Name",
            field_1: "Title",
            field_2: "Email",
            img_0: "Photo"
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
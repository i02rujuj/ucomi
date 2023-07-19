const selectJuntas = document.getElementById("idCentro");

selectJuntas.addEventListener("change", async (event) => {

    const idCentro = document.getElementById("idCentro").value;
    const idDirector = document.getElementById("idDirector");
    const idSecretario = document.getElementById("idSecretario");
    const nombreDirector = document.getElementById("nombreDirector");
    const nombreSecretario = document.getElementById("nombreSecretario");
    const errorDirector = document.getElementById("errorDirector");
    const errorSecretario = document.getElementById("errorSecretario");
    const errorDirectorFront = document.getElementById("errorDirectorFront");
    const errorSecretarioFront = document.getElementById("errorSecretarioFront");

    if(errorDirector)
        errorDirector.innerHTML="";
    if(errorSecretario)
        errorSecretario.innerHTML="";

    errorDirectorFront.innerHTML="";
    errorSecretarioFront.innerHTML="";

    $.ajax({
        type: "POST",
        url: '/miembroGobierno/getDirectivos',
        data: {
            _token: $("meta[name='csrf-token']").attr("content"),
            idCentro: idCentro,
        },
        success: function (response) {
            //director.value=response['director']?.id ?? "No existe directivo activo para el centro seleccionado";
            //secretario.value=response['secretario']?.id ?? "---";

            if(response['director'] && Object.hasOwn(response['director'], 'id')){
                idDirector.value=response['director'].id;
                nombreDirector.value=response['director'].name;
            }
            else{
                idDirector.value="";
                nombreDirector.value ="";
                errorDirectorFront.innerHTML = '<span class="text-red-500 text-xs mt-1">No existe actualmente director/decano para el centro seleccionado. <a class="inline-block w-full mt-1 md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" href="/miembrosGobierno?idCentro='+idCentro+'&idRepresentacion=1">Añadir director</a>';
            }

            if(response['secretario'] && Object.hasOwn(response['secretario'], 'id')){
                idSecretario.value=response['secretario'].id;
                nombreSecretario.value=response['secretario'].name;
            }
            else{
                idSecretario.value="";
                nombreSecretario.value = "";
                errorSecretarioFront.innerHTML = '<span class="text-red-500 text-xs mt-1">No existe actualmente secretario para el centro seleccionado. <a class="inline-block w-full mt-1 md:w-auto text-sm bg-blue-100 text-slate-600 border border-blue-200 font-medium hover:text-black py-1 px-4 rounded" href="/miembrosGobierno?idCentro='+idCentro+'&idRepresentacion=3">Añadir secretario</a>';
            }
        },
        error: function (errorMessage) {
            //$("#errorMessage").append("Error: " + errorMessage);
        }
    });
});
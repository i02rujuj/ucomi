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
                errorDirectorFront.innerHTML="No existe actualmente director/decano para el centro seleccionado";
            }

            if(response['secretario'] && Object.hasOwn(response['secretario'], 'id')){
                idSecretario.value=response['secretario'].id;
                nombreSecretario.value=response['secretario'].name;
            }
            else{
                idSecretario.value="";
                nombreSecretario.value = "";
                errorSecretarioFront.innerHTML = 'No existe actualmente secretario para el centro seleccionado';
            }
        },
        error: function (errorMessage) {
            //$("#errorMessage").append("Error: " + errorMessage);
        }
    });
});
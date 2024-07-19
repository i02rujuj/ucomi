const ENDPOINT_CONVOCATORIA_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete convocatorias",
    explanation: "This endpoint is used delete convocatorias of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_CONVOCATORIA_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get convocatorias",
    explanation: "This endpoint is used get convocatorias of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_CONVOCATORIA_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update convocatoria",
    explanation: "This endpoint is used update convocatoria of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    processData: false,
    contentType: false,
    headers: {
        //'content-type': 'application/json',
        'Content-Type': 'multipart/form-data'
    },
    data: [],
};

const ENDPOINT_CONVOCATORIA_GETALL_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los convocatorias",
    explanation: "This endpoint is used get all convocatorias of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_CONVOCATORIA_ADD_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint add convocatoria",
    explanation: "This endpoint is used add convocatoria of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    processData: false,
    contentType: false,
    headers: {
        //'content-type': 'application/json',
        'Content-Type': 'multipart/form-data'
    },
    data: [],
};

const ENDPOINT_CONVOCATORIA_VALIDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint validate convocatoria",
    explanation: "This endpoint is used validate convocatoria of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    processData: false,
    contentType: false,
    headers: {
        //'content-type': 'application/json',
        'Content-Type': 'multipart/form-data'
    },
    data: [],
};

const ENDPOINT_CONVOCATORIA_CONVOCAR_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los convocados de la convocatoria",
    explanation: "This endpoint is used get all convocados from convocatoria of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_CONVOCATORIA_ASISTIR_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint asistir convocatoria",
    explanation: "This endpoint is used asistir convocatoria of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_CONVOCATORIA_DELETE_BBDD,
    ENDPOINT_CONVOCATORIA_GET_BBDD,
    ENDPOINT_CONVOCATORIA_UPDATE_BBDD,
    ENDPOINT_CONVOCATORIA_GETALL_BBDD,
    ENDPOINT_CONVOCATORIA_ADD_BBDD,
    ENDPOINT_CONVOCATORIA_VALIDATE_BBDD,
    ENDPOINT_CONVOCATORIA_CONVOCAR_BBDD,
    ENDPOINT_CONVOCATORIA_ASISTIR_BBDD,
}
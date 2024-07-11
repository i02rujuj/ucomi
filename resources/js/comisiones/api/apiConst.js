const ENDPOINT_COMISION_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete comisiones",
    explanation: "This endpoint is used delete comisiones of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_COMISION_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get comisiones",
    explanation: "This endpoint is used get comisiones of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_COMISION_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update comisiones",
    explanation: "This endpoint is used update comisiones of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_COMISION_GETALL_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los comisiones",
    explanation: "This endpoint is used get all comisiones of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_COMISION_ADD_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los comisiones",
    explanation: "This endpoint is used add comisiones of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_COMISION_VALIDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los comisiones",
    explanation: "This endpoint is used validate comisiones of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_COMISION_DELETE_BBDD,
    ENDPOINT_COMISION_GET_BBDD,
    ENDPOINT_COMISION_UPDATE_BBDD,
    ENDPOINT_COMISION_GETALL_BBDD,
    ENDPOINT_COMISION_ADD_BBDD,
    ENDPOINT_COMISION_VALIDATE_BBDD,
}
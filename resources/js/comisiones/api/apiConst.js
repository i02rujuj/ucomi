const ENDPOINT_COMISION_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete comisión",
    explanation: "This endpoint is used delete comisión of the server",
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
    comment: "Endpoint get comisión",
    explanation: "This endpoint is used get comisión of the server",
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
    comment: "Endpoint update comisión",
    explanation: "This endpoint is used update comisión of the server",
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
    comment: "Endpoint obtener todos los comisións",
    explanation: "This endpoint is used get all comisións of the server",
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
    ENDPOINT_COMISION_GETALL_BBDD
}
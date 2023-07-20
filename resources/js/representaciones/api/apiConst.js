const ENDPOINT_REPRESENTACION_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete representación",
    explanation: "This endpoint is used delete representación of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_REPRESENTACION_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get representación",
    explanation: "This endpoint is used get representación of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_REPRESENTACION_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update user",
    explanation: "This endpoint is used update representación of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_REPRESENTACION_GETALL_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los representaciones",
    explanation: "This endpoint is used get all representaciones of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_REPRESENTACION_DELETE_BBDD,
    ENDPOINT_REPRESENTACION_GET_BBDD,
    ENDPOINT_REPRESENTACION_UPDATE_BBDD,
    ENDPOINT_REPRESENTACION_GETALL_BBDD
}
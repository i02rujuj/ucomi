const ENDPOINT_CENTRO_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete centro",
    explanation: "This endpoint is used delete centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_CENTRO_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get centro",
    explanation: "This endpoint is used get centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_CENTRO_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update centro",
    explanation: "This endpoint is used update centro of the server",
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

const ENDPOINT_CENTRO_ADD_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint añadir un centro",
    explanation: "This endpoint is used to save a centro of the server",
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

const ENDPOINT_CENTRO_VALIDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint validate centro",
    explanation: "This endpoint is used validate centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_CENTRO_DELETE_BBDD,
    ENDPOINT_CENTRO_GET_BBDD,
    ENDPOINT_CENTRO_UPDATE_BBDD,
    ENDPOINT_CENTRO_ADD_BBDD,
    ENDPOINT_CENTRO_VALIDATE_BBDD,
}
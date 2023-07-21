const ENDPOINT_JUNTA_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete junta",
    explanation: "This endpoint is used delete junta of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_JUNTA_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get junta",
    explanation: "This endpoint is used get junta of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_JUNTA_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update junta",
    explanation: "This endpoint is used update junta of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_JUNTA_GETALL_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los juntas",
    explanation: "This endpoint is used get all juntas of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_JUNTA_DELETE_BBDD,
    ENDPOINT_JUNTA_GET_BBDD,
    ENDPOINT_JUNTA_UPDATE_BBDD,
    ENDPOINT_JUNTA_GETALL_BBDD
}
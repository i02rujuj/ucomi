const ENDPOINT_MIEMBROSGOBIERNO_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete miembro gobierno",
    explanation: "This endpoint is used delete miembro gobierno of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSGOBIERNO_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get miembro gobierno",
    explanation: "This endpoint is used get miembro gobierno of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSGOBIERNO_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update miembro gobierno",
    explanation: "This endpoint is used update miembro gobierno of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSGOBIERNO_GETBYCENTRO_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los miembros gobierno por centro",
    explanation: "This endpoint is used get all miembros gobierno por centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSGOBIERNO_ADD_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint add miembros gobierno por centro",
    explanation: "This endpoint is used add miembros gobierno por centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSGOBIERNO_VALIDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint add miembros gobierno por centro",
    explanation: "This endpoint is used validate miembros gobierno por centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_MIEMBROSGOBIERNO_DELETE_BBDD,
    ENDPOINT_MIEMBROSGOBIERNO_GET_BBDD,
    ENDPOINT_MIEMBROSGOBIERNO_UPDATE_BBDD,
    ENDPOINT_MIEMBROSGOBIERNO_GETBYCENTRO_BBDD,
    ENDPOINT_MIEMBROSGOBIERNO_ADD_BBDD,
    ENDPOINT_MIEMBROSGOBIERNO_VALIDATE_BBDD,
}
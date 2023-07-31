const ENDPOINT_MIEMBROSJUNTA_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete miembro junta",
    explanation: "This endpoint is used delete miembro junta of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSJUNTA_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get miembro junta",
    explanation: "This endpoint is used get miembro junta of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSJUNTA_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update miembro junta",
    explanation: "This endpoint is used update miembro junta of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSJUNTA_GETBYCENTRO_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los miembros junta por centro",
    explanation: "This endpoint is used get all miebros junta por centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_MIEMBROSJUNTA_DELETE_BBDD,
    ENDPOINT_MIEMBROSJUNTA_GET_BBDD,
    ENDPOINT_MIEMBROSJUNTA_UPDATE_BBDD,
    ENDPOINT_MIEMBROSJUNTA_GETBYCENTRO_BBDD

}
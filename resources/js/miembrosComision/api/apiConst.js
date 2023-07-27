const ENDPOINT_MIEMBROSCOMISION_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete miembro comision",
    explanation: "This endpoint is used delete miembro comision of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSCOMISION_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get miembro comision",
    explanation: "This endpoint is used get miembro comision of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_MIEMBROSCOMISION_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update miembro comision",
    explanation: "This endpoint is used update miembro comision of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_MIEMBROSCOMISION_DELETE_BBDD,
    ENDPOINT_MIEMBROSCOMISION_GET_BBDD,
    ENDPOINT_MIEMBROSCOMISION_UPDATE_BBDD
}
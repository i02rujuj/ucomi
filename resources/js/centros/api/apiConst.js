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
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_CENTRO_DELETE_BBDD,
    ENDPOINT_CENTRO_GET_BBDD,
    ENDPOINT_CENTRO_UPDATE_BBDD
}
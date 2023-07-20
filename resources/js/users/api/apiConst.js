const ENDPOINT_USER_DELETE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint delete user",
    explanation: "This endpoint is used delete user of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_USER_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get user",
    explanation: "This endpoint is used get user of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_USER_UPDATE_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint update user",
    explanation: "This endpoint is used update user of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

const ENDPOINT_USER_GETALL_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint obtener todos los users",
    explanation: "This endpoint is used get all users of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_USER_DELETE_BBDD,
    ENDPOINT_USER_GET_BBDD,
    ENDPOINT_USER_UPDATE_BBDD,
    ENDPOINT_USER_GETALL_BBDD
}
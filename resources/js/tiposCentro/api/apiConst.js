const ENDPOINT_TIPOSCENTRO_GET_BBDD = {
    method: 'POST',
    url: "",
    comment: "Endpoint get tipos centro",
    explanation: "This endpoint is used get tipos de centro of the server",
    transformResponse: (data) => JSON.parse(data),
    withCredentials: false,
    headers: {
        'content-type': 'application/json',
    },
    data: [],
};

export {
    ENDPOINT_TIPOSCENTRO_GET_BBDD,
}
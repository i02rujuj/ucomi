import { CENTRO_UPDATE_URL, CENTRO_GET_URL, CENTRO_DELETE_URL, CENTRO_ADD_URL, CENTRO_VALIDATE_URL } from "./api/endPoints";
import { UPDATE_CENTRO_AXIOS, GET_CENTRO_AXIOS, DELETE_CENTRO_AXIOS, ADD_CENTRO_AXIOS, VALIDATE_CENTRO_AXIOS } from "./api/apiSender";
import { ENDPOINT_CENTRO_UPDATE_BBDD, ENDPOINT_CENTRO_GET_BBDD, ENDPOINT_CENTRO_DELETE_BBDD, ENDPOINT_CENTRO_ADD_BBDD, ENDPOINT_CENTRO_VALIDATE_BBDD } from "./api/apiConst";

function DELETE_CENTRO_BBDD(data) {
    ENDPOINT_CENTRO_DELETE_BBDD.url = CENTRO_DELETE_URL;
    ENDPOINT_CENTRO_DELETE_BBDD.data = data;
    return DELETE_CENTRO_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GET_CENTRO_BBDD(data) {
    ENDPOINT_CENTRO_GET_BBDD.url = CENTRO_GET_URL;
    ENDPOINT_CENTRO_GET_BBDD.data = data;
    return GET_CENTRO_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function UPDATE_CENTRO_BBDD(data) {
    ENDPOINT_CENTRO_UPDATE_BBDD.url = CENTRO_UPDATE_URL;
    ENDPOINT_CENTRO_UPDATE_BBDD.data = data;
    return UPDATE_CENTRO_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function ADD_CENTRO_BBDD(data) {
    ENDPOINT_CENTRO_ADD_BBDD.url = CENTRO_ADD_URL;
    ENDPOINT_CENTRO_ADD_BBDD.data = data;
    return ADD_CENTRO_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function VALIDATE_CENTRO_BBDD(data) {
    ENDPOINT_CENTRO_VALIDATE_BBDD.url = CENTRO_VALIDATE_URL;
    ENDPOINT_CENTRO_VALIDATE_BBDD.data = data;
    return VALIDATE_CENTRO_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    DELETE_CENTRO_BBDD,
    GET_CENTRO_BBDD,
    UPDATE_CENTRO_BBDD,
    ADD_CENTRO_BBDD,
    VALIDATE_CENTRO_BBDD,
}
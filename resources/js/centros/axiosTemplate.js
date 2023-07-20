import { CENTRO_UPDATE_URL, CENTRO_GET_URL, CENTRO_DELETE_URL, CENTRO_GETALL_URL } from "./api/endPoints";
import { UPDATE_CENTRO_AXIOS, GET_CENTRO_AXIOS, DELETE_CENTRO_AXIOS, GETALL_CENTRO_AXIOS } from "./api/apiSender";
import { ENDPOINT_CENTRO_UPDATE_BBDD, ENDPOINT_CENTRO_GET_BBDD, ENDPOINT_CENTRO_DELETE_BBDD, ENDPOINT_CENTRO_GETALL_BBDD } from "./api/apiConst";

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

function GETALL_CENTRO_BBDD(data) {
    ENDPOINT_CENTRO_GETALL_BBDD.url = CENTRO_GETALL_URL;
    ENDPOINT_CENTRO_GETALL_BBDD.data = data;
    return GETALL_CENTRO_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    DELETE_CENTRO_BBDD,
    GET_CENTRO_BBDD,
    UPDATE_CENTRO_BBDD,
    GETALL_CENTRO_BBDD
}
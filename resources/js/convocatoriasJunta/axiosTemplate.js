import { CONVOCATORIA_UPDATE_URL, CONVOCATORIA_GET_URL, CONVOCATORIA_DELETE_URL, CONVOCATORIA_GETALL_URL, CONVOCATORIA_ADD_URL, CONVOCATORIA_VALIDATE_URL } from "./api/endPoints";
import { UPDATE_CONVOCATORIA_AXIOS, GET_CONVOCATORIA_AXIOS, DELETE_CONVOCATORIA_AXIOS, GETALL_CONVOCATORIA_AXIOS, ADD_CONVOCATORIA_AXIOS, VALIDATE_CONVOCATORIA_AXIOS } from "./api/apiSender";
import { ENDPOINT_CONVOCATORIA_UPDATE_BBDD, ENDPOINT_CONVOCATORIA_GET_BBDD, ENDPOINT_CONVOCATORIA_DELETE_BBDD, ENDPOINT_CONVOCATORIA_GETALL_BBDD, ENDPOINT_CONVOCATORIA_ADD_BBDD, ENDPOINT_CONVOCATORIA_VALIDATE_BBDD } from "./api/apiConst";

function DELETE_CONVOCATORIA_BBDD(data) {
    ENDPOINT_CONVOCATORIA_DELETE_BBDD.url = CONVOCATORIA_DELETE_URL;
    ENDPOINT_CONVOCATORIA_DELETE_BBDD.data = data;
    return DELETE_CONVOCATORIA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GET_CONVOCATORIA_BBDD(data) {
    ENDPOINT_CONVOCATORIA_GET_BBDD.url = CONVOCATORIA_GET_URL;
    ENDPOINT_CONVOCATORIA_GET_BBDD.data = data;
    return GET_CONVOCATORIA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function UPDATE_CONVOCATORIA_BBDD(data) {
    ENDPOINT_CONVOCATORIA_UPDATE_BBDD.url = CONVOCATORIA_UPDATE_URL;
    ENDPOINT_CONVOCATORIA_UPDATE_BBDD.data = data;
    return UPDATE_CONVOCATORIA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GETALL_CONVOCATORIA_BBDD(data) {
    ENDPOINT_CONVOCATORIA_GETALL_BBDD.url = CONVOCATORIA_GETALL_URL;
    ENDPOINT_CONVOCATORIA_GETALL_BBDD.data = data;
    return GETALL_CONVOCATORIA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function ADD_CONVOCATORIA_BBDD(data) {
    ENDPOINT_CONVOCATORIA_ADD_BBDD.url = CONVOCATORIA_ADD_URL;
    ENDPOINT_CONVOCATORIA_ADD_BBDD.data = data;
    return ADD_CONVOCATORIA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function VALIDATE_CONVOCATORIA_BBDD(data) {
    ENDPOINT_CONVOCATORIA_VALIDATE_BBDD.url = CONVOCATORIA_VALIDATE_URL;
    ENDPOINT_CONVOCATORIA_VALIDATE_BBDD.data = data;
    return VALIDATE_CONVOCATORIA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    DELETE_CONVOCATORIA_BBDD,
    GET_CONVOCATORIA_BBDD,
    UPDATE_CONVOCATORIA_BBDD,
    GETALL_CONVOCATORIA_BBDD,
    ADD_CONVOCATORIA_BBDD,
    VALIDATE_CONVOCATORIA_BBDD,
}
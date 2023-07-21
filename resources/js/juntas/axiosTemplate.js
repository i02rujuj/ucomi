import { JUNTA_UPDATE_URL, JUNTA_GET_URL, JUNTA_DELETE_URL, JUNTA_GETALL_URL } from "./api/endPoints";
import { UPDATE_JUNTA_AXIOS, GET_JUNTA_AXIOS, DELETE_JUNTA_AXIOS, GETALL_JUNTA_AXIOS } from "./api/apiSender";
import { ENDPOINT_JUNTA_UPDATE_BBDD, ENDPOINT_JUNTA_GET_BBDD, ENDPOINT_JUNTA_DELETE_BBDD, ENDPOINT_JUNTA_GETALL_BBDD } from "./api/apiConst";

function DELETE_JUNTA_BBDD(data) {
    ENDPOINT_JUNTA_DELETE_BBDD.url = JUNTA_DELETE_URL;
    ENDPOINT_JUNTA_DELETE_BBDD.data = data;
    return DELETE_JUNTA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GET_JUNTA_BBDD(data) {
    ENDPOINT_JUNTA_GET_BBDD.url = JUNTA_GET_URL;
    ENDPOINT_JUNTA_GET_BBDD.data = data;
    return GET_JUNTA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function UPDATE_JUNTA_BBDD(data) {
    ENDPOINT_JUNTA_UPDATE_BBDD.url = JUNTA_UPDATE_URL;
    ENDPOINT_JUNTA_UPDATE_BBDD.data = data;
    return UPDATE_JUNTA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GETALL_JUNTA_BBDD(data) {
    ENDPOINT_JUNTA_GETALL_BBDD.url = JUNTA_GETALL_URL;
    ENDPOINT_JUNTA_GETALL_BBDD.data = data;
    return GETALL_JUNTA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    DELETE_JUNTA_BBDD,
    GET_JUNTA_BBDD,
    UPDATE_JUNTA_BBDD,
    GETALL_JUNTA_BBDD
}
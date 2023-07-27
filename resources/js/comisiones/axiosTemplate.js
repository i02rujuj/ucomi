import { COMISION_UPDATE_URL, COMISION_GET_URL, COMISION_DELETE_URL, COMISION_GETALL_URL } from "./api/endPoints";
import { UPDATE_COMISION_AXIOS, GET_COMISION_AXIOS, DELETE_COMISION_AXIOS, GETALL_COMISION_AXIOS } from "./api/apiSender";
import { ENDPOINT_COMISION_UPDATE_BBDD, ENDPOINT_COMISION_GET_BBDD, ENDPOINT_COMISION_DELETE_BBDD, ENDPOINT_COMISION_GETALL_BBDD } from "./api/apiConst";

function DELETE_COMISION_BBDD(data) {
    ENDPOINT_COMISION_DELETE_BBDD.url = COMISION_DELETE_URL;
    ENDPOINT_COMISION_DELETE_BBDD.data = data;
    return DELETE_COMISION_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GET_COMISION_BBDD(data) {
    ENDPOINT_COMISION_GET_BBDD.url = COMISION_GET_URL;
    ENDPOINT_COMISION_GET_BBDD.data = data;
    return GET_COMISION_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function UPDATE_COMISION_BBDD(data) {
    ENDPOINT_COMISION_UPDATE_BBDD.url = COMISION_UPDATE_URL;
    ENDPOINT_COMISION_UPDATE_BBDD.data = data;
    return UPDATE_COMISION_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GETALL_COMISION_BBDD(data) {
    ENDPOINT_COMISION_GETALL_BBDD.url = COMISION_GETALL_URL;
    ENDPOINT_COMISION_GETALL_BBDD.data = data;
    return GETALL_COMISION_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    DELETE_COMISION_BBDD,
    GET_COMISION_BBDD,
    UPDATE_COMISION_BBDD,
    GETALL_COMISION_BBDD
}
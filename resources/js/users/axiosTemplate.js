import { USER_UPDATE_URL, USER_GET_URL, USER_DELETE_URL, USER_GETALL_URL } from "./api/endPoints";
import { UPDATE_USER_AXIOS, GET_USER_AXIOS, DELETE_USER_AXIOS, GETALL_USER_AXIOS } from "./api/apiSender";
import { ENDPOINT_USER_UPDATE_BBDD, ENDPOINT_USER_GET_BBDD, ENDPOINT_USER_DELETE_BBDD, ENDPOINT_USER_GETALL_BBDD } from "./api/apiConst";

function DELETE_USER_BBDD(data) {
    ENDPOINT_USER_DELETE_BBDD.url = USER_DELETE_URL;
    ENDPOINT_USER_DELETE_BBDD.data = data;
    return DELETE_USER_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GET_USER_BBDD(data) {
    ENDPOINT_USER_GET_BBDD.url = USER_GET_URL;
    ENDPOINT_USER_GET_BBDD.data = data;
    return GET_USER_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function UPDATE_USER_BBDD(data) {
    ENDPOINT_USER_UPDATE_BBDD.url = USER_UPDATE_URL;
    ENDPOINT_USER_UPDATE_BBDD.data = data;
    return UPDATE_USER_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GETALL_USER_BBDD(data) {
    ENDPOINT_USER_GETALL_BBDD.url = USER_GETALL_URL;
    ENDPOINT_USER_GETALL_BBDD.data = data;
    return GETALL_USER_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    DELETE_USER_BBDD,
    GET_USER_BBDD,
    UPDATE_USER_BBDD,
    GETALL_USER_BBDD
}
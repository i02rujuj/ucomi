import { MIEMBROSJUNTA_UPDATE_URL, MIEMBROSJUNTA_GET_URL, MIEMBROSJUNTA_DELETE_URL } from "./api/endPoints";
import { UPDATE_MIEMBROSJUNTA_AXIOS, GET_MIEMBROSJUNTA_AXIOS, DELETE_MIEMBROSJUNTA_AXIOS } from "./api/apiSender";
import { ENDPOINT_MIEMBROSJUNTA_UPDATE_BBDD, ENDPOINT_MIEMBROSJUNTA_GET_BBDD, ENDPOINT_MIEMBROSJUNTA_DELETE_BBDD } from "./api/apiConst";

function DELETE_MIEMBROSJUNTA_BBDD(data) {
    ENDPOINT_MIEMBROSJUNTA_DELETE_BBDD.url = MIEMBROSJUNTA_DELETE_URL;
    ENDPOINT_MIEMBROSJUNTA_DELETE_BBDD.data = data;
    return DELETE_MIEMBROSJUNTA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function GET_MIEMBROSJUNTA_BBDD(data) {
    ENDPOINT_MIEMBROSJUNTA_GET_BBDD.url = MIEMBROSJUNTA_GET_URL;
    ENDPOINT_MIEMBROSJUNTA_GET_BBDD.data = data;
    return GET_MIEMBROSJUNTA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

function UPDATE_MIEMBROSJUNTA_BBDD(data) {
    ENDPOINT_MIEMBROSJUNTA_UPDATE_BBDD.url = MIEMBROSJUNTA_UPDATE_URL;
    ENDPOINT_MIEMBROSJUNTA_UPDATE_BBDD.data = data;
    return UPDATE_MIEMBROSJUNTA_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    DELETE_MIEMBROSJUNTA_BBDD,
    GET_MIEMBROSJUNTA_BBDD,
    UPDATE_MIEMBROSJUNTA_BBDD
}
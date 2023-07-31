import axios from "axios";

import { ENDPOINT_MIEMBROSJUNTA_UPDATE_BBDD,ENDPOINT_MIEMBROSJUNTA_GET_BBDD, ENDPOINT_MIEMBROSJUNTA_DELETE_BBDD, ENDPOINT_MIEMBROSJUNTA_GETBYCENTRO_BBDD} from "./apiConst";

function DELETE_MIEMBROSJUNTA_AXIOS() {
    return axios(ENDPOINT_MIEMBROSJUNTA_DELETE_BBDD);
}

function GET_MIEMBROSJUNTA_AXIOS() {
    return axios(ENDPOINT_MIEMBROSJUNTA_GET_BBDD);
}

function UPDATE_MIEMBROSJUNTA_AXIOS() {
    return axios(ENDPOINT_MIEMBROSJUNTA_UPDATE_BBDD);
}

function GETBYCENTRO_MIEMBROSJUNTA_AXIOS() {
    return axios(ENDPOINT_MIEMBROSJUNTA_GETBYCENTRO_BBDD);
}

export {
    DELETE_MIEMBROSJUNTA_AXIOS,
    GET_MIEMBROSJUNTA_AXIOS,
    UPDATE_MIEMBROSJUNTA_AXIOS,
    GETBYCENTRO_MIEMBROSJUNTA_AXIOS
}
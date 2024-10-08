import axios from "axios";

import { ENDPOINT_CONVOCATORIA_UPDATE_BBDD,ENDPOINT_CONVOCATORIA_GET_BBDD, ENDPOINT_CONVOCATORIA_DELETE_BBDD, ENDPOINT_CONVOCATORIA_GETALL_BBDD, ENDPOINT_CONVOCATORIA_ADD_BBDD, ENDPOINT_CONVOCATORIA_VALIDATE_BBDD, ENDPOINT_CONVOCATORIA_CONVOCAR_BBDD, ENDPOINT_CONVOCATORIA_ASISTIR_BBDD} from "./apiConst";

function DELETE_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_DELETE_BBDD);
}

function GET_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_GET_BBDD);
}

function UPDATE_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_UPDATE_BBDD);
}

function GETALL_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_GETALL_BBDD);
}

function ADD_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_ADD_BBDD);
}

function VALIDATE_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_VALIDATE_BBDD);
}

function CONVOCAR_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_CONVOCAR_BBDD);
}

function ASISTIR_CONVOCATORIA_AXIOS() {
    return axios(ENDPOINT_CONVOCATORIA_ASISTIR_BBDD);
}

export {
    DELETE_CONVOCATORIA_AXIOS,
    GET_CONVOCATORIA_AXIOS,
    UPDATE_CONVOCATORIA_AXIOS,
    GETALL_CONVOCATORIA_AXIOS,
    ADD_CONVOCATORIA_AXIOS,
    VALIDATE_CONVOCATORIA_AXIOS,
    CONVOCAR_CONVOCATORIA_AXIOS,
    ASISTIR_CONVOCATORIA_AXIOS,
}
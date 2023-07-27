import axios from "axios";

import { ENDPOINT_COMISION_UPDATE_BBDD,ENDPOINT_COMISION_GET_BBDD, ENDPOINT_COMISION_DELETE_BBDD, ENDPOINT_COMISION_GETALL_BBDD} from "./apiConst";

function DELETE_COMISION_AXIOS() {
    return axios(ENDPOINT_COMISION_DELETE_BBDD);
}

function GET_COMISION_AXIOS() {
    return axios(ENDPOINT_COMISION_GET_BBDD);
}

function UPDATE_COMISION_AXIOS() {
    return axios(ENDPOINT_COMISION_UPDATE_BBDD);
}

function GETALL_COMISION_AXIOS() {
    return axios(ENDPOINT_COMISION_GETALL_BBDD);
}

export {
    DELETE_COMISION_AXIOS,
    GET_COMISION_AXIOS,
    UPDATE_COMISION_AXIOS,
    GETALL_COMISION_AXIOS
}
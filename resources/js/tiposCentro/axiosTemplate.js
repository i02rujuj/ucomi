import {TIPOSCENTRO_GET_URL} from "./api/endPoints";
import { GET_TIPOSCENTRO_AXIOS } from "./api/apiSender";
import { ENDPOINT_TIPOSCENTRO_GET_BBDD} from "./api/apiConst";

function GET_TIPOSCENTRO_BBDD(data) {
    ENDPOINT_TIPOSCENTRO_GET_BBDD.url = TIPOSCENTRO_GET_URL;
    ENDPOINT_TIPOSCENTRO_GET_BBDD.data = data;
    return GET_TIPOSCENTRO_AXIOS().then((response) => {
        return response.data;
    }).catch((error) => {
        return error;
    });
}

export {
    GET_TIPOSCENTRO_BBDD,
}
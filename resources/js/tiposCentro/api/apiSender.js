import axios from "axios";

import { ENDPOINT_TIPOSCENTRO_GET_BBDD} from "./apiConst";

function GET_TIPOSCENTRO_AXIOS() {
    return axios(ENDPOINT_TIPOSCENTRO_GET_BBDD);
}

export {
    GET_TIPOSCENTRO_AXIOS,
}

import moment  from "moment";
export function formatDate(value){
    if (value) {
        return moment(String(value), 'DD-MM-YYYY').format("DD-MM-YYYY");
    }
}
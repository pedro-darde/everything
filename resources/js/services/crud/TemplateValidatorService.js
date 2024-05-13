import {BaseCrudService} from "./BaseCrudService.js";

export class TemplateValidatorService  extends BaseCrudService {
    constructor() {
        super('validator');
    }
    async import(id, data) {
        return await axios.post(`${this.model}/import/${id}`, data)
    }
}
export const templateValidatorService = new TemplateValidatorService();

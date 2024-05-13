export class BaseCrudService {

    /**
     *
     * @param {String} model
     */
    constructor(model) {
        this.model = model;
    }

    async all() {
        return await axios.get(`/${this.model}`);
    }

    async find(id) {
        return await axios.get(`/${this.model}/${id}`);
    }

    async create(data) {
        return await axios.post(`/${this.model}`, data);
    }

    async update(id, data) {
        return await axios.put(`/${this.model}/${id}`, data);
    }

    async delete(id) {
        return await axios.delete(`/${this.model}/${id}`);
    }
}

export function useDateFormatter() {
    function formatDate(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString('pt-BR', options);
    }

    return {
        formatDate,
    };
}

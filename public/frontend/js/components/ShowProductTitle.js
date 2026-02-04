const ShowProductTitle = {
    name: 'ShowProductTitle',
    template: `
        <h1>Products 
            <span class="badge bg-secondary">{{ total }}</span>
        </h1>
    `,
    props: {
        total: {
            type: Number,
            default: 0
        }
    },
    setup(props) {
        return {};
    }
};
const Placinta = {
    name: 'ArataTitluCategorie',
    template: `
        <h1>Categorii 
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
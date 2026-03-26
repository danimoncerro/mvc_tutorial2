const StergeProdus = {
    name: 'StergeProdus',
    emits: ['arata-produse'],
    template: `
       <button class="btn btn-danger btn-sm" @click="stergeProdus()" title="Șterge produsul">
            <i class="bi bi-trash"></i>
            Sterge
        </button>
    
    `,
    props: {

        deletelink: {
            type: String,
            required: true
        }
    
    
    },
    setup(props, { emit }) {
    
        const stergeProdus = () => {
            axios.post(props.deletelink)
            .then(response => {
                emit('arata-produse');
            })

        }

        return {
            stergeProdus
        }
    
    },

}
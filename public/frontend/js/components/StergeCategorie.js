const StergeCategorie = {
    name: 'StergeCategorie',
    emits: ['arata-categorii'],
    template: `
       <button class="btn btn-danger btn-sm" @click="stergeCategorie()" title="Șterge categorie">
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
    
        const stergeCategorie = () => {
            axios.post(props.deletelink)
            .then(response => {
                emit('arata-categorii');
            })

        }

        return {
            stergeCategorie
        }
    
    },

}
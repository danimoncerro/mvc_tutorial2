const StergeUtilizator = {
    name: 'StergeUtilizator',
    emits: ['afiseaza-utilizatori'],
    template: `
        <button class="btn btn-danger btn-sm" @click="stergeUtilizator()" title="Șterge utilizatorul">
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
        const stergeUtilizator = () => {
            if (!confirm('Ești sigur că vrei să ștergi acest utilizator?')) {
                return;
            }

            axios.post(props.deletelink, {})
                .then(response => {
                    console.log('Utilizator sters:', response.data);
                    // Refresh the user list
                    emit('afiseaza-utilizatori');
                    alert('Utilizator șters cu succes!');
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                    alert('Eroare la ștergerea utilizatorului!');
                });
        }

        return {
            stergeUtilizator
        };
    }
};
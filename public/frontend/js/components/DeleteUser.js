const DeleteUser = {
    name: 'DeleteUser',
    emits: ['show-users'],
    template: `
        <button class="btn btn-danger btn-sm" @click="deleteUser()" title="Șterge utilizatorul">
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
        const deleteUser = () => {
            if (!confirm('Ești sigur că vrei să ștergi acest utilizator?')) {
                return;
            }

            axios.post(props.deletelink, {})
                .then(response => {
                    console.log('User deleted:', response.data);
                    // Refresh the user list
                    emit('show-users');
                    alert('Utilizator șters cu succes!');
                })
                .catch(error => {
                    console.error('Error deleting user:', error);
                    alert('Eroare la ștergerea utilizatorului!');
                });
        }

        return {
            deleteUser
        };
    }
};
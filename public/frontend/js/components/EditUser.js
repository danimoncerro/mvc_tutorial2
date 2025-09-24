const EditUser = {
    name: 'EditUser',
    emits: ['show-users'],
    template: 
    `
    <!-- Modal pentru editarea utilizatorilor -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Editează utilizator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form @submit.prevent="updateUser()">
                        <div class="mb-3">
                            <label for="editUserEmail" class="form-label">Email utilizator</label>
                            <input type="text" class="form-control" id="editUserEmail" v-model="editingUser.email" required>
                        </div>
                        <div class="mb-3">
                                <label for="role" class="form-label">Rol utilizator</label>
                                <select class="form-select" id="role" v-model="editingUser.role" required>
                                    <option value="">Selectează un rol</option>
                                    <option value="admin">Admin</option>
                                    <option value="livrator">Livrator</option>
                                    <option value="client">Client</option>
                                </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                    <button type="button" class="btn btn-primary" @click="updateUser()">
                        <i class="bi bi-check-circle"></i> Salvează modificările
                    </button>
                </div>
            </div>
        </div>
    </div>
        
    `,
    props: {
        updatelink: {
            type: String,
            required: true
        },
        editingUser: {
            type: Object,
            required: true
        }
    },
    setup(props, { emit }) {

        const updateUser = () => {
            axios.post(props.updatelink + '?id=' + props.editingUser.id, props.editingUser)
            .then(response => {
                console.log('User modified:', response.data);

                // Reset the editing user form
                props.editingUser.email = '';
                props.editingUser.id = '';

                // Închide modalul - metoda simplificată și sigură
                const modal = bootstrap.Modal.getInstance(document.getElementById('editUserModal'));
                modal.hide();

                // Refresh the user list
                emit('show-users');
            })
            .catch(error => {
                console.error('Error updating user:', error);
                alert('Eroare la actualizarea utilizatorului!');
            });
        }

        return {
            updateUser
        };
    }
};
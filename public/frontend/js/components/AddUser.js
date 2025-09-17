const AddUser = {
    name: 'AddUser',
    emits: ['show-users'],
    template: `
        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="bi bi-plus-circle"></i> Adauga user
            </button>
        </div>

        
        <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Adaugă utilizator nou</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form @submit.prevent="addUser()">
                            <div class="mb-3">
                                <label for="userEmail" class="form-label">Email utilizator</label>
                                <input type="text" class="form-control" id="userEmail" v-model="user.email" required>
                            </div>
                            
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        <button type="button" class="btn btn-primary" @click="addUser()">
                            <i class="bi bi-check-circle"></i> Salvează utilizator
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `,
    props: {
        savelink: {
            type: String,
            required: true
        }
        
    },
    setup(props, { emit }) {
        const { reactive } = Vue;
        
        const user = reactive({
            name: '',
            description: ''
        });

        const addUser = () => {
                // Validare simplă
                if (!user.email) {
                    alert('Te rog completează toate câmpurile!');
                    return;
                }

                axios.post(props.savelink, {
                    email: user.email
                    
                })
                .then(response => {
                    console.log('User added:', response.data);

                    // Reset the user form
                    user.email = '';
               
                    
                    // Închide modalul
                    const modal = bootstrap.Modal.getInstance(document.getElementById('addUserModal'));
                    modal.hide();
                    
                    // Refresh the users list
                    emit('show-users');
                    
                    // Afișează mesaj de succes (opțional)
                    alert('Utilizator adăugat cu succes!');
                })
                .catch(error => {
                    console.error('Error adding user:', error);
                    alert('Eroare la adăugarea utilizatorului!');
                });
        }

        return {
            user,
            addUser
        };
    }
};
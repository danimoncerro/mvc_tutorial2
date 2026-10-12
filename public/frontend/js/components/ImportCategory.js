const ImportCategory = {
    name: 'ImportCategory',
    emits: ['show-categories'],
    template: `

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importCategoryModal">
                <i class="bi bi-plus-circle"></i> Importa categorii prin vueJs1
            </button>
        </div>

        <!-- Modal pentru adăugarea produselor -->
        <div class="modal fade" id="importCategoryModal" tabindex="-1" aria-labelledby="importCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importCategoryModalLabel">Importa categorii prin vuejs</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input
                            type="file"
                            class="form-control"
                            @change="selectFile"
                            id="file"
                            name="csvfile"
                            required
                        >

                    <p> SelectedCategorii: {{ selectedFile }} </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        <button type="button" class="btn btn-primary" @click="importCategories()">
                            <i class="bi bi-check-circle"></i> Import Categorii
                        </button>
                    </div>

                </div>
            </div>
        </div>
    
    `,
    props: {
        importlink: {
            type: String,
            required: true
        }
    
    },
    setup(props, { emit }) {

        const { reactive} = Vue;

        const selectedFile = ref(null);

        const selectFile = (event) => {
                selectedFile.value = event.target.files[0];
        }

        const importCategories = () => {
            console.log('Import categorii:');
            console.log(selectedFile.value);

            const formData = new FormData;
            formData.append('csvfile', selectedFile.value);

            axios.post(props.importlink, formData)
                .then(response => {
                    console.log(response)
                    const modal = bootstrap.Modal.getInstance(document.getElementById('importCategoryModal'));
                    modal.hide();
                    // Refresh the product list
                    emit('show-categories');
                    messageNotification.value = response.data;
                })
                .catch(error => {
                    console.log(error)
                });



        }

        return {

            selectedFile,
            selectFile,
            importCategories
        }
    
    
    
    },

}
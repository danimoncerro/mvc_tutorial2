const ImportProduct1 = {
    name: '',
    emits: ['show-products'],
    template: `

        <div class="mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#importProductModal1">
                <i class="bi bi-plus-circle"></i> Importa produse prin vueJs2
            </button>
        </div>


        <!-- Modal pentru importul produselor -->

        <div class="modal fade" id="importProductModal1" tabindex="-1" aria-labelledby="importProductModalLabel1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importProductModalLabel1">Importa Produse prin vuejs2</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input
                            type="file"
                            class="form-control"
                            id="file"
                            @change="selectFile"
                            name="csvfile"
                            required
                        >
                    <p> Selectfile: {{ selectedFile }} </p>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                        <button type="button" class="btn btn-primary" @click="importProducts()">
                            <i class="bi bi-check-circle"></i> Import Produse
                        </button>
                    </div>

                </div>
            </div>
        </div>
    
    
    `,

    props: {
        processlink: {
            type: String,
            required: true
        }

    },

    setup(props, { emit }) {
        const { reactive } = Vue;
        const selectedFile = ref(null);

        const selectFile = (event) => {
                selectedFile.value = event.target.files[0];
        }

        const importProducts = () => {
            console.log('Import produse:');
            console.log(selectedFile.value);

            const formData = new FormData;
            formData.append('csvfile', selectedFile.value); 

            axios.post(props.processlink, formData)
                .then(response => {
                    console.log(response)
                    const modal = bootstrap.Modal.getInstance(document.getElementById('importProductModal1'));
                    modal.hide();
                    // Refresh the product list
                    emit('show-products');
                    messageNotification.value = response.data;
                })
                .catch(error => {
                    console.log(error)
                });
        }

        return {
            selectedFile,
            importProducts,
            selectFile
        }

    }








};
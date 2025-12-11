const CategoryDetail = {
    name: 'CategoryDetail',
    props: {
        category: {
            type: Object,
            default: null
        }

    },
    template: `   

        <!-- Modal pentru afisare detalii categorie -->
        <div class="modal fade" id="categoryDetail" tabindex="-1" aria-labelledby="categoryDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="categoryDetailModalLabel">Detalii categorie</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <p v-if="!category"> Se incarca detaliile categoriei...</p>
                        <div v-else>
                            <strong>Category id: </strong>
                                <span> {{ category.id }} </span>  <br>
                            <strong>Cateogry name: </strong>
                                <span> {{ category.name }} </span> <br>
                            <strong>Category description: </strong>
                                <span> {{ category.description }} </span> <br>
                        </div>

                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Inchide</button>
                        
                    </div>
                </div>
            </div>
        </div>


    `,

    setup(props) {
         
    }
       
    
};
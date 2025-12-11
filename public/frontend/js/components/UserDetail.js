const UserDetail = {
    name: 'UserDetail',
    props: {
        user: {
            type: Object,
            default: null
        }

    },
    template: `   

        <!-- Modal pentru afisare detalii user -->
        <div class="modal fade" id="userDetail" tabindex="-1" aria-labelledby="userDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="userDetailModalLabel">Detalii user</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <p v-if="!user"> Se incarca detaliile userului...</p>
                        <div v-else>
                            <strong>User id: </strong>
                                <span> {{ user.id }} </span>  <br>
                            <strong>User email: </strong>
                                <span> {{ user.email }} </span> <br>
                            <strong>User role: </strong>
                                <span> {{ user.role }} </span> <br>
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
const BillingAddressDetail = {
    name: 'BillingAddressDetail',
    props: {
        billing: {
            type: Object,
            default: null
        }

    },

    template: `   

        <!-- Modal pentru detalii adresa facturare -->
        <div class="modal fade" id="billingAddressModal" tabindex="-1" aria-labelledby="billingAddressModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="billingAddressModalLabel">Detalii adrese facturare</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p v-if="!billing || billing.length === 0">Nu există adrese de facturare înregistrate pentru acest utilizator.</p>
                        <div v-else>
                            <div v-for="(address, index) in billing" :key="address.id" class="mb-3 p-3 border rounded">
                                <h6>Adresa #{{ index + 1 }}</h6>
                                <strong>User ID:</strong> <span>{{ address.user_id || 'N/A' }}</span><br>
                                <strong>Adresa:</strong> <span>{{ address.address || 'N/A' }}</span><br>
                                <strong>Cod postal:</strong> <span>{{ address.zip_code || 'N/A' }}</span><br>
                                <strong>Oraș:</strong> <span>{{ address.city || 'N/A' }}</span><br>
                                <strong>Județ:</strong> <span>{{ address.county || 'N/A' }}</span><br>
                            </div>
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
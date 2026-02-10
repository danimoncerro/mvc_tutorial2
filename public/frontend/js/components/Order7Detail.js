const Order7Detail = {
    name: 'Order7Detail',
    props: {
        order: {
            type: Object,
            default: null
        },
        marks: {
            type: Array
        }

    },
    template: `   

        <!-- Modal pentru afisare detalii comanda -->
        <div class="modal fade" id="order7DetailModal" tabindex="-1" aria-labelledby="orderDetailModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="orderDetailModalLabel">
                            <i class="bi bi-receipt-cutoff me-2"></i>
                            Detalii Comandă
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <!-- Loading State -->
                        <div v-if="!order" class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Se încarcă...</span>
                            </div>
                            <p class="mt-3 text-muted">Se încarcă detaliile comenzii...</p>
                        </div>

                        <!-- Order Details -->
                        <div v-else>
                            <!-- Order Summary Card -->
                            <div class="card mb-3 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-currency-exchange fs-4 text-success me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Valoare Comandă</small>
                                                    <h5 class="mb-0 text-success fw-bold">{{ order.total_order }} lei</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock-history fs-4 text-info me-3"></i>
                                                <div>
                                                    <small class="text-muted d-block">Data Comandă</small>
                                                    <strong>{{ order.created_at }}</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status & User Info -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="bi bi-person-circle me-1"></i>
                                                Client
                                            </h6>
                                            <p class="card-text mb-0">{{ order.user_email }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="bi bi-info-circle me-1"></i>
                                                Status
                                            </h6>
                                            <span class="badge fs-6" :class="{
                                                'bg-warning': order.status === 'pending',
                                                'bg-success': order.status === 'delivered',
                                                'bg-danger': order.status === 'canceled'
                                            }">
                                                {{ order.status }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Addresses -->
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="bi bi-truck me-1"></i>
                                                Adresă Livrare
                                            </h6>
                                            <p class="card-text mb-0 small">{{ order.shipping_address || 'Nu este specificată' }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 text-muted">
                                                <i class="bi bi-receipt me-1"></i>
                                                Adresă Facturare
                                            </h6>
                                            <p class="card-text mb-0 small">{{ order.billing_address || 'Nu este specificată' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Products Table -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0">
                                        <i class="bi bi-cart3 me-2"></i>
                                        Produse Comandate
                                    </h6>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="border-0">Denumire produs</th>
                                                    <th class="border-0 text-end">Preț</th>
                                                    <th class="border-0 text-center">Cantitate</th>
                                                    <th class="border-0 text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="orderItem in marks" :key="orderItem.id">
                                                    <td>
                                                        <strong>{{ orderItem.product_name }}</strong>
                                                    </td>
                                                    <td class="text-end">{{ orderItem.product_price_db }} lei</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary">{{ orderItem.qty }}</span>
                                                    </td>
                                                    <td class="text-end">
                                                        <strong>{{ (orderItem.product_price_db * orderItem.qty).toFixed(2) }} lei</strong>
                                                    </td>
                                                </tr>
                                                <tr v-if="marks.length === 0">
                                                    <td colspan="4" class="text-center text-muted py-4">
                                                        <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                                                        Nu există produse în această comandă
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>
                            Închide
                        </button>
                    </div>
                </div>
            </div>
        </div>


    `,

    setup(props) {
         
    }
       
    
};
const EditeazaParola = {
	name: 'EditeazaParola',
	emits: ['afiseaza-utilizatori'],
	template: 
	`
    <div class="modal fade" id="editeazaParolaModal" tabindex="-1" aria-labelledby="editeazaParolaModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editeazaParolaModalLabel">Editeaza parola</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form @submit.prevent="actualizeazaParola()">
						<div class="mb-3">
							<label for="editareParola" class="form-label">Parola utilizator</label>
							<input type="password" class="form-control" id="editareParola" v-model="utilizator.password" required>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuleaza</button>
					<button type="button" class="btn btn-primary" @click="actualizeazaParola()">
						<i class="bi bi-check-circle"></i> Salveaza modificarile
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
		utilizator: {
			type: Object,
			required: true
		}
	},
	setup(props, { emit }) {
		const actualizeazaParola = () => {
			if (!props.utilizator.id || !props.utilizator.password) {
				alert('Completeaza parola inainte de salvare.');
				return;
			}

            axios.post(props.updatelink + '?id=' + props.utilizator.id, props.utilizator)
				.then(response => {
					console.log('Parola actualizata:', response.data);

					const modal = bootstrap.Modal.getInstance(document.getElementById('editeazaParolaModal'));
					if (modal) {
						modal.hide();
					}

					props.utilizator.password = '';

					emit('afiseaza-utilizatori');
				})
				.catch(error => {
					console.error('Eroare la actualizare parola:', error);
					alert('Eroare la actualizarea parolei!');
				});
			
		};

		return {
			actualizeazaParola
		};
	}
};

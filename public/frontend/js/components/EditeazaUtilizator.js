const EditeazaUtilizator = {
	name: 'EditeazaUtilizator',
	emits: ['afiseaza-utilizatori'],
	template: 
	`
	<div class="modal fade" id="editeazaUtilizatorModal" tabindex="-1" aria-labelledby="editeazaUtilizatorModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editeazaUtilizatorModalLabel">Editeaza utilizator</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form @submit.prevent="actualizeazaUtilizator()">
						<div class="mb-3">
							<label for="editUserEmail" class="form-label">Email utilizator</label>
							<input type="text" class="form-control" id="editUserEmail" v-model="utilizator.email" required>
						</div>
						<div class="mb-3">
							<label for="editUserRole" class="form-label">Rol utilizator</label>
							<select class="form-select" id="editUserRole" v-model="utilizator.role" required>
								<option value="">Selecteaza un rol</option>
								<option value="admin">Admin</option>
								<option value="livrator">Livrator</option>
								<option value="client">Client</option>
							</select>
						</div>
					</form>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anuleaza</button>
					<button type="button" class="btn btn-primary" @click="actualizeazaUtilizator()">
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
		const actualizeazaUtilizator = () => {
			axios.post(props.updatelink + '?id=' + props.utilizator.id, props.utilizator)
				.then(response => {
					console.log('Utilizator actualizat:', response.data);

					const modal = bootstrap.Modal.getInstance(document.getElementById('editeazaUtilizatorModal'));
					if (modal) {
						modal.hide();
					}

					emit('afiseaza-utilizatori');
				})
				.catch(error => {
					console.error('Eroare la actualizare utilizator:', error);
					alert('Eroare la actualizarea utilizatorului!');
				});
		};

		return {
			actualizeazaUtilizator
		};
	}
};

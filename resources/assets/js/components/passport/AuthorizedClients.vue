<style scoped>
    .action-link {
        cursor: pointer;
    }
</style>

<template>
    <div>
		<div class="card card-default">
			<div class="card-header">
				<div style="display: flex; justify-content: space-between; align-items: center;">
					<span>
						Licencias
					</span>

					<a class="action-link" tabindex="-1" @click="showCreateLicenceForm">
						Agregar Licencia
					</a>
				</div>
			</div>
			<div class="card-body">
				<!-- Authorized Tokens -->
				<table class="table table-borderless mb-0 table-hover">
					<thead>
						<tr>
							<th>Name</th>
							<th>Licence</th>
							<th>IP</th>
							<th>Host ID</th>
							<th></th>
						</tr>
					</thead>
					
					<tbody>
						<template v-for="licence in licences">
							<tr v-bind:class="{'text-muted': !showDelete(licence)}">
								<!-- Client Name -->
								<td style="vertical-align: middle;">
									{{ licence.client.name }}
								</td>
								
								<!-- Licence -->
								<td style="vertical-align: middle;">
									{{ licence.licence }}
								</td>
								
								<!-- IP -->
								<td style="vertical-align: middle;">
									{{ licence.ip }}
								</td>
								
								<!-- HostId -->
								<td style="vertical-align: middle;">
									{{ licence.hostid }}
								</td>

								<!-- Revoke Button -->
								<td style="vertical-align: middle;">
									<a v-if="showRevoke(licence)" class="action-link text-danger" @click="revoke(licence)">
										Revocar
									</a>
									
									<a v-if="showDelete(licence)" class="action-link text-danger" @click="deleteLicence(licence)">
										Deshabilitar
									</a>
									
									<a v-if="!showDelete(licence)" class="action-link text-danger" @click="enableLicence(licence)">
										Habilitar
									</a>
								</td>
							</tr>
						</template>
					</tbody>
					
                </table>
            </div>
        </div>
		
		<!-- Create Licence Modal -->
        <div class="modal fade" id="modal-create-licence" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">
                            Crear Licencia
                        </h4>

                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    </div>

                    <div class="modal-body">
                        <!-- Form Errors -->
                        <div class="alert alert-danger" v-if="createForm.errors.length > 0">
                            <p class="mb-0"><strong>Whoops!</strong> Something went wrong!</p>
                            <br>
                            <ul>
                                <li v-for="error in createForm.errors">
                                    {{ error }}
                                </li>
                            </ul>
                        </div>

                        <!-- Create Licence Form -->
                        <form role="form">
                            <!-- Client -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Cliente</label>

                                <div class="col-md-9">
                                    <select v-model="createForm.client" class="form-control" name="client">
									  <option v-for="client in clients" v-bind:value="client.id">
										{{ client.name }}
									  </option>
									</select>

                                    <span class="form-text text-muted">
                                        Seleccione un cliente de la lista.
                                    </span>
                                </div>
                            </div>

                            <!-- Licence -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Licencia</label>

                                <div class="col-md-9">
                                    <input type="text" class="form-control" name="licence"
                                                    @keyup.enter="store" v-model="createForm.licence">

                                    <span class="form-text text-muted">
                                        Código de Licencia.
                                    </span>
                                </div>
                            </div>
							
							<!-- Amount -->
                            <div class="form-group row">
                                <label class="col-md-3 col-form-label">Cantidad</label>

                                <div class="col-md-9">
                                    <input type="number" class="form-control" name="amount" min="0"
                                                    @keyup.enter="store" v-model="createForm.amount">

                                    <span class="form-text text-muted">
                                        Cantidad de Licencias.
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                        <button type="button" class="btn btn-primary" @click="store">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
		
    </div>
</template>

<script>
    export default {
        /*
         * The component's data.
         */
        data() {
            return {
                licences: [],
				
				clients: [],
				
				createForm: {
                    errors: [],
                    client: '',
					licence: '',
                    amount: ''
                },
            };

        },

        /**
         * Prepare the component (Vue 1.x).
         */
        ready() {
            this.prepareComponent();
        },

        /**
         * Prepare the component (Vue 2.x).
         */
        mounted() {
            this.prepareComponent();
        },

        methods: {
            /**
             * Prepare the component (Vue 2.x).
             */
            prepareComponent() {
                this.getTokens();
				this.getClients();
            },

            /**
             * Get all of the authorized licences
             */
            getTokens() {
                axios.get('/oauth/tokens')
                        .then(response => {
                            this.licences = response.data;
                        });
            },

            /**
             * Revoke the given licence.
             */
            revoke(licence) {
                axios.delete('/oauth/tokens/' + licence.token.id)
                        .then(response => {
                            this.getTokens();
                        });
            },
			
			/**
			*
			*/
			showRevoke(licence) {
				return (!! licence.ip);
			},
			
			/**
			*
			*/
			showDelete(licence) {
				return !(!! licence.deleted_at);
			},
			
			/**
             * Show the form for creating new licences.
             */
            showCreateLicenceForm() {
				this.createForm.errors = [];
				this.createForm.client = '';
				this.createForm.licence = '';
				this.createForm.amount = '';
                $('#modal-create-licence').modal('show');
            },
			
			/**
             * Create a new Licence.
             */
            store() {
				console.log('store');
				
				if (! this.createForm.client || !this.createForm.licence || !this.createForm.amount) {
					this.createForm.errors = [];
					if (!this.createForm.client) {
						this.createForm.errors.push('Cliente es requerido');
					}
					if (!this.createForm.licence) {
						this.createForm.errors.push('Código de licencia es requerido');
					}
					if (!this.createForm.amount || this.createForm.amount == 0) {
						this.createForm.errors.push('La cantidad de licencias es requerido');
					}
				} else {
					this.persistLicence(
                    'post', '/oauth/licence',
                    this.createForm, '#modal-create-licence'
                );
				}
            },
			
			/**
             * Persist the client to storage using the given form.
             */
            persistLicence(method, uri, form, modal) {
			
				console.log('persistLicence');
				
                form.errors = [];

                axios[method](uri, form)
                    .then(response => {
					
						console.log(JSON.parse(response.data));
						
						var data = JSON.parse(response.data);
						
						if (data.message == 'OK') {
							this.getTokens();

							form.client = '';
							form.licence = '';
							form.amount = '';
							form.errors = [];

							$(modal).modal('hide');
						} else {
							form.errors = [data.message];
							console.log(form.errors);
						}
                        
                    })
                    .catch(error => {
						console.log('ERRORS');
						form.errors = ['Something went wrong. Please try again.'];
						console.log(error);
                        if (typeof error.response.data === 'object') {
                            //form.errors = _.flatten(_.toArray(error.response.data.errors));
							console.log('OBJECT');
                        } else {
                            //form.errors = ['Something went wrong. Please try again.'];
							console.log('ELSE');
                        };
						console.log(form.errors);
                    });
            },
			
			/**
             * Disable the given licence.
             */
            deleteLicence(licence) {
                axios.delete('/oauth/licence/' + licence.id)
                        .then(response => {
                            this.getTokens();
                        });
            },
			
			/**
             * Enable the given licence.
             */
            enableLicence(licence) {
                axios.put('/oauth/licence/' + licence.id)
                        .then(response => {
                            this.getTokens();
                        });
            },
			
			/**
             * Get all of the OAuth clients for the user.
             */
            getClients() {
                axios.get('/oauth/clients')
                        .then(response => {
                            this.clients = response.data;
                        });
            },

        }
    }
</script>

<template>
    <div>
        <b-modal
            :id="id"
            :title="(isNew)?titulo1:titulo2"
            @show="reset"
            @hidden="reset"
            @ok="handleOk">
            <form @submit.stop.prevent="enviar">
                <b-alert dismissible :show="errors.length">
                    {{ errors }}
                </b-alert>
                <template v-for="(item,key) in form">
                    <b-form-group
                        :label="item.label"
                        :label-for="key"
                        :state="item.state"
                        :invalid-feedback="item.stateText"
                        v-if="['text','password','date','textarea','email','select'].includes(item.type)"
                    >
                        <b-input
                            :type="item.type"
                            :placeholder="item.label"
                            v-model="item.value"
                            :id="key"
                            :state="item.state"
                            v-if="['text','password','date','email'].includes(item.type)"
                        ></b-input>
                        <b-textarea
                            v-if="item.type==='textarea'"
                            :placeholder="item.label"
                            v-model="item.value"
                            :id="key"
                            :state="item.state"
                        ></b-textarea>
                    </b-form-group>
                </template>
            </form>
            <template #modal-footer="{ ok, cancel }">
                <b-button variant="danger" @click="cancel()">
                    Cancel
                </b-button>
                <loading-button :loading="sending" variant="default"
                                @click.native="ok()" :text="'Guardar'" :textLoad="'Guardando'">Guardar
                </loading-button>
            </template>
        </b-modal>
    </div>
</template>

<script>
import axios from "axios";
import LoadingButton from '@/Shared/LoadingButton'

export default {
    name: "recibo",
    props: {
        isNew: Boolean,
        id: String,
        itemRow: Object,
    },
    components: {
        LoadingButton
    },
    data() {
        return {
            sending: false,
            titulo1: "Nuevo Recibo",
            titulo2: "Modificar Recibo",
            form: {
                nombre: {
                    label: 'nombre',
                    value: "",
                    type: "text",
                    state: null,
                    stateText: null
                }, descripcion: {
                    label: 'descripcion',
                    value: "",
                    type: "textarea",
                    state: null,
                    stateText: null
                }, sucursal: {
                    label: 'sucursal',
                    value: "",
                    type: "select",
                    state: null,
                    stateText: null,
                    options: this.sucursales
                }, dependeDe: {
                    label: 'Depende de',
                    value: "",
                    type: "select",
                    state: null,
                    stateText: null,
                    options: this.cajasPadre
                }, enable: {
                    label: 'enable',
                    value: "",
                    type: "boolean",
                    state: null,
                    stateText: null
                }, monto: {
                    label: '',
                    value: "",
                    type: "hidden",
                    state: null,
                    stateText: null
                }
            },
            idForm: null,
            errors: Array
        }
    },
    methods: {
        reset() {
            this.limpiar();

            if (this.isNew) {
                if ('id' in this.itemRow) {
                    this.idForm = null;
                }
                Object.keys(this.form).forEach(key => {
                    this.form[key].value = "";
                })
            } else {
                if ('id' in this.itemRow) {
                    this.idForm = this.itemRow['id'];
                }
                Object.keys(this.form).forEach(key => {
                    if (['enable'].includes(key)) {
                        this.form[key].value = (this.itemRow[key] === 1)
                    } else {
                        this.form[key].value = this.itemRow[key];
                    }
                })
            }
        },
        limpiar() {
            Object.keys(this.form).forEach(key => {
                this.form[key].state = null;
                this.form[key].stateText = null;
            })
            this.errors = [];
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault();
            this.enviar();
        },
        enviar() {
            this.sending = true;
            this.limpiar();
            let producto = new FormData();
            if (this.idForm) {
                producto.append('id', this.idForm);
            }
            Object.keys(this.form).forEach(key => {
                if (['dependeDe'].includes(key)) {
                    if (this.form[key].value !== "" && this.form[key].value !== null) {
                        producto.append(key, this.form[key].value);
                    }
                } else if (['enable'].includes(key)) {
                    producto.append(key, this.form[key].value ? '1' : '0');
                } else {
                    producto.append(key, this.form[key].value);
                }
            })

            axios.post('/admin/caja', producto, {headers: {'Content-Type': 'multipart/form-data'}})
                .then(({data}) => {
                    if (data["status"] === 0) {
                        this.$bvModal.hide(this.id)
                    }
                })
                .catch(error => {
                    // handle error
                    this.errors = error
                    console.log(error);
                }).finally(() => {
                this.sending = false;
            })
        }
    },
}
</script>

<template>
    <div class="content-w">
        <div class="content-box">
            <Menu :active="3"></Menu>
            <div class="tab-content">
                <div class="row m-b-20 m-t-10">
                    <div class="col">
                        <b-button v-b-modal="'productoModal'" @click="loadModal()">{{ boton1 }}</b-button>
                        <FormProducto :isNew="isNew" id="productoModal" :itemRow="itemRow"></FormProducto>
                    </div>
                </div>

                <div class="row m-b-20">
                    <b-card>
                        <b-table
                            striped
                            hover
                            responsive
                            :items="tipos"
                            :fields="fields"
                            show-empty
                            small
                        >
                            <template #empty="scope">
                                <p>{{ textoVacio }}</p>
                            </template>
                            <template v-slot:cell(Acciones)="row">
                                <div class="row-actions">
                                    <b-button size="sm" v-b-modal="'productoModal'" @click="loadModal(false,row)">
                                        {{ boton2 }}
                                    </b-button>
                                    <b-button size="sm" class="btn-danger" @click="borrar(row.item.id)">{{
                                            boton3
                                        }}
                                    </b-button>
                                </div>
                            </template>
                        </b-table>
                    </b-card>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from '@/Shared/Layout'
import FormProducto from './form'
import Menu from '@/Shared/menu/menuProductos';

export default {
    layout: Layout,
    props: {
        tipos: Array,
        errors: Object,
    },
    components: {
        FormProducto,
        Menu
    },
    data() {
        return {
            isNew: true,
            boton1: "Nuevo",
            boton2: "Modificar",
            boton3: "Borrar",
            titulo: 'Productos',
            textoVacio: 'No existen Productos',
            idModal: 'productoModal',
            fields:
                [
                    'nombre',
                    'codigo',
                    'Acciones'
                ],
            itemRow: {}
        }
    },
    methods: {
        loadModal(isNew = true, item = null) {
            this.isNew = isNew;
            this.itemRow = {};
            if (!isNew) {
                this.itemRow = item.item;
            }
        },
        borrar(id) {
            this.$inertia.delete(`producto/${id}`, {
                onBefore: () => confirm('Esta seguro?'),
            })
        }
    }
}
</script>


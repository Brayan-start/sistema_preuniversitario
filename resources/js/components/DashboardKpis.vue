<template>
    <div class="row">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <h6 class="text-uppercase">Total Inscritos</h6>
                    <h2 class="mb-0">{{ kpis.total_inscritos }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <h6 class="text-uppercase">Aprobados</h6>
                    <h2 class="mb-0">{{ kpis.inscritos_aprobados }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark shadow">
                <div class="card-body">
                    <h6 class="text-uppercase">Pagos Pendientes</h6>
                    <h2 class="mb-0">{{ kpis.pagos_pendientes }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <h6 class="text-uppercase">Cupos Libres</h6>
                    <h2 class="mb-0">{{ kpis.cupos_totales }}</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            kpis: {
                total_inscritos: 0,
                inscritos_aprobados: 0,
                pagos_pendientes: 0,
                cupos_totales: 0
            }
        };
    },
    mounted() {
        this.fetchKpis();
    },
    methods: {
        async fetchKpis() {
            try {
                const response = await fetch('/api/admin/dashboard', {
                    headers: {
                        'Authorization': `Bearer ${localStorage.getItem('token')}`
                    }
                });
                const data = await response.json();
                this.kpis = data.kpis;
            } catch (error) {
                console.error("Error fetching KPIs:", error);
            }
        }
    }
};
</script>

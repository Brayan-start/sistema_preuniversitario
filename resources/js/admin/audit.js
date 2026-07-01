const init = () => {
    const table = document.querySelector('[data-admin-audit-table]');

    if (table && window.DataTable) {
        new window.DataTable(table, {
            pageLength: 15,
            ordering: false,
            language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json' },
        });
    }
};

init();
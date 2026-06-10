const init = () => {
    const table = document.querySelector('[data-admin-search-table]');

    if (table && window.DataTable) {
        new window.DataTable(table, {
            pageLength: 20,
            ordering: false,
            language: { url: 'https://cdn.datatables.net/plug-ins/2.1.8/i18n/es-ES.json' },
        });
    }
};

init();
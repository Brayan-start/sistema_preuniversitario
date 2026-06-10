import './bootstrap';

const boot = () => {
	const loader = document.getElementById('loader');

	if (loader) {
		window.requestAnimationFrame(() => {
			loader.style.opacity = '0';
			window.setTimeout(() => loader.remove(), 350);
		});
	}

	const sidebarCollapse = document.getElementById('sidebarCollapse');
	const sidebar = document.getElementById('sidebar');
	const content = document.getElementById('content');

	if (sidebarCollapse && sidebar && content) {
		sidebarCollapse.addEventListener('click', () => {
			sidebar.classList.toggle('active');
			content.classList.toggle('active');
		});
	}

	if (window.AOS) {
		window.AOS.init({ duration: 800, once: true });
	}

	document.querySelectorAll('[data-flash-toast]').forEach((element) => {
		const { type, message } = element.dataset;

		if (window.Swal) {
			window.Swal.mixin({
				toast: true,
				position: 'top-end',
				showConfirmButton: false,
				timer: 3000,
				timerProgressBar: true,
			}).fire({ icon: type, title: message });
		}
	});

	const page = document.body.dataset.page;

	if (page === 'admin-dashboard') {
		import('./admin/dashboard');
	}

	if (page === 'admin-statistics') {
		import('./admin/statistics');
	}

	if (page === 'admin-audit') {
		import('./admin/audit');
	}

	if (page === 'admin-search') {
		import('./admin/search');
	}
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', boot);
} else {
	boot();
}

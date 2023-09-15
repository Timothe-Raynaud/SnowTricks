export function toast(type, message) {
    let toast = document.getElementById('toast');
    let toastContainer = document.getElementById('toast-container');
    let toastContent = document.getElementById('toast-content');

    toastContainer.className = type;
    toast.classList.remove('d-none');
    toastContent.innerHTML = message;

    setTimeout(() => {
        dismiss(toast);
    }, 4000);

    toast.addEventListener("mouseenter", handleMouseEnter);
    toast.addEventListener("mouseleave", handleMouseLeave);
}

function dismiss(toast) {
    if (!toast.matches(':hover')) {
        toast.classList.add('d-none');
    }
}

function handleMouseEnter() {
    clearTimeout(this.dismissTimeout);
}

function handleMouseLeave() {
    this.dismissTimeout = setTimeout(() => {
        dismiss(this);
    }, 1000);
}
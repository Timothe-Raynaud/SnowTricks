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

    toast.addEventListener("mouseenter", () => handleMouseEnter(toast));
    toast.addEventListener("mouseleave", () => handleMouseLeave(toast));
}

function dismiss(toast) {
    if (!toast.matches(':hover')) {
        toast.classList.add('d-none');
    }
}

function handleMouseEnter(toast) {
    clearTimeout(toast.dismissTimeout);
}

function handleMouseLeave(toast) {
    toast.dismissTimeout = setTimeout(() => {
        dismiss(toast);
    }, 1000);
}
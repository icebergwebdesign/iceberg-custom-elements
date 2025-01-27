// Function to detach all click listeners from .breakdance-menu-link elements, 
// except those with href containing "#" or empty
function detachLinkClickListeners() {
    const links = document.querySelectorAll('.breakdance-menu--dropdown-accordion .breakdance-menu-list .breakdance-dropdown-toggle .breakdance-menu-link');
    links.forEach(link => {
        // Check if the link's href attribute contains "#" or is empty
        if (!link.href || link.href.endsWith('#')) {
            // Skip this link, do not detach its listeners
            return;
        }

        // Clone the link to remove all event listeners from it
        const clonedLink = link.cloneNode(true);
        // Replace the original link with its clone
        link.parentNode.replaceChild(clonedLink, link);
    });
}

// Call the modified setup function on page load with a delay
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(detachLinkClickListeners, 500); // Delay execution by 0.5 second to ensure all elements are loaded
});
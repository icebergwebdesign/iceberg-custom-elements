// Function to check for active dropdown items and update parent menu items
function updateParentMenuItems(parentMenuItemClass) {    
    // Select all active dropdown items
    let activeDropdownItems = document.querySelectorAll('.breakdance-dropdown-item--active');

    // Iterate over each active dropdown item
    activeDropdownItems.forEach(activeItem => {
        // Find the closest parent menu item
        let parentMenuItem = activeItem.closest(parentMenuItemClass);

        // If parent menu item exists, add new class
        if (parentMenuItem) {
            parentMenuItem.classList.add('breakdance-menu-item--active');
        }
    });
}

// Call the function on page load if required elements exist
document.addEventListener('DOMContentLoaded', function() {
    // Check if the required elements exist
    let menuItemsExist = document.querySelector('.breakdance-menu-list .menu-item');
    let menuCustomItemsExist = document.querySelector('.breakdance-menu-list .bde-menu-dropdown');

    // If the required elements exist, start the function for each type
    if (menuItemsExist) {
        setTimeout(() => updateParentMenuItems('.current-menu-parent'), 500); // Delay execution by 0.5 second
    }
    if (menuCustomItemsExist) {
        setTimeout(() => updateParentMenuItems('.bde-menu-dropdown'), 500); // Delay execution by 0.5 second
    }
});

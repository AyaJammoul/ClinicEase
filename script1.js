const allSideMenu = document.querySelectorAll('#sidebar .side-menu.top li');
const allContentSections = document.querySelectorAll('section[id$="-content"]');

allSideMenu.forEach(menuItem => {
    menuItem.addEventListener('click', function () {
        
        allSideMenu.forEach(item => {
            item.classList.remove('active');
        });

        menuItem.classList.add('active');

        allContentSections.forEach(section => {
            section.style.display = 'none';
        });

        const contentId = menuItem.id + '-content';
        const contentSection = document.getElementById(contentId);
        if (contentSection) {
            contentSection.style.display = 'block';
        }
    });
});

const defaultContent = document.getElementById('dashboard-content');
if (defaultContent) {
    defaultContent.style.display = 'block';
}

const menuBar = document.querySelector('#content nav .bx.bx-menu');
const sidebar = document.getElementById('sidebar');

menuBar.addEventListener('click', function () {
    sidebar.classList.toggle('hide');
})

// Comment out the search button logic
// const searchButton = document.querySelector('#content nav form .form-input button');
// const searchButtonIcon = document.querySelector('#content nav form .form-input button .bx');
// const searchForm = document.querySelector('#content nav form');

// searchButton.addEventListener('click', function (e) {
//     if(window.innerWidth < 576) {
//         e.preventDefault();
//         searchForm.classList.toggle('show');
//         if(searchForm.classList.contains('show')) {
//             searchButtonIcon.classList.replace('bx-search', 'bx-x');
//         } else {
//             searchButtonIcon.classList.replace('bx-x', 'bx-search');
//         }
//     }
// });

if(window.innerWidth < 768) {
    sidebar.classList.add('hide');
} else if(window.innerWidth > 576) {
    // searchButtonIcon.classList.replace('bx-x', 'bx-search');
    // searchForm.classList.remove('show');
}

window.addEventListener('resize', function () {
    if(this.innerWidth > 576) {
        // searchButtonIcon.classList.replace('bx-x', 'bx-search');
        // searchForm.classList.remove('show');
    }
})

const switchMode = document.getElementById('switch-mode');
const dynamicText = document.getElementById('dynamic-text');
const dynamicTitle = document.getElementById('dynamic-title');
const dynamicInput = document.getElementById('dynamic-input');

switchMode.addEventListener('change', function () {
    if (this.checked) {
        document.body.classList.add('dark');
        dynamicText.style.color = getComputedStyle(document.documentElement).getPropertyValue('--light');
        dynamicTitle.style.color = getComputedStyle(document.documentElement).getPropertyValue('--light');
        dynamicInput.style.background = getComputedStyle(document.documentElement).getPropertyValue('--dark');
    } else {
        document.body.classList.remove('dark');
        dynamicText.style.color = getComputedStyle(document.documentElement).getPropertyValue('--dark');
        dynamicTitle.style.color = getComputedStyle(document.documentElement).getPropertyValue('--dark');
        dynamicInput.style.background = getComputedStyle(document.documentElement).getPropertyValue('--dark-grey');
    }
});

/* custom JavaScript codes can be added here.
 * This file is disabled by default, to enable it open your functions.php file and uncomment the necessary lines.
 */
document.addEventListener('DOMContentLoaded', function () {
    var pageFilterInput = document.getElementById('page-filter');
    var pageCheckboxes = document.querySelectorAll('.page-checkbox');

    pageFilterInput.addEventListener('input', function () {
        var filterText = pageFilterInput.value.toLowerCase();
        console.log(filterText);
        pageCheckboxes.forEach(function (checkbox) {

            var labelText = checkbox.name.toLowerCase();
            if (labelText.includes(filterText)) {
                checkbox.parentNode.style.display = 'block';
            } else {
                checkbox.parentNode.style.display = 'none';
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('page-checkbox-form');
    var selectedValues = [];
    var src = 'https://calendar.google.com/calendar/embed?src='
    let child;

    form.addEventListener('change', function (event) {
        var checkedCheckboxes = document.querySelectorAll('.page-checkbox:checked');
        var calendarFrame = document.getElementById("calendar-container");


        // Iterate through the checked checkboxes and add their values to the array
        checkedCheckboxes.forEach(function (checkbox) {
            selectedValues.push(checkbox.value);
        });
        if (selectedValues.length > 0) {

            var newURL = src + selectedValues.join("&src=");
            child = document.createElement('iframe');
            child.id = 'calendar-id';
            child.width = '800';
            child.height = '600';
            child.frameBorder = '0';
            child.scrolling = 'no';
            child.src = newURL;
        } else {
            child = document.createElement('span');
            child.textContent = 'No param?';
            console.log('span');
        }
        calendarFrame.innerHTML = '';
        calendarFrame.appendChild(child);

    });
});
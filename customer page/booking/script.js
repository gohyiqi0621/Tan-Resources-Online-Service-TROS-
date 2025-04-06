document.addEventListener("DOMContentLoaded", function () {
    let now = new Date();
    let currentHours = now.getHours();
    
    // If it's after 9 PM, disable today and allow only future dates
    let minSelectableDate = new Date();
    if (currentHours >= 21) {
        minSelectableDate.setDate(minSelectableDate.getDate() + 1); // Move to next day
    }

    // Initialize Flatpickr for date selection
    const datePicker = flatpickr("#date", {
        enableTime: false,
        dateFormat: "Y-m-d",
        minDate: minSelectableDate, // Set minDate dynamically
        onChange: function (selectedDates) {
            updateMinTime(selectedDates[0]);
        }
    });

    // Initialize Flatpickr for time selection
    const timePicker = flatpickr("#time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "h:i K",
        minTime: "09:00", // Default 9:00 AM
        maxTime: "21:00", // 9:00 PM
        time_24hr: false
    });

    // Function to update minTime dynamically based on selected date
    function updateMinTime(selectedDate) {
        let selectedDay = new Date(selectedDate);
        let today = new Date();
        today.setHours(0, 0, 0, 0); // Reset to start of the day

        let minTime = "09:00"; // Default minTime

        if (selectedDay.getTime() === today.getTime()) {
            let now = new Date();
            let currentHours = now.getHours();
            let currentMinutes = now.getMinutes();

            // Ensure minTime is at least the current time
            if (currentHours < 9) {
                minTime = "09:00";
            } else if (currentHours < 21) {
                minTime = `${String(currentHours).padStart(2, '0')}:${String(currentMinutes).padStart(2, '0')}`;
            } else {
                minTime = "21:00"; // Restrict past 9 PM
            }
        }

        timePicker.set({ minTime: minTime });
    }
});

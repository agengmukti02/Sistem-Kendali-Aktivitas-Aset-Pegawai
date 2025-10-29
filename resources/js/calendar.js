import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';

document.addEventListener('DOMContentLoaded', function () {
    console.log('DOM loaded, looking for calendar...');

    const calendarEl = document.getElementById('calendar');
    if (!calendarEl) {
        console.error('Calendar element not found!');
        return;
    }

    console.log('Calendar element found, initializing...');

    // Get events data from the element
    let events = [];
    try {
        const eventsData = calendarEl.getAttribute('data-events');
        events = eventsData ? JSON.parse(eventsData) : [];
        console.log('Events loaded:', events);
    } catch (e) {
        console.error('Error parsing events:', e);
    }

    // Initialize calendar
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin],
        initialView: 'dayGridMonth',
        locale: 'id',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        events: events,
        height: 'auto',
        eventDisplay: 'block',
        eventBackgroundColor: '#3788d8',
        eventBorderColor: '#3788d8',
        eventDidMount: function (info) {
            console.log('Event mounted:', info.event.title);

            // Add tooltip
            info.el.setAttribute('title',
                `${info.event.title}\n${info.event.extendedProps.description || ''}`
            );
        },
        eventClick: function (info) {
            alert(`Event: ${info.event.title}\nDescription: ${info.event.extendedProps.description || 'No description'}`);
        }
    });

    try {
        calendar.render();
        console.log('Calendar rendered successfully!');
    } catch (error) {
        console.error('Error rendering calendar:', error);
    }
});

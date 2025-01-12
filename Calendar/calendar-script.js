const calendar = document.querySelector(".calendar"),
  date = document.querySelector(".date"),
  daysContainer = document.querySelector(".days"),
  prev = document.querySelector(".prev"),
  next = document.querySelector(".next"),
  todayBtn = document.querySelector(".today-btn"),
  gotoBtn = document.querySelector(".goto-btn"),
  dateInput = document.querySelector(".date-input"),
  eventDay = document.querySelector(".event-day"),
  eventDate = document.querySelector(".event-date"),
  eventsContainer = document.querySelector(".events"),
  addEventBtn = document.querySelector(".add-event"),
  addEventWrapper = document.querySelector(".add-event-wrapper"),
  addEventCloseBtn = document.querySelector(".close"),
  addEventTitle = document.querySelector(".event-name"),
  addEventFrom = document.querySelector(".event-time-from"),
  addEventTo = document.querySelector(".event-time-to"),
  addEventSubmit = document.querySelector(".add-event-btn");

let today = new Date();
let activeDay;
let month = today.getMonth();
let year = today.getFullYear();

const months = [
  "January",
  "February",
  "March",
  "April",
  "May",
  "June",
  "July",
  "August",
  "September",
  "October",
  "November",
  "December",
];

const eventsArr = [];

// Initialize calendar
function initCalendar() {
  const firstDay = new Date(year, month, 1);
  const lastDay = new Date(year, month + 1, 0);
  const prevLastDay = new Date(year, month, 0);
  const prevDays = prevLastDay.getDate();
  const lastDate = lastDay.getDate();
  const day = firstDay.getDay();
  const nextDays = 7 - lastDay.getDay() - 1;

  date.innerHTML = `${months[month]} ${year}`;

  let days = "";

  for (let x = day; x > 0; x--) {
    days += `<div class="day prev-date">${prevDays - x + 1}</div>`;
  }

  for (let i = 1; i <= lastDate; i++) {
    let event = eventsArr.some(
      (eventObj) =>
        eventObj.day === i && eventObj.month === month + 1 && eventObj.year === year
    );
    if (i === today.getDate() && year === today.getFullYear() && month === today.getMonth()) {
      activeDay = i;
      getActiveDay(i);
      updateEvents(i);
      days += `<div class="day today active ${event ? 'event' : ''}">${i}</div>`;
    } else {
      days += `<div class="day ${event ? 'event' : ''}">${i}</div>`;
    }
  }

  for (let j = 1; j <= nextDays; j++) {
    days += `<div class="day next-date">${j}</div>`;
  }
  daysContainer.innerHTML = days;
  addListeners();
}

function addListeners() {
  document.querySelectorAll(".day").forEach((day) => {
    day.addEventListener("click", (e) => {
      getActiveDay(e.target.innerHTML);
      updateEvents(Number(e.target.innerHTML));
      activeDay = Number(e.target.innerHTML);

      document.querySelectorAll(".day").forEach((day) => day.classList.remove("active"));
      e.target.classList.add("active");
    });
  });
}

function getActiveDay(date) {
  const day = new Date(year, month, date);
  const dayName = day.toString().split(" ")[0];
  eventDay.innerHTML = dayName;
  eventDate.innerHTML = `${date} ${months[month]} ${year}`;
}

function updateEvents(date) {
  let events = "";

  eventsArr.forEach((event) => {
    if (date === event.day && month + 1 === event.month && year === event.year) {
      event.events.forEach((eventItem) => {
        events += `<div class="event">
                      <div class="title">
                        <i class="fas fa-circle"></i>
                        <h3 class="event-title">${eventItem.title}</h3>
                      </div>
                      <div class="event-time">
                        <span class="event-time">${eventItem.time}</span>
                      </div>
                   </div>`;
      });
    }
  });

  if (events === "") {
    events = `<div class="no-event"><h3>No Events</h3></div>`;
  }

  eventsContainer.innerHTML = events;
}

async function fetchBookings() {
  try {
    eventsArr.length = 0; // Clear existing events
    const response = await fetch('http://localhost/Calendar/collect-bookings.php');
    const bookings = await response.json();

    bookings.forEach((booking) => {
      const appointmentDate = new Date(booking.appointment_date);
      const day = appointmentDate.getDate();
      const month = appointmentDate.getMonth() + 1;
      const year = appointmentDate.getFullYear();

      let eventFound = false;
      eventsArr.forEach((event) => {
        if (event.day === day && event.month === month && event.year === year) {
          const exists = event.events.some(
            (e) =>
              e.title === `${booking.first_name} ${booking.last_name}: ${booking.appointment_name}` &&
              e.time === booking.appointment_desc
          );

          if (!exists) {
            event.events.push({
              title: `${booking.first_name} ${booking.last_name}: ${booking.appointment_name}`,
              time: booking.appointment_desc,
            });
          }
          eventFound = true;
        }
      });

      if (!eventFound) {
        eventsArr.push({
          day,
          month,
          year,
          events: [
            {
              title: `${booking.first_name} ${booking.last_name}: ${booking.appointment_name}`,
              time: booking.appointment_desc,
            },
          ],
        });
      }
    });

    updateEvents(activeDay);
  } catch (error) {
    console.error('Error fetching bookings:', error);
  }
}

prev.addEventListener("click", () => {
  month--;
  if (month < 0) {
    month = 11;
    year--;
  }
  initCalendar();
});

next.addEventListener("click", () => {
  month++;
  if (month > 11) {
    month = 0;
    year++;
  }
  initCalendar();
});

todayBtn.addEventListener("click", () => {
  today = new Date();
  month = today.getMonth();
  year = today.getFullYear();
  initCalendar();
});

gotoBtn.addEventListener("click", () => {
  const dateArr = dateInput.value.split("/");
  if (dateArr.length === 2) {
    if (dateArr[0] > 0 && dateArr[0] < 13 && dateArr[1].length === 4) {
      month = dateArr[0] - 1;
      year = parseInt(dateArr[1]);
      initCalendar();
    } else {
      alert("Invalid Date");
    }
  }
});

initCalendar();
fetchBookings();
/**
 * timezone select
 * 
 * @class TimezonePicker
 * @constructor
*/
class TimezonePicker extends HTMLElement {
    constructor() {
        super()

        this.timeZone = [
            "(GMT-11:00) Pacific/Pago_Pago",
            "(GMT-10:00) Pacific/Honolulu",
            "(GMT-08:00) America/Los_Angeles",
            "(GMT-08:00) America/Tijuana",
            "(GMT-07:00) America/Denver",
            "(GMT-07:00) America/Phoenix",
            "(GMT-07:00) America/Mazatlan",
            "(GMT-06:00) America/Chicago",
            "(GMT-06:00) America/Mexico_City",
            "(GMT-06:00) America/Regina",
            "(GMT-06:00) America/Guatemala",
            "(GMT-05:00) America/Bogota",
            "(GMT-05:00) America/New_York",
            "(GMT-05:00) America/Lima",
            "(GMT-04:30) America/Caracas",
            "(GMT-04:00) America/Halifax",
            "(GMT-04:00) America/Guyana",
            "(GMT-04:00) America/La_Paz",
            "(GMT-03:00) America/Argentina/Buenos_Aires",
            "(GMT-03:00) America/Godthab",
            "(GMT-03:00) America/Montevideo",
            "(GMT-03:30) America/St_Johns",
            "(GMT-03:00) America/Santiago",
            "(GMT-02:00) America/Sao_Paulo",
            "(GMT-02:00) Atlantic/South_Georgia",
            "(GMT-01:00) Atlantic/Azores",
            "(GMT-01:00) Atlantic/Cape_Verde",
            "(GMT+00:00) Africa/Abidjan",
            "(GMT+00:00) Africa/Casablanca",
            "(GMT+00:00) Europe/Dublin",
            "(GMT+00:00) Europe/Lisbon",
            "(GMT+00:00) Europe/London",
            "(GMT+00:00) UTC",
            "(GMT+00:00) GMT",
            "(GMT+00:00) Africa/Monrovia",
            "(GMT+01:00) Africa/Algiers",
            "(GMT+01:00) Europe/Amsterdam",
            "(GMT+01:00) Europe/Berlin",
            "(GMT+01:00) Europe/Brussels",
            "(GMT+01:00) Europe/Budapest",
            "(GMT+01:00) Europe/Belgrade",
            "(GMT+01:00) Europe/Prague",
            "(GMT+01:00) Europe/Copenhagen",
            "(GMT+01:00) Europe/Madrid",
            "(GMT+01:00) Europe/Paris",
            "(GMT+01:00) Europe/Rome",
            "(GMT+01:00) Europe/Stockholm",
            "(GMT+01:00) Europe/Vienna",
            "(GMT+01:00) Europe/Warsaw",
            "(GMT+02:00) Europe/Athens",
            "(GMT+02:00) Europe/Bucharest",
            "(GMT+02:00) Africa/Cairo",
            "(GMT+02:00) Asia/Jerusalem",
            "(GMT+02:00) Africa/Johannesburg",
            "(GMT+02:00) Europe/Helsinki",
            "(GMT+02:00) Europe/Kiev",
            "(GMT+02:00) Europe/Kaliningrad",
            "(GMT+02:00) Europe/Riga",
            "(GMT+02:00) Europe/Sofia",
            "(GMT+02:00) Europe/Tallinn",
            "(GMT+02:00) Europe/Vilnius",
            "(GMT+03:00) Europe/Istanbul",
            "(GMT+03:00) Asia/Baghdad",
            "(GMT+03:00) Africa/Nairobi",
            "(GMT+03:00) Europe/Minsk",
            "(GMT+03:00) Asia/Riyadh",
            "(GMT+03:00) Europe/Moscow",
            "(GMT+03:30) Asia/Tehran",
            "(GMT+04:00) Asia/Baku",
            "(GMT+04:00) Europe/Samara",
            "(GMT+04:00) Asia/Tbilisi",
            "(GMT+04:00) Asia/Yerevan",
            "(GMT+04:30) Asia/Kabul",
            "(GMT+05:00) Asia/Karachi",
            "(GMT+05:00) Asia/Yekaterinburg",
            "(GMT+05:00) Asia/Tashkent",
            "(GMT+05:30) Asia/Colombo",
            "(GMT+06:00) Asia/Almaty",
            "(GMT+06:00) Asia/Dhaka",
            "(GMT+06:30) Asia/Rangoon",
            "(GMT+07:00) Asia/Bangkok",
            "(GMT+07:00) Asia/Jakarta",
            "(GMT+07:00) Asia/Krasnoyarsk",
            "(GMT+08:00) Asia/Shanghai",
            "(GMT+08:00) Asia/Hong_Kong",
            "(GMT+08:00) Asia/Kuala_Lumpur",
            "(GMT+08:00) Asia/Irkutsk",
            "(GMT+08:00) Asia/Singapore",
            "(GMT+08:00) Asia/Taipei",
            "(GMT+08:00) Asia/Ulaanbaatar",
            "(GMT+08:00) Australia/Perth",
            "(GMT+09:00) Asia/Yakutsk",
            "(GMT+09:00) Asia/Seoul",
            "(GMT+09:00) Asia/Tokyo",
            "(GMT+09:30) Australia/Darwin",
            "(GMT+10:00) Australia/Brisbane",
            "(GMT+10:00) Pacific/Guam",
            "(GMT+10:00) Asia/Magadan",
            "(GMT+10:00) Yuzhno-Asia/Vladivostok",
            "(GMT+10:00) Pacific/Port_Moresby",
            "(GMT+10:30) Australia/Adelaide",
            "(GMT+11:00) Australia/Hobart",
            "(GMT+11:00) Australia/Sydney",
            "(GMT+11:00) Pacific/Guadalcanal",
            "(GMT+11:00) Pacific/Noumea",
            "(GMT+12:00) Pacific/Majuro",
            "(GMT+12:00) Asia/Kamchatka",
            "(GMT+13:00) Pacific/Auckland",
            "(GMT+13:00) Pacific/Fakaofo",
            "(GMT+13:00) Pacific/Fiji",
            "(GMT+13:00) Pacific/Tongatapu",
            "(GMT+14:00) Pacific/Apia"
        ]
    }

    connectedCallback() {
        this.innerHTML = `
            <select id="timezone" name="timezone" class="custom-select">
                ${this.timeZone.map(name => {
                    let timezone = name.split(' ')[1]
                    let selected = this.getAttribute('selected') === timezone ? 'selected' : ''
                    return '<option value="' + timezone + '" ' + selected + '>' + name + '</option>'
                })}
            </select>
        `
    }
}

export default TimezonePicker
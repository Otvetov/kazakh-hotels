<script>
let guestsCount = {{ request('guests', 2) }};
let roomsCount = {{ request('rooms', 1) }};

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';

        if (id === 'guestsModal') {
            const guestsCountEl = document.getElementById('guestsCount');
            const roomsCountEl = document.getElementById('roomsCount');
            if (guestsCountEl) guestsCount = parseInt(guestsCountEl.textContent) || 2;
            if (roomsCountEl) roomsCount = parseInt(roomsCountEl.textContent) || 1;

            const guestsMinusBtn = document.getElementById('guestsMinusBtn');
            const guestsPlusBtn = document.getElementById('guestsPlusBtn');
            const roomsMinusBtn = document.getElementById('roomsMinusBtn');
            const roomsPlusBtn = document.getElementById('roomsPlusBtn');

            if (guestsMinusBtn) guestsMinusBtn.disabled = guestsCount <= 1;
            if (guestsPlusBtn) guestsPlusBtn.disabled = guestsCount >= 10;
            if (roomsMinusBtn) roomsMinusBtn.disabled = roomsCount <= 1;
            if (roomsPlusBtn) roomsPlusBtn.disabled = roomsCount >= 5;
        }

        if (id === 'dateModal') {
            initCalendar();
        }
    }
}

function closeModals() {
    document.querySelectorAll('.modal').forEach(m => {
        m.classList.add('hidden');
        m.style.display = 'none';
    });
    document.body.style.overflow = '';
}

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModals();
    }
});

/* ---------- Направление ---------- */
function selectCityAndClose(city) {
    const cityValueHotels = document.getElementById('cityValueHotels');
    const cityInputHotels = document.getElementById('cityInputHotels');

    if (cityValueHotels && cityInputHotels) {
        cityValueHotels.textContent = city;
        cityInputHotels.value = city;
    } else {
        const cityValue = document.getElementById('cityValue');
        const cityInput = document.getElementById('cityInput');
        if (cityValue) cityValue.textContent = city;
        if (cityInput) cityInput.value = city;
    }
    closeModals();
}

/* ---------- Календарь дат ---------- */
const MONTHS_RU = ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'];
let calView = new Date();           // отображаемый месяц
let calCheckIn = null;              // Date
let calCheckOut = null;             // Date

function calFmt(d) {
    return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
}
function calStripTime(d) {
    return new Date(d.getFullYear(), d.getMonth(), d.getDate());
}
function calParse(str) {
    if (!str) return null;
    const p = str.split('-');
    if (p.length !== 3) return null;
    return new Date(+p[0], +p[1] - 1, +p[2]);
}

function initCalendar() {
    const ci = document.getElementById('checkIn');
    const co = document.getElementById('checkOut');
    calCheckIn = calParse(ci ? ci.value : '');
    calCheckOut = calParse(co ? co.value : '');
    calView = calCheckIn ? new Date(calCheckIn.getFullYear(), calCheckIn.getMonth(), 1)
                         : new Date(new Date().getFullYear(), new Date().getMonth(), 1);
    renderCalendar();
}

function calPrevMonth() {
    const today = calStripTime(new Date());
    const firstOfThisMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    const candidate = new Date(calView.getFullYear(), calView.getMonth() - 1, 1);
    if (candidate >= firstOfThisMonth) {
        calView = candidate;
        renderCalendar();
    }
}
function calNextMonth() {
    calView = new Date(calView.getFullYear(), calView.getMonth() + 1, 1);
    renderCalendar();
}

function calClear() {
    calCheckIn = null;
    calCheckOut = null;
    const ci = document.getElementById('checkIn');
    const co = document.getElementById('checkOut');
    if (ci) ci.value = '';
    if (co) co.value = '';
    renderCalendar();
}

function calOnDay(dateStr) {
    const d = calParse(dateStr);
    if (!calCheckIn || (calCheckIn && calCheckOut)) {
        calCheckIn = d;
        calCheckOut = null;
    } else if (d > calCheckIn) {
        calCheckOut = d;
    } else {
        calCheckIn = d;
        calCheckOut = null;
    }
    // синхронизируем скрытые поля
    const ci = document.getElementById('checkIn');
    const co = document.getElementById('checkOut');
    if (ci) ci.value = calCheckIn ? calFmt(calCheckIn) : '';
    if (co) co.value = calCheckOut ? calFmt(calCheckOut) : '';
    renderCalendar();
}

function renderCalendar() {
    const label = document.getElementById('calMonthLabel');
    const grid = document.getElementById('calDays');
    const hint = document.getElementById('calHint');
    const prevBtn = document.getElementById('calPrev');
    if (!grid) return;

    label.textContent = MONTHS_RU[calView.getMonth()] + ' ' + calView.getFullYear();

    const today = calStripTime(new Date());
    const firstOfThisMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    if (prevBtn) prevBtn.disabled = (new Date(calView.getFullYear(), calView.getMonth(), 1) <= firstOfThisMonth);

    const year = calView.getFullYear();
    const month = calView.getMonth();
    const firstDay = new Date(year, month, 1);
    // смещение: неделя начинается с понедельника
    let offset = firstDay.getDay() - 1;
    if (offset < 0) offset = 6;
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    let html = '';
    for (let i = 0; i < offset; i++) {
        html += '<span class="cal-empty"></span>';
    }
    for (let day = 1; day <= daysInMonth; day++) {
        const d = new Date(year, month, day);
        const ds = calFmt(d);
        const dow = d.getDay(); // 0=вс,6=сб
        const isWeekend = (dow === 0 || dow === 6);
        const isPast = d < today;

        let cls = 'cal-day';
        if (isPast) {
            cls += ' cal-disabled';
        } else {
            if (isWeekend) cls += ' cal-weekend';
            const isStart = calCheckIn && d.getTime() === calCheckIn.getTime();
            const isEnd = calCheckOut && d.getTime() === calCheckOut.getTime();
            const inRange = calCheckIn && calCheckOut && d > calCheckIn && d < calCheckOut;
            if (isStart || isEnd) cls += ' cal-selected';
            else if (inRange) cls += ' cal-inrange';
        }

        if (isPast) {
            html += `<span class="${cls}">${day}</span>`;
        } else {
            html += `<button type="button" class="${cls}" onclick="calOnDay('${ds}')">${day}</button>`;
        }
    }
    grid.innerHTML = html;

    if (hint) {
        if (calCheckIn && calCheckOut) {
            const opt = { day: 'numeric', month: 'short' };
            hint.textContent = calCheckIn.toLocaleDateString('ru-RU', opt) + ' – ' + calCheckOut.toLocaleDateString('ru-RU', opt);
        } else if (calCheckIn) {
            hint.textContent = 'Выберите дату выезда';
        } else {
            hint.textContent = 'Выберите дату заезда';
        }
    }
}

function saveDates() {
    const checkIn = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;

    if (checkIn && checkOut) {
        const checkInDate = calParse(checkIn);
        const checkOutDate = calParse(checkOut);
        const options = { day: 'numeric', month: 'short' };

        const dateValueHotels = document.getElementById('dateValueHotels');
        const checkInInputHotels = document.getElementById('checkInInputHotels');
        const checkOutInputHotels = document.getElementById('checkOutInputHotels');

        if (dateValueHotels && checkInInputHotels && checkOutInputHotels) {
            dateValueHotels.textContent = checkInDate.toLocaleDateString('ru-RU', options) + ' – ' + checkOutDate.toLocaleDateString('ru-RU', options);
            checkInInputHotels.value = checkIn;
            checkOutInputHotels.value = checkOut;
        } else {
            const dateValue = document.getElementById('dateValue');
            const checkInInput = document.getElementById('checkInInput');
            const checkOutInput = document.getElementById('checkOutInput');
            if (dateValue) dateValue.textContent = checkInDate.toLocaleDateString('ru-RU', options) + ' – ' + checkOutDate.toLocaleDateString('ru-RU', options);
            if (checkInInput) checkInInput.value = checkIn;
            if (checkOutInput) checkOutInput.value = checkOut;
        }
        closeModals();
    }
}

/* ---------- Гости и номера ---------- */
function changeGuests(delta) {
    guestsCount = Math.max(1, Math.min(10, guestsCount + delta));
    const guestsCountEl = document.getElementById('guestsCount');
    if (guestsCountEl) guestsCountEl.textContent = guestsCount;
    const minusBtn = document.getElementById('guestsMinusBtn');
    const plusBtn = document.getElementById('guestsPlusBtn');
    if (minusBtn) minusBtn.disabled = guestsCount <= 1;
    if (plusBtn) plusBtn.disabled = guestsCount >= 10;
}

function changeRooms(delta) {
    roomsCount = Math.max(1, Math.min(5, roomsCount + delta));
    const roomsCountEl = document.getElementById('roomsCount');
    if (roomsCountEl) roomsCountEl.textContent = roomsCount;
    const minusBtn = document.getElementById('roomsMinusBtn');
    const plusBtn = document.getElementById('roomsPlusBtn');
    if (minusBtn) minusBtn.disabled = roomsCount <= 1;
    if (plusBtn) plusBtn.disabled = roomsCount >= 5;
}

function saveGuests() {
    const guestsText = guestsCount === 1 ? 'гость' : 'гостей';
    const roomsText = roomsCount === 1 ? 'номер' : 'номеров';
    const valueText = `${guestsCount} ${guestsText}, ${roomsCount} ${roomsText}`;

    const guestsValue = document.getElementById('guestsValue');
    const guestsInput = document.getElementById('guestsInput');
    const roomsInput = document.getElementById('roomsInput');
    if (guestsValue) guestsValue.textContent = valueText;
    if (guestsInput) guestsInput.value = guestsCount;
    if (roomsInput) roomsInput.value = roomsCount;

    const guestsValueHotels = document.getElementById('guestsValueHotels');
    const guestsInputHotels = document.getElementById('guestsInputHotels');
    const roomsInputHotels = document.getElementById('roomsInputHotels');
    if (guestsValueHotels) guestsValueHotels.textContent = valueText;
    if (guestsInputHotels) guestsInputHotels.value = guestsCount;
    if (roomsInputHotels) roomsInputHotels.value = roomsCount;

    closeModals();
}

/* ---------- Валидация формы поиска ---------- */
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchError = document.getElementById('searchError');

    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const city = document.getElementById('cityInput').value.trim();
            const checkIn = document.getElementById('checkInInput').value;
            const checkOut = document.getElementById('checkOutInput').value;

            const errors = [];
            if (!city || city === 'Выберите направление') errors.push('Выберите направление');
            if (!checkIn) errors.push('Выберите дату заезда');
            if (!checkOut) errors.push('Выберите дату выезда');

            if (errors.length > 0) {
                e.preventDefault();
                searchError.textContent = 'Пожалуйста, заполните следующие поля: ' + errors.join(', ');
                searchError.classList.remove('hidden');
                searchError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                return false;
            } else {
                searchError.classList.add('hidden');
            }
        });
    }
});

/* ---------- Поиск города (AJAX) и подгрузка ---------- */
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('citySelect');
    const searchResults = document.getElementById('search-results');
    const popularCities = document.getElementById('popular-cities');
    const citiesListResults = document.getElementById('cities-list-results');

    if (citySelect) {
        let searchTimeout;
        citySelect.addEventListener('input', function(e) {
            const searchTerm = e.target.value.trim();
            clearTimeout(searchTimeout);

            if (!searchTerm) {
                searchResults.classList.add('hidden');
                popularCities.classList.remove('hidden');
                return;
            }

            searchTimeout = setTimeout(() => {
                citiesListResults.innerHTML = '<div class="text-center py-4 text-[#7e8488]">Поиск...</div>';
                searchResults.classList.remove('hidden');
                popularCities.classList.add('hidden');

                fetch(`{{ route('home') }}?city=${encodeURIComponent(searchTerm)}&ajax=1`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.cities && data.cities.length > 0) {
                        citiesListResults.innerHTML = data.cities.map(city => `
                            <button onclick="selectCityAndClose('${city}')" class="w-full flex items-center gap-3 p-3 hover:bg-white/5 rounded-2xl transition-colors text-left">
                                <span class="w-10 h-10 flex items-center justify-center bg-[#141516] rounded-full flex-shrink-0">
                                    <svg class="w-5 h-5 text-[#8ee30f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </span>
                                <span class="text-white font-medium">${city}</span>
                            </button>
                        `).join('');
                    } else {
                        citiesListResults.innerHTML = '<div class="text-center py-4 text-[#7e8488]">Ничего не найдено</div>';
                    }
                })
                .catch(error => {
                    console.error('Error searching cities:', error);
                    citiesListResults.innerHTML = '<div class="text-center py-4 text-[#7e8488]">Ошибка поиска</div>';
                });
            }, 300);
        });
    }

    // Close modals on outside click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) closeModals();
        });
    });

    // Load more hotels
    const loadMoreBtn = document.getElementById('load-more');
    if (loadMoreBtn) {
        let page = 2;
        let isLoading = false;

        loadMoreBtn.addEventListener('click', function() {
            if (isLoading) return;
            isLoading = true;
            loadMoreBtn.disabled = true;
            const originalContent = loadMoreBtn.innerHTML;
            loadMoreBtn.innerHTML = '<span>Загрузка...</span>';

            fetch(`{{ route('home') }}?page=${page}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('hotels-container');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;

                const newCards = Array.from(tempDiv.children);
                newCards.forEach((card, index) => {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    container.appendChild(card);
                    setTimeout(() => {
                        card.style.transition = 'all 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, index * 50);
                });

                if (!data.has_more || !data.html) {
                    loadMoreBtn.remove();
                } else {
                    page++;
                    isLoading = false;
                    loadMoreBtn.disabled = false;
                    loadMoreBtn.innerHTML = originalContent;
                }
            })
            .catch(error => {
                console.error('Error loading hotels:', error);
                isLoading = false;
                loadMoreBtn.disabled = false;
                loadMoreBtn.innerHTML = originalContent;
            });
        });
    }
});
</script>

<style>
.modal {
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.7);
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
}

.modal.hidden {
    display: none !important;
}

.modal:not(.hidden) {
    display: flex;
    animation: modalFadeIn 0.25s ease-out;
}

.modal-box {
    background: #1b1c1d;
    color: #fafafa;
    padding: 0;
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 24px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalSlideIn 0.25s ease-out;
    box-shadow: 0 24px 50px -12px rgba(0, 0, 0, 0.6);
}

@keyframes modalFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(-12px) scale(0.98); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

.search-btn { transition: all 0.2s ease; }
.search-btn:hover { border-color: #8ee30f; }

.label { font-size: 12px; color: #7e8488; }
.value { font-weight: 500; color: #fafafa; }

/* Календарь */
.cal-day {
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 12px;
    font-size: .9rem;
    color: #fafafa;
    background: transparent;
    cursor: pointer;
    transition: background-color .12s ease;
}
.cal-day:hover { background: rgba(255, 255, 255, 0.08); }
.cal-weekend { color: #f04141; }
.cal-disabled { color: #3a3b3c; cursor: not-allowed; }
.cal-disabled:hover { background: transparent; }
.cal-inrange { background: rgba(142, 227, 15, 0.15); border-radius: 0; }
.cal-selected { background: #8ee30f; color: #0a0a0a; font-weight: 600; }
.cal-selected:hover { background: #8ee30f; }
.cal-empty { height: 40px; }
</style>

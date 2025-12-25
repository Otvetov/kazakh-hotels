<script>
let guestsCount = {{ request('guests', 2) }};
let roomsCount = {{ request('rooms', 1) }};

function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
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

function selectCity(city) {
    const citySelect = document.getElementById('citySelect');
    if (citySelect) {
        citySelect.value = city;
    }
}

function saveCity() {
    const citySelect = document.getElementById('citySelect');
    const city = citySelect.value.trim();
    
    if (city) {
        document.getElementById('cityValue').textContent = city;
        document.getElementById('cityInput').value = city;
        closeModals();
    }
}

function saveDates() {
    const checkIn = document.getElementById('checkIn').value;
    const checkOut = document.getElementById('checkOut').value;
    
    if (checkIn && checkOut) {
        const checkInDate = new Date(checkIn);
        const checkOutDate = new Date(checkOut);
        const options = { day: 'numeric', month: 'short' };
        
        document.getElementById('dateValue').textContent = 
            checkInDate.toLocaleDateString('ru-RU', options) + ' – ' + 
            checkOutDate.toLocaleDateString('ru-RU', options);
        
        document.getElementById('checkInInput').value = checkIn;
        document.getElementById('checkOutInput').value = checkOut;
        closeModals();
    }
}

function changeGuests(delta) {
    guestsCount = Math.max(1, Math.min(10, guestsCount + delta));
    document.getElementById('guestsCount').textContent = guestsCount;
    // Update button states
    const minusBtn = event.target.closest('button').parentElement.querySelector('button:first-child');
    const plusBtn = event.target.closest('button').parentElement.querySelector('button:last-child');
    if (minusBtn) minusBtn.disabled = guestsCount <= 1;
    if (plusBtn) plusBtn.disabled = guestsCount >= 10;
}

function changeRooms(delta) {
    roomsCount = Math.max(1, Math.min(5, roomsCount + delta));
    document.getElementById('roomsCount').textContent = roomsCount;
    // Update button states
    const minusBtn = event.target.closest('button').parentElement.querySelector('button:first-child');
    const plusBtn = event.target.closest('button').parentElement.querySelector('button:last-child');
    if (minusBtn) minusBtn.disabled = roomsCount <= 1;
    if (plusBtn) plusBtn.disabled = roomsCount >= 5;
}

function selectCityAndClose(city) {
    document.getElementById('cityValue').textContent = city;
    document.getElementById('cityInput').value = city;
    closeModals();
}

function saveGuests() {
    const guestsText = guestsCount === 1 ? 'гость' : 'гостей';
    const roomsText = roomsCount === 1 ? 'номер' : 'номеров';
    
    document.getElementById('guestsValue').textContent = 
        `${guestsCount} ${guestsText}, ${roomsCount} ${roomsText}`;
    
    document.getElementById('guestsInput').value = guestsCount;
    document.getElementById('roomsInput').value = roomsCount;
    closeModals();
}

// Form validation
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchError = document.getElementById('searchError');
    
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const city = document.getElementById('cityInput').value.trim();
            const checkIn = document.getElementById('checkInInput').value;
            const checkOut = document.getElementById('checkOutInput').value;
            
            const errors = [];
            
            if (!city || city === 'Выберите направление') {
                errors.push('Выберите направление');
            }
            
            if (!checkIn) {
                errors.push('Выберите дату заезда');
            }
            
            if (!checkOut) {
                errors.push('Выберите дату выезда');
            }
            
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

document.addEventListener('DOMContentLoaded', function() {
    // City search filter with AJAX
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
                // Show loading
                citiesListResults.innerHTML = '<div class="text-center py-4 text-gray-500">Поиск...</div>';
                searchResults.classList.remove('hidden');
                popularCities.classList.add('hidden');
                
                // Fetch cities from server
                fetch(`{{ route('home') }}?city=${encodeURIComponent(searchTerm)}&ajax=1`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.cities && data.cities.length > 0) {
                        citiesListResults.innerHTML = data.cities.map(city => `
                            <button
                                onclick="selectCityAndClose('${city}')"
                                class="w-full flex items-center gap-3 p-3 hover:bg-gray-50 rounded-lg transition-colors text-left"
                            >
                                <svg class="w-5 h-5 text-[#38b000]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-gray-900">${city}</span>
                            </button>
                        `).join('');
                    } else {
                        citiesListResults.innerHTML = '<div class="text-center py-4 text-gray-500">Ничего не найдено</div>';
                    }
                })
                .catch(error => {
                    console.error('Error searching cities:', error);
                    citiesListResults.innerHTML = '<div class="text-center py-4 text-gray-500">Ошибка поиска</div>';
                });
            }, 300);
        });
    }

    // Update check-out min date when check-in changes
    const checkIn = document.getElementById('checkIn');
    const checkOut = document.getElementById('checkOut');
    if (checkIn && checkOut) {
        checkIn.addEventListener('change', function() {
            if (this.value) {
                const minDate = new Date(this.value);
                minDate.setDate(minDate.getDate() + 1);
                checkOut.min = minDate.toISOString().split('T')[0];
                if (checkOut.value && checkOut.value <= this.value) {
                    checkOut.value = '';
                }
            }
        });
    }

    // Close modals on outside click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModals();
            }
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
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
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
    background: rgba(0, 0, 0, 0.5);
    align-items: flex-start;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
    padding-top: 5rem;
}

.modal.hidden {
    display: none !important;
}

.modal:not(.hidden) {
    display: flex;
    animation: modalFadeIn 0.3s ease-out;
}

.modal-box {
    background: white;
    padding: 24px;
    border-radius: 16px;
    width: 100%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalSlideIn 0.3s ease-out;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.search-btn {
    transition: all 0.2s ease;
}

.search-btn:hover {
    border-color: #38b000;
}

.label {
    font-size: 12px;
    color: #6b7280;
}

.value {
    font-weight: 500;
    color: #111827;
}
</style>


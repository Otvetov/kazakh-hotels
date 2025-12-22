<script>
let guestsCount = {{ request('guests', 2) }};
let roomsCount = {{ request('rooms', 1) }};

function openModal(id) {
    document.getElementById(id).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModals() {
    document.querySelectorAll('.modal').forEach(m => m.classList.add('hidden'));
    document.body.style.overflow = '';
}

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
    guestsCount = Math.max(1, guestsCount + delta);
    document.getElementById('guestsCount').textContent = guestsCount;
}

function changeRooms(delta) {
    roomsCount = Math.max(1, roomsCount + delta);
    document.getElementById('roomsCount').textContent = roomsCount;
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

document.addEventListener('DOMContentLoaded', function() {
    // City search filter
    const citySelect = document.getElementById('citySelect');
    if (citySelect) {
        citySelect.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cities = document.querySelectorAll('.city-option');
            cities.forEach(city => {
                const cityName = city.textContent.toLowerCase();
                city.style.display = cityName.includes(searchTerm) ? 'block' : 'none';
            });
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
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 50;
    padding: 1rem;
}

.modal-box {
    background: white;
    padding: 24px;
    border-radius: 16px;
    width: 100%;
    max-width: 400px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalFadeIn 0.2s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
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


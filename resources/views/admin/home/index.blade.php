@extends('base')

@section('content')
    <div class="container mx-auto py-4 flex flex-col justify-center bg-white p-8 rounded-md">
        <h2 class="text-2xl font-semibold mb-4">Calendrier des Commandes</h2>
        <div class="flex flex-col gap-8">
            <div class="flex justify-around">
                <p class="text-lg font-semibold bg-slate-100 rounded-md p-4">Nombre total de commandes :
                    <span class="text-pink-600">{{ count($events) }}</span>
                </p>
                <p class="text-lg font-semibold bg-slate-100 rounded-md p-4">Nombre de produits moyens / commandes :
                    <span class="text-pink-600">{{ $averageProductsPerOrder }}</span>
                </p>
            </div>
            <div class="bg-slate-100 p-8 rounded-md overflow-hidden">
                <div class="order-slider">
                    <p class="text-md font-semibold mb-4">Prochaines commandes à traiter</p>
                    <div class="slider flex gap-4 overflow-x-auto pb-6">
                        @foreach ($events as $event)
                            @if ($event['changeOver'] > 0)
                                <div
                                    class="slider-item flex-none w-48 p-4 border border-yellow-500 rounded-lg bg-yellow-100 flex flex-col justify-between">
                                    <div class="flex flex-col gap-1">
                                        <h4 class="text-sm font-semibold text-yellow-600">Changement de type</h4>
                                        <p class="text-sm text-gray-600">Durée : {{ $event['changeOver'] }} minutes</p>
                                    </div>
                                </div>
                            @endif
                            <div
                                class="slider-item flex-none w-1/3 p-4 border bg-white rounded-lg flex flex-col justify-between">
                                <div class="flex flex-col gap-1">
                                    <div class="flex justify-between">
                                        <h4 class="text-lg font-semibold max-w-48">{{ $event['order']->client->name }}
                                        </h4>
                                        <div class="flex items-center gap-2">
                                            <p>{{ $event['type'] }}</p>
                                            <div class="h-4 w-4 rounded"
                                                style="background-color: {{ $event['backgroundColor'] }}">
                                            </div>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 font-semibold">Produit(s):
                                    </p>
                                    <p class="text-sm">{{ $event['productList'] }}</p>
                                    <div class="flex gap-1">
                                        <p class="text-sm text-gray-600 font-semibold">Date limite:</p>
                                        <p class="text-sm">{{ $event['order']->deadline->format('d M Y') }}</p>
                                    </div>
                                    <div class="flex gap-1">
                                        <p class="text-sm text-gray-600 font-semibold">Durée de production:</p>
                                        <p class="text-sm">{{ $event['productionDuration'] }} minutes</p>
                                    </div>
                                </div>
                                <a href="{{ $event['url'] }}" class="text-pink-500 mt-auto">Modifier</a>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

            <div id="calendar"></div>
        </div>

    </div>

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@3.2.0/main.css" rel="stylesheet" />

    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            var events = @json($events);
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'timeGridWeek',
                events: events,
                locale: 'fr',
                eventClick: function(info) {
                    window.location.href = info.event.url;
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine',
                    day: 'Jour'
                },
                weekends: false, // Hide Saturday and Sunday columns
                businessHours: {
                    // Highlight business hours from 8 AM to 6 PM, Monday to Friday
                    daysOfWeek: [1, 2, 3, 4, 5], // Monday to Friday
                    startTime: '08:00', // Start time
                    endTime: '18:00' // End time
                },
                minTime: '08:00', // Display calendar starting from 8 AM
                maxTime: '18:00' // Display calendar up to 6 PM
            });

            calendar.render();

            const slider = document.querySelector('.slider');
            const sliderItems = document.querySelectorAll('.slider-item');
            let currentIndex = 0;

            function nextSlide() {
                if (currentIndex < sliderItems.length - 1) {
                    currentIndex++;
                    updateSlider();
                }
            }

            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            }

            function updateSlider() {
                const offset = sliderItems[currentIndex].offsetLeft - slider.offsetLeft;
                slider.scrollTo({
                    left: offset,
                    behavior: 'smooth'
                });
            }

            const nav = document.createElement('div');
            nav.classList.add('flex', 'justify-between', 'mt-4');
            nav.innerHTML = `
                <button id="prevBtn" class="bg-pink-300 text-pink-900 py-2 px-4 rounded-md">Précédent</button>
                <button id="nextBtn" class="bg-pink-300 text-pink-900 py-2 px-4 rounded-md">Suivant</button>
            `;
            slider.parentNode.appendChild(nav);

            document.getElementById('nextBtn').addEventListener('click', nextSlide);
            document.getElementById('prevBtn').addEventListener('click', prevSlide);
        });
    </script>
@endsection

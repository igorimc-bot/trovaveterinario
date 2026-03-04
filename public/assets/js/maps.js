console.log('Maps.js loaded - version: 1.2 (detail-fallback)');

let map;
let markers = [];
let infoWindow;

async function initMap() {
    const mapElement = document.getElementById('map');
    if (!mapElement) return;

    const query = mapElement.dataset.query;
    const locationName = mapElement.dataset.location;
    const fullQuery = `${query} veterinario in ${locationName}`;

    // Default center (Italy)
    let center = { lat: 41.8719, lng: 12.5674 };

    // Load libraries
    const { Map, InfoWindow } = await google.maps.importLibrary("maps");
    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
    const { Place } = await google.maps.importLibrary("places");

    map = new Map(mapElement, {
        zoom: 12,
        center: center,
        mapId: 'DEMO_MAP_ID', // Required for AdvancedMarkerElement, replace with your Map ID if you have one
    });

    infoWindow = new InfoWindow();

    const request = {
        textQuery: fullQuery,
        fields: ['displayName', 'location', 'formattedAddress', 'rating', 'userRatingCount', 'id'],
        language: 'it',
    };

    try {
        const { places } = await Place.searchByText(request);

        if (places && places.length > 0) {
            await renderResults(places, AdvancedMarkerElement);
            if (places[0].location) {
                map.setCenter(places[0].location);
            }
        } else {
            document.getElementById('places-list').innerHTML = '<div class="text-muted text-center p-4">Nessun risultato trovato nelle vicinanze.</div>';
        }
    } catch (error) {
        document.getElementById('places-list').innerHTML = '<div class="text-muted text-center p-4">Errore durante la ricerca dei risultati.</div>';
        console.error('Places Search failed:', error);
    }
}

async function renderResults(places, AdvancedMarkerElement) {
    const listContainer = document.getElementById('places-list');
    listContainer.innerHTML = '';
    console.log('Starting renderResults for', places.length, 'places');

    for (let i = 0; i < places.length; i++) {
        const searchPlace = places[i];

        try {
            console.log(`Processing [${i}]: ${searchPlace.displayName}`);

            // 1. Try modern fetchFields (Note: fields are case-sensitive)
            try {
                await searchPlace.fetchFields({
                    fields: ['nationalPhoneNumber', 'internationalPhoneNumber', 'websiteURI']
                });
                console.log(`Modern fetch OK for ${searchPlace.displayName}`);
            } catch (modernError) {
                console.warn(`Modern fetchFields failed for ${searchPlace.displayName}. Error:`, modernError);

                // 2. Try Legacy Fallback if modern fails (might not work for new 2025 projects)
                console.log(`Attempting legacy fallback for ${searchPlace.displayName}...`);
                const legacyData = await getLegacyDetails(searchPlace.id);
                if (legacyData) {
                    console.log(`Legacy fallback SUCCESS for ${searchPlace.displayName}`);
                    searchPlace.nationalPhoneNumber = legacyData.formatted_phone_number;
                    searchPlace.websiteURI = legacyData.website;
                }
            }

            // Create Marker
            if (searchPlace.location) {
                const marker = new AdvancedMarkerElement({
                    position: searchPlace.location,
                    map: map,
                    title: searchPlace.displayName,
                });
                markers.push(marker);

                // Create Item UI
                const item = document.createElement('div');
                item.className = 'place-item';
                item.setAttribute('data-index', i);

                // Use any available phone/web
                const phone = searchPlace.nationalPhoneNumber || searchPlace.internationalPhoneNumber;
                const web = searchPlace.websiteURI;

                item.innerHTML = `
                    <div class="place-name">${escapeHtml(searchPlace.displayName)}</div>
                    <div class="place-address">${escapeHtml(searchPlace.formattedAddress)}</div>
                    ${searchPlace.rating ? `
                        <div class="place-rating">
                            <span class="stars">⭐ ${searchPlace.rating}</span>
                            <span class="count">(${searchPlace.userRatingCount || 0} recensioni)</span>
                        </div>
                    ` : ''}
                    <div class="place-actions-mini mt-2" style="margin-top: 10px; display: flex; gap: 10px;">
                        ${phone ? `<a href="tel:${phone}" class="track-click" data-type="telefono" data-name="${escapeHtml(searchPlace.displayName)}" data-id="${searchPlace.id}" style="font-weight: bold; font-size: 0.85rem; color: #2563eb; text-decoration: none;">📞 Chiama</a>` : ''}
                        ${web ? `<a href="${web}" target="_blank" class="track-click" data-type="sito" data-name="${escapeHtml(searchPlace.displayName)}" data-id="${searchPlace.id}" style="font-weight: bold; font-size: 0.85rem; color: #2563eb; text-decoration: none;">🌐 Sito</a>` : ''}
                    </div>
                `;

                listContainer.appendChild(item);

                // Events
                marker.addListener('gmp-click', () => showPlaceDetails(searchPlace, marker, item));
                item.addEventListener('click', (e) => {
                    if (e.target.tagName === 'A') return;
                    map.panTo(searchPlace.location);
                    map.setZoom(15);
                    showPlaceDetails(searchPlace, marker, item);
                });

                // Bind tracking
                item.querySelectorAll('.track-click').forEach(link => {
                    link.addEventListener('click', function (e) {
                        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(searchPlace.displayName + ' ' + searchPlace.formattedAddress)}&query_place_id=${searchPlace.id}`;
                        trackClick(this.dataset.type, this.dataset.name, this.dataset.id, searchPlace.websiteURI, mapsUrl);
                    });
                });
            }
        } catch (error) {
            console.error('Final error processing place:', searchPlace.displayName, error);
            renderFallbackItem(searchPlace, listContainer, i);
        }
    }
}

// Global helper for legacy details
function getLegacyDetails(placeId) {
    return new Promise((resolve) => {
        try {
            const service = new google.maps.places.PlacesService(map);
            service.getDetails({
                placeId: placeId,
                fields: ['formatted_phone_number', 'website']
            }, (details, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    resolve(details);
                } else {
                    console.warn(`Legacy getDetails failed for ${placeId}, status: ${status}`);
                    resolve(null);
                }
            });
        } catch (e) {
            console.error('Legacy service error:', e);
            resolve(null);
        }
    });
}

function renderFallbackItem(place, container, index) {
    const item = document.createElement('div');
    item.className = 'place-item';
    item.setAttribute('data-index', index);
    item.innerHTML = `
        <div class="place-name">${escapeHtml(place.displayName)}</div>
        <div class="place-address">${escapeHtml(place.formattedAddress)}</div>
    `;
    container.appendChild(item);
}

function trackClick(type, name, id, websiteUrl = null, mapsUrl = null) {
    const mapElement = document.getElementById('map');
    const trackingData = {
        type: type,
        place_name: name,
        place_id: id,
        page_url: window.location.href,
        website_url: websiteUrl || '',
        google_maps_url: mapsUrl || '',
        // Detailed location from map attributes
        servizio: mapElement?.dataset?.servizio || '',
        regione: mapElement?.dataset?.regione || '',
        provincia: mapElement?.dataset?.provincia || '',
        comune: mapElement?.dataset?.comune || ''
    };

    fetch('/api/track-click.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(trackingData)
    }).catch(err => console.error('Tracking error:', err));
}

function showPlaceDetails(place, marker, listItem) {
    // Highlight item
    document.querySelectorAll('.place-item').forEach(el => el.classList.remove('active'));
    if (listItem) listItem.classList.add('active');

    // Scroll to item if not visible
    if (listItem) {
        listItem.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    const phone = place.nationalPhoneNumber || place.internationalPhoneNumber;
    const web = place.websiteURI;

    const content = `
        <div style="padding: 10px; max-width: 250px;">
            <h3 style="margin: 0 0 5px 0; font-size: 1.1rem; color: #1e293b;">${place.displayName}</h3>
            <p style="margin: 0 0 10px 0; font-size: 0.9rem; color: #64748b;">${place.formattedAddress}</p>
            
            ${phone ? `
                <p style="margin: 0 0 5px 0; font-size: 0.9rem;">
                    <strong>Tel:</strong> <a href="tel:${phone}" onclick="trackClick('telefono', '${place.displayName}', '${place.id}')" style="color: #2563eb; text-decoration: none;">${phone}</a>
                </p>
            ` : ''}

            ${place.rating ? `<div style="color: #f59e0b; font-weight: bold; margin-bottom: 10px;">⭐ ${place.rating} / 5</div>` : ''}
            
            <div style="display: flex; flex-direction: column; gap: 8px;">
                ${web ? `
                    <a href="${web}" target="_blank" onclick="trackClick('sito', '${place.displayName}', '${place.id}')" 
                       style="display: block; background: #fff; color: #2563eb; border: 1px solid #2563eb; text-align: center; padding: 8px; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">
                       Visita Sito Web
                    </a>
                ` : ''}
                
                    <a href="https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.displayName + ' ' + place.formattedAddress)}&query_place_id=${place.id}" 
                       target="_blank" 
                       onclick="trackClick('mappe', '${place.displayName}', '${place.id}', '${place.websiteURI || ''}', 'https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(place.displayName + ' ' + place.formattedAddress)}&query_place_id=${place.id}')"
                       style="display: block; background: #2563eb; color: white; text-align: center; padding: 8px; border-radius: 4px; text-decoration: none; font-size: 0.9rem;">
                       Apri in Google Maps
                    </a>
            </div>
        </div>
    `;
    infoWindow.setContent(content);
    infoWindow.open(map, marker);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Map initialization handled via script
window.initMap = initMap;

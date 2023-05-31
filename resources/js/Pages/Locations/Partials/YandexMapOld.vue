<template>
    <div id="map" class="w-full h-[300px] bg-gray-300"></div>
</template>

<script>
export default {
    props:['coords', 'yaLocationCoords'],

    mounted() {
        ymaps.ready(init);
        var myMap;
        let component = this;

        yandexCoordsInput.oninput = () => {
            // TODO validate and clean coordsInput

            let long = yandexCoordsInput.value.split(',')[0];
            let lat = yandexCoordsInput.value.split(',')[1];

            myMap.setCenter([long, lat]);
            myMap.setZoom(11);
            myMap.geoObjects.removeAll();
            myMap.geoObjects.add(new ymaps.Placemark([long, lat], {
                balloonContent: [long, lat],
                hintContent: [long, lat],
            }))
        };

        function init () {
            myMap = new ymaps.Map("map", {
                center: component.coords,
                zoom: 11,
                controls: ['zoomControl'],
            }, {
                yandexMapDisablePoiInteractivity: true,
            });

            myMap.events.add('click', function (e) {
                let locationCoords = e.get('coords');
                component.emitCoords(locationCoords);

                myMap.geoObjects.removeAll();
                myMap.geoObjects.add(new ymaps.Placemark(locationCoords, {
                    balloonContent: locationCoords,
                    hintContent: locationCoords,
                }, {
                    // preset: 'islands#icon', iconColor: '#0095b6'
                }));
            });

            myMap.behaviors.disable('scrollZoom');

            // Set center and zoom to accident region coords, then the accident's video is loaded
            document.getElementById('accidentVideo_0').onload = function () {
                myMap.setCenter(component.coords);
                myMap.setZoom(11);
            };
            // Set center and zoom to default (Moscow, 7) and remove mark on map after location submit
            document.getElementById('locationSubmitButton').onclick = function () {
                myMap.setCenter([55.76, 37.64]);
                myMap.setZoom(7);
                myMap.geoObjects.removeAll();
            };
        }
    },
    methods:{
        emitCoords(locationCoords){
            this.$emit('sendLocationCoords', locationCoords)
        },
    },
}
</script>

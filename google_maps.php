<?php

class cfs_google_maps extends cfs_field
{

    function __construct() {
        $this->name = 'google_maps';
        $this->label = __( 'Google Maps', 'cfs' );
    }

    function html( $field ) {
        $latLng = isset($field->value[0]) ? $field->value[0] : null;
        $address = isset($field->value[1]) ? $field->value[1] : null;
    ?>
        <script>
        (function($) {
            $(function() {
                var
                   $group = $('#google_map_field_<?php echo $field->id?>')
                   ,  $canvas = $group.find('.map_canvas')
                   ,  $inputLatLng = $group.find('[name="<?php echo $field->input_name?>[0]"]')
                   ,  $inputAddress = $group.find('[name="<?php echo $field->input_name?>[1]"]')
                   ,  latLng = [<?php echo $latLng ? $latLng : '40.4,-98.7' ?>]
                   ;

                var map = new google.maps.Map($canvas.get(0), {
                    zoom: 4,
                    center: new google.maps.LatLng(latLng[0],latLng[1]),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    position: new google.maps.LatLng(latLng[0],latLng[1])
                });

                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    $inputLatLng.val(event.latLng.toUrlValue());
                });

                google.maps.event.addListener(marker, 'click', function(event) {
                    marker.setPosition(null);
                    $inputLatLng.val('');
                });

                google.maps.event.addListener(marker, 'dragend', function(event) {
                    $inputLatLng.val(event.latLng.toUrlValue());
                });

                $group.find('[type=button]').click(function() {
                    var geocoder = new google.maps.Geocoder();
                    geocoder.geocode(
                        {address: $inputAddress.val()},
                        function(results, status) {
                            if (status == google.maps.GeocoderStatus.OK) {
                                map.setCenter(results[0].geometry.location);
                                marker.setPosition(results[0].geometry.location);
                                $inputLatLng.val(results[0].geometry.location.toUrlValue());
                            } else {
                                alert('Error locating address.')
                            }
                        }
                    )
                });
            });
        })(jQuery);
        </script>
        <div id="google_map_field_<?php echo $field->id?>">
            <div class="map_canvas" style="width:100%; height:250px"></div>
            <input
                type="text"
                readonly
                name="<?php  echo $field->input_name ?>[0]"
                class="<?php echo $field->input_class ?>"
                value="<?php echo $latLng ?>"
            />
            <input
                type="text"
                name="<?php echo $field->input_name ?>[1]"
                class=""
                value="<?php echo $address?>"
            />
            <input type="button" value="Update Map" />
        </div>
    <?php
    }

    function prepare_value($value, $field=null) {
        return $value;
    }

    function format_value_for_api($value, $field = null) {
        if(!empty($value)) {
           return array('latLng'=>$value[0],'address'=>$value[1]);
        }
    }

    function input_head( $field = null ) {
    ?>
        <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <?php
    }
}

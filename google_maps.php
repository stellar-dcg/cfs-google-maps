<?php

class cfs_google_maps extends cfs_field
{

    function __construct() {
        $this->name = 'google_maps';
        $this->label = __( 'Google Maps', 'cfs' );
    }


    function html( $field ) {
        $latlng = empty( $field->value ) ? '40.4,-98.7' : $field->value;
    ?>
        <script>
        (function($) {
            $(function() {
                var $canvas = $('#map_canvas_<?php echo $field->id?>');
                var map = new google.maps.Map($canvas.get(0), {
                    zoom: 4,
                    center: new google.maps.LatLng(<?php echo $latlng; ?>),
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                });

                var marker = new google.maps.Marker({
                    map: map,
                    draggable: true,
                    position: new google.maps.LatLng(<?php echo $latlng; ?>)
                });

                google.maps.event.addListener(map, 'click', function(event) {
                    marker.setPosition(event.latLng);
                    $canvas.closest('.field').find('input.google_maps').val(event.latLng.toUrlValue());
                });

                google.maps.event.addListener(marker, 'click', function(event) {
                    marker.setPosition(null);
                    $canvas.closest('.field').find('input.google_maps').val('');
                });

                google.maps.event.addListener(marker, 'dragend', function(event) {
                    $canvas.closest('.field').find('input.google_maps').val(event.latLng.toUrlValue());
                });
            });
        })(jQuery);
        </script>
        <div id="map_canvas_<?php echo $field->id?>" style="width:100%; height:250px"></div>
        <input type="hidden" name="<?php echo $field->input_name; ?>" class="<?php echo $field->input_class; ?>" value="<?php echo $field->value; ?>" />
    <?php
    }


    function input_head( $field = null ) {
    ?>
        <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <?php
    }
}

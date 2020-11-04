<?php
/**
 * Functions class
 */


//@todo rename Starter_Plugin
class DT_Starter_Plugin_Functions
{
    private static $_instance = null;
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    } // End instance()

    public function __construct() {
        add_filter( "dt_custom_fields_settings", [ $this, "dt_contact_fields" ], 1, 2 );
        add_filter( "dt_search_extra_post_meta_fields", array( $this, "dt_search_fields" ), 10, 1 );
        add_filter( 'dt_details_additional_tiles', [ $this, 'dt_details_additional_tiles' ], 10, 2 );

        add_action( "dt_details_additional_section", [ $this, "dt_add_section" ], 30, 2 );

    }

    public function dt_contact_fields( array $fields, string $post_type = ""){
        //check if we are dealing with a contact
        if ($post_type === "contacts"){
            //check if the language field is already set
            if ( !isset( $fields["language"] )){
                //define the language field
                $fields["language"] = [
                    "name" => __( "Spoken Language", "disciple_tools_language" ),
                    "type" => "key_select",
                    "default" => [
                        "english" => __( "English", "disciple_tools_language" ),
                        "french" => __( "French", "disciple_tools_language" )
                    ],
                    'tile' => 'contact_language'
                ];
            }
        }
        //don't forget to return the update fields array
        return $fields;
    }

    public static function dt_search_fields( array $fields ) {
        //add the "language" field added in the dt_contact_fields function to search
        array_push( $fields, "language" );
        return $fields;
    }

    public function dt_declare_section_id( $sections, $post_type = "" ){
        //check if we are on a contact
        if ($post_type === "contacts"){
            $contact_fields = Disciple_Tools_Contact_Post_Type::instance()->get_custom_fields_settings();
            //check if the language field is set
            if ( isset( $contact_fields["language"] ) ){
                $sections[] = "contact_language";
            }
            //add more section ids here if you want...
        }
        return $sections;
    }

    public function dt_details_additional_tiles( $tiles, $post_type = "" ){
        if ( $post_type === "contacts" ){
            $tiles["contact_language"] = [ "label" => __( "Language", 'disciple_tools' ) ];
        }
        return $tiles;
    }

    public function dt_add_section( $section, $post_type ) {
        if ( $section === "contact_language" && $post_type === "contacts" ) {
            ?>
            <!-- need you own css? -->
            <style type="text/css">
                .required-style-example {
                    color: red
                }
            </style>

            <p class="required-style-example"> Wanna know something cool? D.T is translated into multiple languages. <a href="https://disciple.tools/translation/">Check it out!</a></p>

            <script type="application/javascript">
                //enter jquery here if you need it
                jQuery(($) => {
                })
            </script>
            <?php
        }
    }

}

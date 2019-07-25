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
        add_filter( "dt_details_additional_section_ids", [ $this, "dt_declare_section_id" ], 999, 2 );
        add_action( "dt_details_additional_section", [ $this, "dt_add_section" ] );

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
                    ]
                ];
            }
        }
        //don't forget to return the update fields array
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

    public function dt_add_section( $section ) {
        if ( $section == "contact_language" ) {
            $contact_id     = get_the_ID();
            $contact_fields = Disciple_Tools_Contact_Post_Type::instance()->get_custom_fields_settings();
            $contact        = Disciple_Tools_Contacts::get_contact( $contact_id, true )
            ?>
            <!-- need you own css? -->
            <style type="text/css">
                .required-style-example {
                    color: red
                }
            </style>

            <label class="section-header">
                <?php esc_html_e( 'Language', 'disciple_tools' ) ?>
            </label>
            <div class="section-subheader">
                <?php esc_html_e( 'Spoken Language', 'disciple_tools' ) ?> <span class="required-style-example">*</span>
            </div>
            <select class="select-field" id="language" style="margin-bottom: 0px">
                <?php
                foreach ( $contact_fields["language"]["default"] as $key => $value ) {
                    if ( $contact["language"]["key"] === $key ) {
                        ?>
                        <option value="<?php echo esc_html( $key ) ?>"
                                selected><?php echo esc_html( $value["label"] ); ?></option>
                    <?php } else { ?>
                        <option value="<?php echo esc_html( $key ) ?>"><?php echo esc_html( $value["label"] ); ?></option>
                    <?php }
                }
                ?>
            </select>


            <script type="application/javascript">
                //enter jquery here if you need it
                jQuery(($) => {
                })
            </script>
            <?php
        }

        //add more sections here if you want...
    }

}

<?php
class My_Istymo_SCI {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function display_sci_list() {
        // Get current user information
        $current_user = wp_get_current_user();
        $codePostal = get_field('code_postal_user', 'user_' . $current_user->ID);
        $codesPostauxArray = array();

        // Clean and split postal codes
        $codePostal = str_replace(' ', '', $codePostal);
        $codesPostauxArray = explode(';', $codePostal);

        ob_start();
        ?>
        <div class="aide_a_la_prospection">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <p>
                <strong>Aide à la prospection Lead SCI</strong> <br>
                <br>Grâce à notre solution innovante de prospection, identifiez rapidement et efficacement les Sociétés Civiles Immobilières (SCI) et proposez vos services.
            </p>
        </div>

        <div class="my-istymo-sci-container">
            <div class="my-istymo-dpe-container-form">
                <form id="filtre-codePostal-sci">
                    <label for="codePostal-sci">Sélectionnez votre code postal :</label>
                    <select name="codePostal" id="codePostal-sci">
                        <?php
                        foreach ($codesPostauxArray as $value) {
                            echo '<option value="' . esc_attr($value) . '">' . esc_html($value) . '</option>';
                        }
                        ?>
                    </select>
                </form>

                <div class="dashboard-search-wrap-lead">
                    <form id="dashboard-search-form-sci" class="dashboard-search-form" autocomplete="off">
                        <input type="text" name="prop_search" id="dashboard-search-form-input-leads-sci" placeholder="Filtrer par mot clé">
                    </form>
                </div>
            </div>

            <div class="dashboard-posts-list">
                <div class="dashboard-posts-list-head">
                    <div class="small-column-wrap">
                        <div class="column column-nom-prenom"><span><?php esc_html_e('NOM / Prénom (Dirigeant)', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-adresse"><span><?php esc_html_e('Adresse', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-ville"><span><?php esc_html_e('Ville', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-code-postal"><span><?php esc_html_e('Code postal', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-code-denomination"><span><?php esc_html_e('Dénomination', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-google-maps"><span><?php esc_html_e('Géolocalisation', 'my-istymo'); ?></span></div>
                    </div>
                </div>
                <div id="sciResultsContainer"></div>
            </div>
        </div>

        <script>
        jQuery(document).ready(function($) {
            var timeout;

            function fetchData(postalCode) {
                $('#sciResultsContainer').html('<p style="text-align: center; margin-top: 20px;">Chargement en cours...</p>');

                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'get_api_data',
                        username: 'ainaguiseppe@gmail.com',
                        password: '=/FEScXU+E2qgjg',
                        postalCode: postalCode
                    },
                    success: function(response) {
                        if (response.success) {
                            var results = response.data;
                            var container = $('#sciResultsContainer');

                            container.empty();

                            $.each(results, function(index, result) {
                                var name = result.formality.content.personneMorale.composition.pouvoirs[0].individu.descriptionPersonne.nom + ' ' + 
                                         result.formality.content.personneMorale.composition.pouvoirs[0].individu.descriptionPersonne.prenoms[0];

                                var address = '';
                                if (result.formality.content.personneMorale.adresseEntreprise.adresse.numVoie) {
                                    address += result.formality.content.personneMorale.adresseEntreprise.adresse.numVoie + ' ';
                                }
                                if (result.formality.content.personneMorale.adresseEntreprise.adresse.typeVoie) {
                                    address += result.formality.content.personneMorale.adresseEntreprise.adresse.typeVoie + ' ';
                                }
                                if (result.formality.content.personneMorale.adresseEntreprise.adresse.voie) {
                                    address += result.formality.content.personneMorale.adresseEntreprise.adresse.voie;
                                }
                                
                                var city = result.formality.content.personneMorale.adresseEntreprise.adresse.commune || '';
                                var postalCode = result.formality.content.personneMorale.adresseEntreprise.adresse.codePostal || '';
                                var denomination = result.formality.content.personneMorale.identite.entreprise.denomination || '';

                                var googleMapsUrl = "https://www.google.com/maps/place/" + encodeURIComponent(address + ' ' + result.formality.content.personneMorale.adresseEntreprise.adresse.codeInseeCommune + ' ' + city);

                                var html = '<div class="property-column-wrap">' +
                                    '<div class="small-column-wrap"><div class="column column-date"><span class="property-name"><p>' + name + '</p></span></div></div>' +
                                    '<div class="small-column-wrap"><div class="column column-date"><span class="property-adresse"><p>' + address + '</p></span></div></div>' +
                                    '<div class="small-column-wrap"><div class="column column-date ville"><span class="property-city"><p>' + city + '</p></span></div></div>' +
                                    '<div class="small-column-wrap"><div class="column column-date code-postal"><span class="property-postalcode"><p>' + postalCode + '</p></span></div></div>' +
                                    '<div class="small-column-wrap"><div class="column column-date denomination"><span class="property-denomination"><p>' + denomination + '</p></span></div></div>' +
                                    '<div class="small-column-wrap"><div class="column column-maps"><span class="property-maps"><a href="' + googleMapsUrl + '" target="_blank">Localiser SCI</a></span></div></div>' +
                                    '</div>';

                                container.append(html);
                            });
                        } else {
                            $('#sciResultsContainer').html('<button onclick="location.reload()">Rafraîchir la page</button>');
                        }
                    },
                    error: function() {
                        $('#sciResultsContainer').html('<p style="text-align: center; margin-top: 20px;">Erreur de connexion.</p>');
                    }
                });
            }

            $('#codePostal-sci').on('change', function() {
                clearTimeout(timeout);
                var selectedPostalCode = $(this).val();
                timeout = setTimeout(function() {
                    fetchData(selectedPostalCode);
                }, 300);
            });

            var initialPostalCode = $('#codePostal-sci').val();
            if (initialPostalCode) {
                fetchData(initialPostalCode);
            }
        });
        </script>
        <?php
        return ob_get_clean();
    }
}
<?php
class My_Istymo_DPE {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function display_dpe_list() {
        // Get current user information
        $current_user = wp_get_current_user();
        $codePostal = get_field('code_postal_user', 'user_' . $current_user->ID);
        $codesPostauxArray = array();

        // Clean and split postal codes
        $codePostal = str_replace(' ', '', $codePostal);
        $codesPostauxArray = explode(';', $codePostal);

        // Localize array for JavaScript
        wp_localize_script($this->plugin_name . '-dpe', 'assocArray', $codesPostauxArray);

        // Get initial API URL
        $request_url = 'https://data.ademe.fr/data-fair/api/v1/datasets/dpe-v2-logements-existants/lines?sort=-Date_r%C3%A9ception_DPE&size=50&Code_postal_%28brut%29_eq=' . reset($codesPostauxArray);

        ob_start();
        ?>
        <div class="aide_a_la_prospection">
            <i class="fa fa-info-circle" aria-hidden="true"></i>
            <p>
                <strong>Aide à la prospection Lead DPE</strong> <br>
                <br>L'obligation du Diagnostic de Performance Énergétique concerne toute personne désirant mettre en vente un bien immobilier.
                Facilitez votre prospection et anticipez les ventes à venir en consultant la liste des DPE réalisés sur vos secteurs d'activité.
            </p>
        </div>

        <div class="my-istymo-dpe-container">
            <div class="my-istymo-dpe-container-form">
                <form id="filtre-codePostal">
                    <label for="codePostal">Sélectionnez votre code postal :</label>
                    <select name="codePostal" id="codePostal">
                        <?php
                        foreach ($codesPostauxArray as $value) {
                            echo '<option value="' . esc_attr($value) . '">' . esc_html($value) . '</option>';
                        }
                        ?>
                    </select>
                </form>

                <div class="dashboard-search-wrap-lead">
                    <form id="dashboard-search-form" class="dashboard-search-form" autocomplete="off">
                        <input type="text" name="prop_search" id="dashboard-search-form-input-leads" placeholder="Filtrer par mot clé">
                    </form>
                </div>
            </div>

            <div class="dashboard-posts-list">
                <div class="dashboard-posts-list-head">
                    <div class="small-column-wrap">
                        <div class="column column-type-batiment"><span><?php esc_html_e("Habitation", 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-date-dpe"><span><?php esc_html_e('Date DPE', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-adresse"><span><?php esc_html_e('Adresse', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-commune"><span><?php esc_html_e('Commune', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-code-postal"><span><?php esc_html_e('Code postal', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-surface-habitable"><span><?php esc_html_e('Surface Habitable', 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-complement-adresse"><span><?php esc_html_e("Complément adresse", 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-complement-adresse"><span><?php esc_html_e("Étiquette DPE", 'my-istymo'); ?></span></div>
                    </div>
                    <div class="small-column-wrap">
                        <div class="column column-google-maps"><span><?php esc_html_e('Géolocalisation', 'my-istymo'); ?></span></div>
                    </div>    
                </div>
                <div id="resultsContainer"></div>
            </div>

            <div class="container">
                <div class="pagination">
                    <button id="loadMoreButton" class="btn2">Voir plus <i class="fas fa-arrow-right"></i></button>
                </div>
            </div>
        </div>

        <script>
            var nextPageUrl = '<?php echo esc_url($request_url); ?>';
            var totalResults = 0;

            function fetchDataFromApi(url, successCallback, errorCallback) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', url, true);

                xhr.onload = function () {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        var parsedResponse = JSON.parse(xhr.responseText);
                        nextPageUrl = parsedResponse.next || null;

                        var loadMoreButton = document.getElementById('loadMoreButton');
                        if (nextPageUrl === null) {
                            loadMoreButton.classList.add('disable-button');
                        } else {
                            loadMoreButton.classList.remove('disable-button');
                        }

                        successCallback(xhr.responseText);
                    } else {
                        errorCallback();
                    }
                };

                xhr.send();
            }

            function appendResultsToContainer(response) {
                var container = document.getElementById('resultsContainer');
                var parsedResponse = JSON.parse(response);

                if (parsedResponse.results && parsedResponse.results.length > 0) {
                    totalResults = parsedResponse.total;

                    var keyOrder = [
                        'Type_bâtiment',
                        'Date_réception_DPE',
                        'Adresse_brute',
                        'Nom__commune_(BAN)',
                        'Code_postal_(brut)',
                        'Surface_habitable_logement',
                        "Complément_d'adresse_logement",
                        'Etiquette_DPE',
                        'Adresse_(BAN)'
                    ];

                    parsedResponse.results.forEach(function (result) {
                        var propertyColumnWrap = document.createElement('div');
                        propertyColumnWrap.className = 'property-column-wrap';

                        keyOrder.forEach(function (key) {
                            var smallColumnWrap = document.createElement('div');
                            smallColumnWrap.className = 'small-column-wrap';

                            var column = document.createElement('div');
                            column.className = 'column column-date';
                            var value = result[key] !== undefined ? result[key] : '';

                            if (key === 'Date_réception_DPE') {
                                var dateObj = new Date(value);
                                var options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                                var formattedDate = dateObj.toLocaleDateString('fr-FR', options);
                                column.innerHTML = '<span class="property-date">' + formattedDate + '</span>';
                            } else if (key === 'Adresse_(BAN)') {
                                column.innerHTML = '<span class="property-date"><a class="map-it-link" href="https://www.google.com/maps/place/' + encodeURIComponent(result[key]) + '" rel="noopener noreferrer" target="_blank">Localiser le bien</a></span>';
                            } else {
                                column.innerHTML = '<span class="property-date">' + value + '</span>';
                            }

                            smallColumnWrap.appendChild(column);
                            propertyColumnWrap.appendChild(smallColumnWrap);
                        });

                        container.appendChild(propertyColumnWrap);
                    });

                    var loadMoreButton = document.getElementById('loadMoreButton');
                    loadMoreButton.disabled = totalResults < 50;
                }
            }

            function loadNextPage() {
                if (nextPageUrl) {
                    var cleanUrl = nextPageUrl.replace(/#038;/g, '');
                    fetchDataFromApi(cleanUrl, appendResultsToContainer, function () {
                        console.error('Error fetching data from the API');
                    });
                }
            }

            window.addEventListener('scroll', function() {
                if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight) {
                    loadNextPage();
                }
            });

            window.addEventListener('load', loadNextPage);

            var searchInput = document.getElementById('dashboard-search-form-input-leads');
            var codePostalSelect = document.getElementById('codePostal');

            searchInput.addEventListener('input', function() {
                var searchTerm = this.value.trim();
                var selectedValue = codePostalSelect.value;
                nextPageUrl = `https://data.ademe.fr/data-fair/api/v1/datasets/dpe-v2-logements-existants/lines?sort=-Date_r%C3%A9ception_DPE&size=50&Code_postal_%28brut%29_eq=${selectedValue}&q=${searchTerm}`;
                document.getElementById('resultsContainer').innerHTML = '';
                loadNextPage();
            });

            searchInput.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    loadNextPage();
                }
            });

            codePostalSelect.addEventListener('change', function() {
                var selectedValue = this.value;
                nextPageUrl = `https://data.ademe.fr/data-fair/api/v1/datasets/dpe-v2-logements-existants/lines?sort=-Date_r%C3%A9ception_DPE&size=50&Code_postal_%28brut%29_eq=${selectedValue}`;
                document.getElementById('resultsContainer').innerHTML = '';
                loadNextPage();
            });
        </script>
        <?php
        return ob_get_clean();
    }
}
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_POST['url'];

    // Funzione per ottenere un elenco di tutte le pagine di un sito web
    function getAllPages($domain) {
        $pages = [];

        // Esegui una richiesta HTTP GET alla pagina principale del dominio
        $html = file_get_contents($domain);

        // Crea un oggetto DOMDocument per analizzare il contenuto HTML
        $dom = new DOMDocument();
        @$dom->loadHTML($html); // Usa @ per sopprimere gli avvisi

        // Trova tutti i tag 'a' nella pagina
        $links = $dom->getElementsByTagName('a');

        // Estrai gli URL dei link e aggiungili all'array delle pagine
        foreach ($links as $link) {
            $url = $link->getAttribute('href');
            // Verifica se l'URL è relativo e aggiungilo al dominio se necessario
            if (strpos($url, 'http') !== 0) {
                $url = rtrim($domain, '/') . '/' . ltrim($url, '/');
            }
            // Assicurati che l'URL sia un URL interno al dominio
            if (strpos($url, $domain) === 0 && !empty($url) && $url !== '#' && $url !== 'javascript:void(0)' && $url !== $domain . '/#') {
                $pages[] = $url;
            }
        }

        return $pages;
    }

    // Sostituisci con il tuo dominio
    $domain = $url;
    $allPages = getAllPages($domain);

    // Restituisci l'elenco delle pagine come JSON
    header('Content-Type: application/json');
    echo json_encode($allPages);
} else {
    http_response_code(405); // Metodo non consentito
    echo "Metodo non consentito.";
}
?>

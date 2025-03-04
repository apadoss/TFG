<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Panther\Client;
use Spatie\Browsershot\Browsershot;

class ScrapingController extends Controller
{
    public function pcComponentesScraper() {
        $html = Browsershot::url('https://www.pccomponentes.com/tarjetas-graficas')
            ->bodyHtml();
        
        dd($html);

        // $client = Client::createFirefoxClient();
        // $client->request('GET', 'https://www.pccomponentes.com/tarjetas-graficas');

        // sleep(5);

        // try {
        //     $crawler = $client->waitFor('#category-list-product-grid');
        //     $html = $crawler->filter('div:has(> #category-list-product-grid)')->text();
        //     echo $html;
        // } catch (\Exception $e) {
        //     echo "Error: " . $e->getMessage();
        // }

        // $client->close();
    }

    public function coolModScraper($maxPages=20) {
        $products = [];
        $page = 1;
        $baseUrl = "https://www.coolmod.com/tarjetas-graficas/";

        while ($page <= $maxPages) {
            $url = "{$baseUrl}?pagina={$page}";
    
            $response = Http::get($url);
            if ($response->failed()) {
                break;
            }

            $html = $response->body();
            
            if (strpos($html, "No hay productos disponibles actualmente en esta categoría.") !== false) {
                break;
            }

            $crawler = new Crawler($html);
    
            // Extraer productos de la página actual
            $crawler->filter('input[name="price[]"]')->each(function (Crawler $node, $index) use (&$products, $crawler) {
                $price = $node->attr('value');
    
                // Nombre del producto
                $nameNode = $crawler->filter('input[name="item_name[]"]')->eq($index);
                $name = $nameNode->count() ? $nameNode->attr('value') : null;
    
                // URL del producto
                $urlNode = $crawler->filter('a[data-itemname]')->eq($index);
                $productUrl = $urlNode->count() ? $urlNode->attr('href') : null;
    
                $products[count($products) + 1] = [
                    'price' => $price,
                    'name' => $name,
                    'url' => $productUrl,
                ];
            });
    
            $page++; // Siguiente página
            sleep(2);
        }
    
        return response()->json($products);
    }

    public function amazonScraper() {
        $browser = new HttpBrowser(HttpClient::create());
        $request = $browser->request('GET', "https://www.amazon.es/s?i=computers&rh=n%3A937925031%2Cp_123%3A341127%257C368902&s=popularity-rank&dc&fs=true&qid=1739125700&rnid=91049100031&xpid=hFkEsjIqmiJbL&ref=sr_pg_1");
        $html = $request->outerHtml();

        $crawler = new Crawler($html);

        $products = [];

        $crawler->filter('div[data-component-type="s-search-result"]')->each(function(Crawler $node, $index) use (&$products) {
            // Precio
            $price = '';
            if ($node->filter('span.a-price-whole')->count()) {
                $priceWhole = $node->filter('span.a-price-whole')->text();
                // Concatenar parte fraccionaria
                $priceFraction = $node->filter('span.a-price-fraction')->count() ? $node->filter('span.a-price-fraction')->text() : '';
                $price = $priceWhole . $priceFraction;
            }

            // Nombre del producto
            $name = '';
            if ($node->filter('a.a-link-normal.s-line-clamp-4.s-link-style.a-text-normal')->count()) {
                $name = $node->filter('a.a-link-normal.s-line-clamp-4.s-link-style.a-text-normal')->text();
            }

            // URL del producto
            $url = '';
            if ($node->filter('a.a-link-normal.s-line-clamp-4.s-link-style.a-text-normal')->count()) {
                $url = $node->filter('a.a-link-normal.s-line-clamp-4.s-link-style.a-text-normal')->attr('href');
                if ($url && strpos($url, 'http') !== 0) {
                    $url = 'https://www.amazon.es' . $url;
                }
            }

            $products[$index + 1] = [
                'price' => $price,
                'name'  => $name,
                'url'   => $url,
            ];
        });

        return response()->json($products);
    }

    public function neoByteScraper() {
        $browser = new HttpBrowser(HttpClient::create());
        $request = $browser->request('GET', "https://www.neobyte.es/placas-base-106?utm_source=componentes&utm_medium=landing&utm_campaign=Neobyte&order=product.position.asc&resultsPerPage=9999999");
        $html = $request->outerHtml();

        $crawler = new Crawler($html);
        $scriptNode = $crawler->filter('script#microdata-product-list-script[type="application/ld+json"]');

        if ($scriptNode->count() > 0) {
            $jsonContent = $scriptNode->text();
            $data = json_decode($jsonContent, true);
            return response()->json($data['itemListElement']);
        }

        return response()->json(['error' => 'No se encontró el script con JSON'], 404);
    }
}
